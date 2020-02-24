<?PHP
include "../Graphes/GrapheLignes.php";
include "Classe_CalculsPDF.php";
include "../SCI_EF.inc";

if (IsSet($_GET["cle"]))
{
	if ($_GET["cle"]=="tracemoteur")
	{
		//http://localhost/SCI_EF/PDF/PDF_Synthese.php?cle=tracemoteur&id=1&xml=SIM_00011_00016.xml
		//http://localhost/SCI_EF/PDF/PDF_Synthese.php?xml=SIM_00011_00016.xml
		$LO_XML = simplexml_load_file("../" . $_GET["xml"]);
		$LT_Elements = array();
		$LT_Elements[]   = new ClasseCalculs("". "", "");
		Moteur_Charge();
		Moteur_ExportTrace();
	}
	if ($_GET["cle"]=="graphe")
	{
		$LO_XML = simplexml_load_file("../Data/XML/" . $_GET["xml"]);
		$LT_Elements = array();
		$LT_Elements[]   = new ClasseCalculs("". "", "");
		Moteur_Charge();
		Moteur_Graphe();
	}
}


Function MOTEUR_Charge(){
Global $LT_Elements, $LO_XML;
	
	$LN_MoisAnniversaire = SubStr($LO_XML->simulation->datedebut, 3, 2);
	
	$LN_AssurancePNO     	= round(TOOL_DecodeNum($LO_XML->simulation->charges->assurancepno)/12,2);
	$LN_EvolPNO		= TOOL_DecodeNum($LO_XML->simulation->charges->evolassurancepno);
	
	$LN_AssuranceGRL     	=  round(TOOL_DecodeNum($LO_XML->simulation->charges->assurancegrl)/12,2);
	$LN_EvolGRL		= TOOL_DecodeNum($LO_XML->simulation->charges->evolassurancegrl);
	
	$LN_AssuranceCopro 	=  round(TOOL_DecodeNum($LO_XML->simulation->charges->assurancecopro)/12,2);
	$LN_EvolAssurCopro	= TOOL_DecodeNum($LO_XML->simulation->charges->evolassurancecopro);
	
	$LN_ChargesCopro	=  round(TOOL_DecodeNum($LO_XML->simulation->charges->chargescopro)/12,2);
	$LN_EvolChargesCopro	= TOOL_DecodeNum($LO_XML->simulation->charges->evolchargescopro);
	
	$LN_AutresCharges	=  round(TOOL_DecodeNum($LO_XML->simulation->charges->autrescharges)/12,2);
	$LN_EvolAutresCharges	= TOOL_DecodeNum($LO_XML->simulation->charges->evolautrescharges);
	
	$LN_FraisAgence		=  round(TOOL_DecodeNum($LO_XML->simulation->charges->fraisgestion)/12,2);
	$LN_EvolFraisAgence	= TOOL_DecodeNum($LO_XML->simulation->charges->evolfraisgestion);
	
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;

	$LN_DureeAmortiImmo		= 0;
	$LN_DureeAmortiMeubles 	= 0;
	$LN_TaxeFonciere		= 0;	// ajoute MD
	$LN_SurfaceTotale		= 0;	// ajoute MD
	$LN_EvolTaxeFonciere	= 0;	// ajoute MD
	if ($LC_TypeFiscalite == "indiv") {
			$LN_TaxeFonciere		=  round(TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->taxefonciere)/12,2);
			$LN_EvolTaxeFonciere	= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->evoltaxefonciere);	
			
			$LN_DureeAmortiImmo		= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->amortimm);
			$LN_DureeAmortiMeubles 	= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->amortmob);
	}
	
	if ($LC_TypeFiscalite == "sci") {
			$LN_TaxeFonciere		=  round(TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->taxefonciere)/12,2);
			$LN_EvolTaxeFonciere	= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->evoltaxefonciere);	
			
			
	}
	
	$LN_TravauxEntretien	=  round(TOOL_DecodeNum($LO_XML->simulation->charges->travauxannuels)/12,2);
	$LN_EvolTravauxEntretien= TOOL_DecodeNum($LO_XML->simulation->charges->evoltravauxannuels);
	
	$LN_TypeEmprunt 	= $LO_XML->simulation->credit->typepret;
	$LN_MontantEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->emprunt);
	$LN_TauxEmprunt		= TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal);
	$LN_TauxADIEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->tauxassurance);
	$LN_DureeEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->duree);
	
	// Autres revenus de l'investisseur
		$LN_AutresRevenus = 0;
		
		$LN_RevenuSalaire 	= TOOL_DecodeNum($LO_XML->investisseur->ressources->salaire);
		$LN_RevenuFoncier 	= TOOL_DecodeNum($LO_XML->investisseur->ressources->foncier);
		$LN_RevenuPension 	= TOOL_DecodeNum($LO_XML->investisseur->charges->pensions);
		$LN_EvolRevenu 		= TOOL_DecodeNum($LO_XML->investisseur->ressources->evolrevenus);
		
		$LN_AutresRevenus += 	 $LN_RevenuSalaire
								+$LN_RevenuFoncier
								-$LN_RevenuPension;
	

	$LN_AssurancePNOAnnuelle 	=0;
	$LN_AssuranceGRLAnnuelle 	=0;
	$LN_AssuranceCoproAnnuelle	=0;
	$LN_ChargesCoproAnnuelle	=0;
	$LN_AutresChargesAnnuelle	=0;
	$LN_FraisAgenceAnnuelle		=0;
	$LN_TaxeFonciereAnnuelle 	= 0;
	$LN_TravauxEntretienAnnuelle	=0;
	
	$LN_TravauxRenovDeducAnnuelle = 0;
	$LN_TravauxRenovNonDeducAnnuelle = 0;
		
	$LN_ChargesDeductiblesCommunesAnnuelle = 0;
	$LN_ChargesIndividuel = 0;
	$LT_Elements[0]->LN_ChargesIndividuel=$LN_ChargesIndividuel;

	$LN_PrixAchat = TOOL_DecodeNum($LO_XML->simulation->frais->prixachat);
	
	// test si LMNP
	$LC_SiLMNP="false";
	$LN_SiLMNP = 0;
	$LN_SurfLMNP =0;
	if ($LC_TypeFiscalite == "indiv") {
		$LN_SurfaceTotale = 0;
		if(IsSet($LO_XML->simulation->descriptifs))
		{
			for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
				$LC_SiLMNPLu = $LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscallmnp;
				$LN_SurfLue = $LO_XML->simulation->descriptifs->descriptif[$j]->surface;
				$LN_SurfaceTotale = $LN_SurfaceTotale+$LN_SurfLue;
				if ($LC_SiLMNPLu=="true") {
					$LN_SurfLMNP = $LN_SurfLue ;
					$LN_SiLMNP = 1;
				}
				//echo " exist LMNP : ".$LN_SiLMNP."<br>";
			}
		}
	}
		//NB logements :
		$LN_NbLogements=0;
		$LN_NbLogementsNu=0;
		if(IsSet($LO_XML->simulation->descriptifs))
		{
			for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
				if ($LO_XML->simulation->descriptifs->descriptif[$j]->surface>0) {
					$LN_NbLogements+=1;
					if ($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscallmnp=="false") {
						$LN_NbLogementsNu+=1;
					}
				}
			}
		}
	
	// faire evoluer les mois
	$LN_Mois  = SubStr($LO_XML->simulation->datedebut, 3, 2);
	$LN_Annee = SubStr($LO_XML->simulation->datedebut, 6, 4);
	for ($i=1; $i< TOOL_DecodeNum($LO_XML->simulation->duree*12)+1; $i++)//for ($i=1; $i< TOOL_DecodeNum($LO_XML->simulation->duree)+1; $i++)
	{
		
		$LN_Mois = $LN_Mois + 1;
		
		if ($LN_Mois>12){
			// on change d'annee civile
			$LN_Mois=1;
			$LN_Annee=$LN_Annee + 1;
						
			$LN_AssurancePNOAnnuelle 	=  $LN_AssurancePNO;
			$LN_AssuranceGRLAnnuelle 	=  $LN_AssuranceGRL;
			$LN_AssuranceCoproAnnuelle 	=  $LN_AssuranceCopro;
			$LN_ChargesCoproAnnuelle 	=  $LN_ChargesCopro;
			$LN_AutresChargesAnnuelle 	=  $LN_AutresCharges;
			$LN_FraisAgenceAnnuelle 	=  $LN_FraisAgence;
			$LN_TaxeFonciereAnnuelle	=  $LN_TaxeFonciere;
			
			$LN_TravauxEntretienAnnuelle 	=  $LN_TravauxEntretien;
			//$LN_TravauxRenovNonDeducAnnuelle = $LN_TravauxRenovNonDeduc;
			//$LN_TravauxRenovDeducAnnuelle = $LN_TravauxRenovDeduc;
			
			//$LN_MensualiteEmpruntAnnuelle 	=  $LN_MensualiteEmprunt;
			//$LN_InteretEmpruntAnnuelle	=  $LN_InteretEmprunt;
			//$LN_MensualiteADI		=  $LN_MensualiteADI;
			
			//$LN_ChargesDeductiblesCommunesAnnuelle = $LN_ChargesDeductiblesCommunes;
		}
		
		$LT_Elements[$i-1]->LN_Mois = $LN_Mois;
		$LT_Elements[$i-1]->LN_Annee = $LN_Annee;
		
		// Credit : 
		Moteur_CreditTer ($i, $LN_TypeEmprunt);
		
		//Travaux et amortissements
		Moteur_TravauxAmortissements ($i);
				
		//Charges deductibles 
		$LN_ChargesDeductiblesCommunes = 	 $LN_AssurancePNO
									+$LN_AssuranceGRL
									+$LN_AssuranceCopro
									+$LN_ChargesCopro
									+$LN_FraisAgence
									+$LN_TaxeFonciere
									+$LN_TravauxEntretien
									+$LT_Elements[$i-1]->LN_InteretEmprunt
									+$LT_Elements[$i-1]->LN_MensualiteADI;
									
		// Loyers
		Moteur_Loyers ($i);
					
		if (($i>1) && ($LN_Mois == $LN_MoisAnniversaire)){
			// on change d'annee anniversaire
			$LN_AssurancePNO 	= round($LN_AssurancePNO*(1+$LN_EvolPNO/100),2);
			$LN_AssuranceGRL 	= round($LN_AssuranceGRL  		* (1+$LN_EvolGRL/100),2);
			$LN_AssuranceCopro 	= round($LN_AssuranceCopro  	* (1+$LN_EvolAssurCopro/100),2);
			$LN_ChargesCopro 	= round($LN_ChargesCopro  		* (1+$LN_EvolChargesCopro/100),2);
			$LN_AutresCharges 	= round($LN_AutresCharges  		* (1+$LN_EvolAutresCharges/100),2);
			$LN_FraisAgence 	= round($LN_FraisAgence  		* (1+$LN_EvolFraisAgence/100),2);
			$LN_TaxeFonciere 	= round($LN_TaxeFonciere  		* (1+$LN_EvolTaxeFonciere/100),2);
			$LN_TravauxEntretien = round($LN_TravauxEntretien  	* (1+$LN_EvolTravauxEntretien/100),2);
			$LN_AutresRevenus 	= round($LN_AutresRevenus		* (1+$LN_EvolRevenu/100),2);
		}
				
		$LN_AssurancePNOAnnuelle 	= $LN_AssurancePNOAnnuelle	+ $LN_AssurancePNO;
		$LN_AssuranceGRLAnnuelle 	= $LN_AssuranceGRLAnnuelle 	+ $LN_AssuranceGRL;
		$LN_AssuranceCoproAnnuelle 	= $LN_AssuranceCoproAnnuelle 	+ $LN_AssuranceCopro;
		$LN_ChargesCoproAnnuelle 	= $LN_ChargesCoproAnnuelle 	+ $LN_ChargesCopro;
		$LN_AutresChargesAnnuelle 	= $LN_AutresChargesAnnuelle 	+ $LN_AutresCharges;
		$LN_FraisAgenceAnnuelle 	= $LN_FraisAgenceAnnuelle 	+ $LN_FraisAgence;
		$LN_TaxeFonciereAnnuelle 	= $LN_TaxeFonciereAnnuelle 	+ $LN_TaxeFonciere;
		$LN_TravauxEntretienAnnuelle 	= $LN_TravauxEntretienAnnuelle 	+ $LN_TravauxEntretien;

		//$LN_TravauxRenovNonDeducAnnuelle = $LN_TravauxRenovNonDeducAnnuelle + $LN_TravauxRenovNonDeduc;
		//$LN_TravauxRenovDeducAnnuelle	 = $LN_TravauxRenovDeducAnnuelle + $LN_TravauxRenovDeduc;
		
		//$LN_MensualiteEmpruntAnnuelle 	= $LN_MensualiteEmpruntAnnuelle	+ $LN_MensualiteEmprunt;
						
		//$LN_InteretEmpruntAnnuelle	= $LN_InteretEmpruntAnnuelle	+ $LN_InteretEmprunt;
		//$LN_MensualiteADIAnnuelle	= $LN_MensualiteADIAnnuelle	+ $LN_MensualiteADI;
				
		$LN_ChargesDeductiblesCommunesAnnuelle = $LN_ChargesDeductiblesCommunesAnnuelle + $LN_ChargesDeductiblesCommunes;
		
		// on cree le niveau dans le tableau de la classe
		$LC_Mois  = Str_Pad($LN_Mois, 2, "0", STR_PAD_LEFT);
		$LC_Annee = Str_Pad($LN_Annee, 4, "0", STR_PAD_LEFT);
		$LT_Elements[] = new ClasseCalculs($LC_Annee . $LC_Mois, $LC_Mois);
		
		// on range les donnees
		$LT_Elements[$i-1]->LN_AssurancePNO 		= $LN_AssurancePNO;	
		$LT_Elements[$i-1]->LN_AssurancePNOAnnuelle 	= $LN_AssurancePNOAnnuelle;	
		
		$LT_Elements[$i-1]->LN_AssuranceGRL 		= $LN_AssuranceGRL;	
		$LT_Elements[$i-1]->LN_AssuranceGRLAnnuelle 	= $LN_AssuranceGRLAnnuelle;	
		
		$LT_Elements[$i-1]->LN_AssuranceCopro 		= $LN_AssuranceCopro;	
		$LT_Elements[$i-1]->LN_AssuranceCoproAnnuelle 	= $LN_AssuranceCoproAnnuelle;
		
		$LT_Elements[$i-1]->LN_ChargesCopro 		= $LN_ChargesCopro;	
		$LT_Elements[$i-1]->LN_ChargesCoproAnnuelle 	= $LN_ChargesCoproAnnuelle;
		
		$LT_Elements[$i-1]->LN_AutresCharges 		= $LN_AutresCharges;	
		$LT_Elements[$i-1]->LN_AutresChargesAnnuelle 	= $LN_AutresChargesAnnuelle;
		
		$LT_Elements[$i-1]->LN_FraisAgence 		= $LN_FraisAgence;	
		$LT_Elements[$i-1]->LN_FraisAgenceAnnuelle 	= $LN_FraisAgenceAnnuelle;
		
		$LT_Elements[$i-1]->LN_TaxeFonciere 		= $LN_TaxeFonciere;	
		$LT_Elements[$i-1]->LN_TaxeFonciereAnnuelle	= $LN_TaxeFonciereAnnuelle;
		
		$LT_Elements[$i-1]->LN_TravauxEntretien 	= $LN_TravauxEntretien;	
		$LT_Elements[$i-1]->LN_TravauxEntretienAnnuelle	= $LN_TravauxEntretienAnnuelle;
		
			
		//$LT_Elements[$i-1]->LN_TravauxRenovDeducAnnuelle 	= $LN_TravauxRenovDeducAnnuelle;
		//$LT_Elements[$i-1]->LN_TravauxRenovNonDeducAnnuelle 	= $LN_TravauxRenovNonDeducAnnuelle;
		
		//$LT_Elements[$i-1]->LN_MensualiteEmprunt 	= $LN_MensualiteEmprunt;	
		//$LT_Elements[$i-1]->LN_MensualiteEmpruntAnnuelle= $LN_MensualiteEmpruntAnnuelle;
		
		//$LT_Elements[$i-1]->LN_CapitalrestantDu 	= $LN_CapitalrestantDu;	
		
		//$LT_Elements[$i-1]->LN_InteretEmprunt		= $LN_InteretEmprunt;
		//$LT_Elements[$i-1]->LN_InteretEmpruntAnnuelle	= $LN_InteretEmpruntAnnuelle;
		
		//$LT_Elements[$i-1]->LN_MensualiteADI		= $LN_MensualiteADI;
		//$LT_Elements[$i-1]->LN_MensualiteADIAnnuelle	= $LN_MensualiteADIAnnuelle;
		
		$LT_Elements[$i-1]->LN_ChargesDeductiblesCommunes = $LN_ChargesDeductiblesCommunes;
		$LT_Elements[$i-1]->LN_ChargesDeductiblesCommunesAnnuelle = $LN_ChargesDeductiblesCommunesAnnuelle;
			
		$LT_Elements[$i-1]->LN_AutresRevenus=round($LN_AutresRevenus/12,2);

		
		
		$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
		if ($LC_TypeFiscalite=="indiv") {
			// Charges specifiques individuel :
			Moteur_ChargesIndividuelles ($i, $LN_SurfaceTotale, $LN_SurfLMNP, $LN_NbLogementsNu);
			
			// Charges specifiques LMNP
			Moteur_ChargesLMNP($i, $LN_SurfLMNP, $LN_SurfaceTotale, $LN_SiLMNP);
			
			// Montant imposable
			Moteur_MontantsImposables($i, $LN_SiLMNP);
			
			//Regimes d'imposition
			Moteur_RegimeImpositionIndividuel ($i, $LN_SiLMNP);
			
			// Montant impots individuel
			Moteur_MontantImposableIndividuel($i);

			// selection tranche d'impots	
			Moteur_SelectionTrancheImpotsIndividuel($i);

			// Montant de l'impots et CSG :
			Moteur_MontantImpotsCRSDIndividuel($i);
			
			// tresorerie :
			Moteur_TresorerieIndividuelle($i);
			Moteur_RemboursementAnticipeIndividuel($i);
			Moteur_DateRemboursementAnticipeIndiv($i);
		}
		else {
			// Charges SCI
			Moteur_ChargesSCI($i);
			// Montant imposable
			Moteur_MontantsImposables($i, $LN_SiLMNP);
			//Regimes d'imposition
			Moteur_RegimeImpositionIndividuel ($i, $LN_SiLMNP);
			
			Moteur_ImpotsSCI($i);
			Moteur_TresorerieSCI ($i);
			Moteur_DividendeAssocie ($i);
			Moteur_RemboursementAnticipeSCI($i);
			Moteur_DateRemboursementAnticipeSCI($i);
			Moteur_ImpotsAssocie($i);
		}
	}	
}

function Moteur_Credit ($i, $LN_TypeEmprunt) {
	Global  $LO_XML, $LT_Elements ;
	$LN_CapitalrestantDu =0;
	
	$LN_MontantEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->emprunt);
	$LN_TauxEmprunt		= TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal);
	$LN_TauxADIEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->tauxassurance);
	$LN_DureeEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->duree);
	
	if ($i==1) {
		
		$LN_CapitalrestantDu = $LN_MontantEmprunt;
		//echo "I=1 donc CRD = ".$LN_CapitalrestantDu;
	}
	else{
		$LN_CapitalRestantDu=$LT_Elements[$i-2]->LN_CRD; // capital restant du de la ligne précédente
		//echo "I sup 1 donc CRD = ".$LT_Elements[$i-2]->LN_CRD;
	}
		
	if ($LN_TypeEmprunt=="PRET_EchClassique")  
	{
		$LN_MensualiteEmprunt = round(($LN_MontantEmprunt *($LN_TauxEmprunt /1200))/(1-pow(1+($LN_TauxEmprunt /1200),(-1*$LN_DureeEmprunt *12))),2);
	}
	
	if ($LN_TypeEmprunt=="PRET_EchInFine")  
	{
		$LN_MensualiteEmprunt = round($LN_MontantEmprunt *($LN_TauxEmprunt /1200),2);
		//echo "in fine detecte !";
	}

	$LN_MensualiteADI = round($LN_MontantEmprunt*$LN_TauxADIEmprunt/1200,2);
	
	if ($i > $LN_DureeEmprunt*12) //Si le credit est termine
	{
		$LN_MensualiteEmprunt	=0;
		$LN_InteretEmprunt	=0;
		$LN_CapitalrestantDu	=0;
		$LN_MontantADI		=0;
	}
			
	if (($i <= $LN_DureeEmprunt*12) && ($i>1)) //Si le credit est en court
	{
		$LN_InteretEmprunt=round($LN_CapitalrestantDu*$LN_TauxEmprunt/1200,2);
		$LN_CapitalrestantDu=$LN_CapitalrestantDu-($LN_MensualiteEmprunt-$LN_InteretEmprunt);
		
		if ($LN_TypeEmprunt="PRET_EchInFine") {
			$LN_CapitalrestantDu = $LN_MontantEmprunt;
		}
	}
	
	// prêt in fine
	if (($LN_TypeEmprunt=="PRET_EchInFine")  && ($i== $LN_DureeEmprunt*12)) { // fin du crédit in fine
		$LN_MensualiteEmprunt += $LN_MontantEmprunt;
	}
	
	$LT_Elements[$i-1]->LN_CRD=$LN_CapitalRestantDu;
	$LT_Elements[$i-1]->LN_InteretEmprunt=$LN_InteretEmprunt;
	$LT_Elements[$i-1]->LN_Mensualite=$LN_MensualiteEmprunt;
	
	//echo "Mensualite : ".$LT_Elements[$i-1]->LN_Mensualite."<br>";//$LN_MensualiteEmprunt."<br>";
	//echo "CRD Enregistre : ".$LT_Elements[$i-1]->LN_CRD."<br>";

}

function Moteur_CreditBis ($i, $LN_TypeEmprunt) {
Global  $LO_XML, $LT_Elements ;
	
	$LN_MontantEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->emprunt);
	$LN_TauxEmprunt		= TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal);
	$LN_TauxADIEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->tauxassurance);
	$LN_DureeEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->duree);
	
	$LN_CapitalRestantDu=$LN_MontantEmprunt;
	$LN_MensualiteADI = round($LN_MontantEmprunt*$LN_TauxADIEmprunt/1200,2);

	
	
	if ($LN_TypeEmprunt=="PRET_EchClassique")  
	{
		$LN_MensualiteEmprunt = round(($LN_MontantEmprunt *($LN_TauxEmprunt /1200))/(1-pow(1+($LN_TauxEmprunt /1200),(-1*$LN_DureeEmprunt *12))),2);
		if ($i>1) {
			$LN_CapitalRestantDu=$LT_Elements[$i-2]->LN_CRD-($LN_MensualiteEmprunt-$LN_InteretEmprunt);
		}
	}	
	
	if ($LN_TypeEmprunt=="PRET_EchInFine")  
	{
		$LN_MensualiteEmprunt = round($LN_MontantEmprunt *($LN_TauxEmprunt /1200),2);
		//echo "in fine detecte !";
	}
	
	$LN_InteretEmprunt=round($LT_Elements[$i-1]->LN_CRD*$LN_TauxEmprunt/1200,2);
	
		// fin prêt in fine
	if (($LN_TypeEmprunt=="PRET_EchInFine")  && ($i== $LN_DureeEmprunt*12)) {
		$LN_MensualiteEmprunt += $LN_MontantEmprunt;
	}
	
	//Si le credit est termine
	if ($i > $LN_DureeEmprunt*12) 
	{
		$LN_MensualiteEmprunt	=0;
		$LN_InteretEmprunt	=0;
		$LN_CapitalRestantDu	=0;
		$LN_MontantADI		=0;
	}
	
	$LT_Elements[$i-1]->LN_CRD=$LN_CapitalRestantDu;
	$LT_Elements[$i-1]->LN_InteretEmprunt=$LN_InteretEmprunt;
	$LT_Elements[$i-1]->LN_Mensualite=$LN_MensualiteEmprunt;
	$LT_Elements[$i-1]->LN_MensualiteADI=$LN_MensualiteADI;
	
	//Echo "CRD : ".$LT_Elements[$i-1]->LN_CRD."<br>";
	//Echo "Interets : ".$LT_Elements[$i-1]->LN_InteretEmprunt."<br>";
	//Echo "Mensualité : ".$LT_Elements[$i-1]->LN_Mensualite."<br>";
	//Echo "ADDI : ".$LT_Elements[$i-1]->LN_MensualiteADI."<br>";
	
}

function Moteur_CreditTer ($i, $LN_TypeEmprunt) {
Global  $LO_XML, $LT_Elements ;
	$LN_DureeEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->duree);
	$LN_CapitalRestantDu = 0;
	
	if ($i<=$LN_DureeEmprunt) {//if ($i<=$LN_DureeEmprunt*12) {
		$LN_MontantEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->emprunt);
		$LN_TauxEmprunt		= TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal);
		$LN_TauxADIEmprunt	= TOOL_DecodeNum($LO_XML->simulation->credit->tauxassurance);
		
		$LN_MensualiteADI = round($LN_MontantEmprunt*$LN_TauxADIEmprunt/1200,2);
	
		if ($LN_TypeEmprunt=="PRET_EchClassique")  
		{
			$LN_MensualiteEmprunt = round(($LN_MontantEmprunt *($LN_TauxEmprunt /1200))/(1-pow(1+($LN_TauxEmprunt /1200),(-1*$LN_DureeEmprunt))),2);//$LN_MensualiteEmprunt = round(($LN_MontantEmprunt *($LN_TauxEmprunt /1200))/(1-pow(1+($LN_TauxEmprunt /1200),(-1*$LN_DureeEmprunt *12))),2);
			
			if ($i==1) {
				$LN_CapitalRestantDu = $LN_MontantEmprunt;
				//echo "I =1  CRD = ".$LN_CapitalRestantDu;
			}
			if ($i>1) {
				$LN_CapitalRestantDu=$LT_Elements[$i-2]->LN_CRD-($LN_MensualiteEmprunt-$LT_Elements[$i-2]->LN_InteretEmprunt);
				//echo "I sup 1 CRD etait ".$LT_Elements[$i-2]->LN_CRD."<br>";
				//echo "mensu - inbterets :  ".($LN_MensualiteEmprunt-$LT_Elements[$i-2]->LN_InteretEmprunt)."<br>";
				
			}
			//echo "crd  =".$LN_CapitalRestantDu."<br>";
			$LN_InteretEmprunt=round($LN_CapitalRestantDu*$LN_TauxEmprunt/1200,2);
		}
							  
		if ($LN_TypeEmprunt=="PRET_EchInFine") {
			$LN_MensualiteEmprunt = round($LN_MontantEmprunt *($LN_TauxEmprunt /1200),2);
			$LN_InteretEmprunt = $LN_MensualiteEmprunt;
			$LN_CapitalRestantDu = $LN_MontantEmprunt;
			
			if ($i==$LN_DureeEmprunt) {//if ($i==$LN_DureeEmprunt*12) { // fin de crédit in fine
				$LN_MensualiteEmprunt += $LN_MontantEmprunt;
			}
		}
	}
	else {
		$LN_MensualiteEmprunt	=0;
		$LN_InteretEmprunt		=0;
		$LN_CapitalrestantDu	=0;
		$LN_MensualiteADI	=0;
	}
	
	$LT_Elements[$i-1]->LN_Mensualite=$LN_MensualiteEmprunt;
	$LT_Elements[$i-1]->LN_CRD=$LN_CapitalRestantDu;
	$LT_Elements[$i-1]->LN_InteretEmprunt=$LN_InteretEmprunt;
	$LT_Elements[$i-1]->LN_MensualiteADI=$LN_MensualiteADI;
	
	//Echo "Mensualité : ".$LT_Elements[$i-1]->LN_Mensualite."<br>";
	//echo "Capital restant du :".$LT_Elements[$i-1]->LN_CRD."<br>";
	//echo "Interets :".$LT_Elements[$i-1]->LN_InteretEmprunt."<br>";
}


function Moteur_Loyers ($i) {
	Global $LO_XML, $LT_Elements ;
		
		$LN_Loyers = 0;
		$LN_LoyerLu = 0;
		$LN_LoyersLMNP = 0;
		$LN_IRL = 0;
		$LN_NbLoyers = TOOL_DecodeNum($LO_XML->simulation->charges->nbloyers);
		$LN_LoyersImposable = 0;
		$LN_DispFiscalDeduc=0;
		
		if (IsSet($LO_XML->simulation->charges->evolloyers)){
			$LN_IRL = TOOL_DecodeNum($LO_XML->simulation->charges->evolloyers)/100;
		}
		
		for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
			$LN_DebutLoyer = TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->loyerdecale);
			$LC_LoyersSaisonniers = $LO_XML->simulation->descriptifs->descriptif[$j]->loyerssaisonniers;
			$LC_DispFiscal = $LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaleligible;
			$LC_SiLMNPLu = $LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscallmnp;
						
			if($i-1>=$LN_DebutLoyer) {
				$LN_LoyerLu = 0;
				$LN_LoyImpo=0;

				if ($LC_LoyersSaisonniers=="true") {
					$LN_LoyerLu = TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->loyerssaison[$LT_Elements[$i-1]->LN_Mois]);
				}
				
				else {
					$LN_LoyerLu = TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->valeurlocative);
				}
				
				if ($LC_DispFiscal=="true") {
					$LN_DispFiscalDuree =TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscalduree);
					if ($i-1<=$LN_DebutLoyer+$LN_DispFiscalDuree*12) {
						$LN_DispFiscalDeduc =TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaldeduc)/100;
						$LN_LoyImpo=$LN_LoyerLu*(1-$LN_DispFiscalDeduc);
					}
					else {
						$LN_LoyImpo=$LN_LoyerLu;
					}
				}
					
				else {
					$LN_LoyImpo=$LN_LoyerLu;
				}
				$LN_LoyersImposable+=$LN_LoyImpo;
				
//echo $j."Loyers lu = ".$LN_LoyerLu." Loyers imposable = ".$LN_LoyersImposable."<br>";			
					
				If ($LC_SiLMNPLu=="true") {
					$LN_LoyersLMNP = $LN_LoyersLMNP+($LN_LoyerLu)*pow(1+$LN_IRL,$LT_Elements[$i-1]->LN_Annee-SubStr($LO_XML->simulation->datedebut, 6, 4));
				}
							
			$LN_Loyers = $LN_Loyers+($LN_LoyerLu)*pow(1+$LN_IRL,$LT_Elements[$i-1]->LN_Annee-SubStr($LO_XML->simulation->datedebut, 6, 4));
			}
		}
		
		
		
		$LN_LoyersPercu = round($LN_Loyers*$LN_NbLoyers/12,2);
		$LN_LoyersPercuLMNP = round($LN_LoyersLMNP*$LN_NbLoyers/12,2);
		$LN_LoyersImposable=round($LN_LoyersImposable*$LN_NbLoyers/12,2);
		
				
		$LT_Elements[$i-1]->LN_Loyer = $LN_Loyers;
		$LT_Elements[$i-1]->LN_LoyersPercu = $LN_LoyersPercu;
		$LT_Elements[$i-1]->LN_LoyersPercuLMNP = $LN_LoyersPercuLMNP;
		$LT_Elements[$i-1]->LN_LoyersImposable = $LN_LoyersImposable;
				
		//Echo "Loyer : ".$LN_Loyers." Loyer percu : ".$LN_LoyersPercu." Loyer percu LMNP : ".$LN_LoyersPercuLMNP." Loyer imposable : ".	$LN_LoyersImposable."<br>";	
}

function Moteur_LoyersImposable ($i) {
	Global $LO_XML, $LT_Elements;
	
}


function Moteur_TravauxAmortissements ($i) {
Global $LO_XML, $LT_Elements ;
	
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	
	$LN_DureeAmortiImmo		= 1;
	$LN_DureeAmortiMeubles 	= 1;
	
	if ($LC_TypeFiscalite == "indiv") {
			
			$LN_DureeAmortiImmo		= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->amortimm);
			$LN_DureeAmortiMeubles 	= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->amortmob);
	}
	
	
	if ($LC_TypeFiscalite == "sci") {
		$LN_DureeAmortiImmo 	= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->amortimm);
		$LN_DureeAmortiMeubles 	= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->amortmob);
			
	}
	
	$LN_PrixAchat = TOOL_DecodeNum($LO_XML->simulation->frais->prixachat);
	
	$LN_TravauxRenovDeduc 		= 0;
	$LN_TravauxRenovNonDeduc 	= 0;
	
	$LN_AmortiBati = 0;
	$LN_AmortiMeubles = 0;
	$LN_DebutPremLoyer=100;
		
	for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
		$LN_DebutPremLoyerLu = TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->loyerdecale);
		if ($LN_DebutPremLoyerLu<$LN_DebutPremLoyer) {$LN_DebutPremLoyer=$LN_DebutPremLoyerLu;}
	}
				
	if (($i-1>=$LN_DebutPremLoyer) && ($i-1<=$LN_DebutPremLoyer+($LN_DureeAmortiImmo*12))) {
			$LN_AmortiBati = round($LN_PrixAchat*2/3/($LN_DureeAmortiImmo*12),2);
			// Proposer par la suite la part du terrain et la part du bâti, consideree ici 2/3
		}
	
	$LC_IntervalDelaisTravaux = $LO_XML->simulation->travaux->delai;
	
	for ($j=0; $j<count($LO_XML->simulation->travaux->detail);$j++){
		$LN_MomentTravaux = TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->delai);
		if ($LC_IntervalDelaisTravaux=="A") {$LN_MomentTravaux=$LN_MomentTravaux*12;}
						
		if ($LN_MomentTravaux==$i-1) {
			$LN_TravauxRenovDeducLu = 	TOOL_DecodeNum( $LO_XML->simulation->travaux->detail[$j]->montantdeductible);
			$LN_TravauxRenovNonDeducLu = 	 TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->montantnondeductible);
			$LN_TravauxRenovDeduc += 	$LN_TravauxRenovDeduc ;
			$LN_TravauxRenovNonDeduc += 	$LN_TravauxRenovNonDeduc ;
			
			$LN_MeubleLu = TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->meuble);
		}
		$LN_TravauxRenovDeducLuAAmortir = 	 TOOL_DecodeNum( $LO_XML->simulation->travaux->detail[$j]->montantdeductible);
		$LN_TravauxRenovNonDeducLuAAmortir = TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->montantnondeductible);
		$LN_MeubleLuAAmortir = 				 TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->meuble);
		
		$LN_DebutAmortissement =0;
		
		if ($LN_MomentTravaux>$LN_DebutPremLoyer) {$LN_DebutAmortissement=$LN_MomentTravaux;}
		if ($LN_MomentTravaux<$LN_DebutPremLoyer) {$LN_DebutAmortissement=$LN_DebutPremLoyer;}
					
		if (($i-1>=$LN_DebutAmortissement) && ($i-1<=$LN_DebutAmortissement+($LN_DureeAmortiImmo *12))) {
				$LN_AmortiBati += round(($LN_TravauxRenovDeducLuAAmortir+$LN_TravauxRenovNonDeducLuAAmortir)/($LN_DureeAmortiImmo*12),2);
		}
		
		if (($i-1>=$LN_DebutAmortissement) && ($i-1<=$LN_DebutAmortissement+($LN_DureeAmortiMeubles *12))) {
				$LN_AmortiMeubles += round($LN_MeubleLuAAmortir/($LN_DureeAmortiMeubles*12),2);
		}
	}
	
		$LT_Elements[$i-1]->LN_AmortiBati = $LN_AmortiBati;
		$LT_Elements[$i-1]->LN_AmortiMeubles = $LN_AmortiMeubles;
		$LT_Elements[$i-1]->LN_TravauxRenovDeduc 	= $LN_TravauxRenovDeduc;
		$LT_Elements[$i-1]->LN_TravauxRenovNonDeduc = $LN_TravauxRenovNonDeduc;
		
		//echo 'Amorti immo : '.$LN_AmortiBati."  Amorti meubles : ".$LN_AmortiMeubles."<br>";
}

function Moteur_ChargesIndividuelles ($i, $LN_SurfaceTotale, $LN_SurfLMNP, $LN_NbLogementsNu) {
	Global $LO_XML, $LT_Elements ;
	
	$LN_ChargesIndividuel =0;
		//if ($LC_TypeFiscalite == "indiv") {
						
			if ($LT_Elements[$i-1]->LN_Mois==12) {	$LN_ForfaitGestion = 20*$LN_NbLogementsNu;}
			if ($LT_Elements[$i-1]->LN_Mois<12) {	$LN_ForfaitGestion = 0;}
			
			$k=$i-2;
			if ($k<0) {
				$k=0;
				$LT_Elements[$k]->LN_ChargesIndividuel=0;
			}
			
			$LN_ReliquasChargesIndiv = ($LT_Elements[$k]->LN_ChargesIndividuel)
										- ($LT_Elements[$k]->LN_LoyersPercu 
										- $LT_Elements[$k]->LN_LoyersPercuLMNP);
			
										
			if ($LN_ReliquasChargesIndiv<0) {$LN_ReliquasChargesIndiv=0;}
			
			$LN_ChargesIndividuel = 0;
			if ($LN_SurfaceTotale != 0)
			{
				$LN_ChargesIndividuel = $LT_Elements[$i-1]->LN_ChargesDeductiblesCommunes
										*($LN_SurfaceTotale-$LN_SurfLMNP)
										/$LN_SurfaceTotale
										+$LT_Elements[$i-1]->LN_TravauxRenovDeduc
										+$LN_ForfaitGestion
										+$LN_ReliquasChargesIndiv;
			}
								//echo "reliqua ch Indiv :".$LN_ReliquasChargesIndiv."<br>";
		//}
		$LT_Elements[$i-1]->LN_ChargesIndividuel=round($LN_ChargesIndividuel,2);
		//echo "Charges individuel : ".$LT_Elements[$i-1]->LN_ChargesIndividuel."&emsp;&emsp;";
}

function Moteur_ChargesLMNP($i, $LN_SurfLMNP, $LN_SurfaceTotale, $LN_SiLMNP) {
Global $LO_XML, $LT_Elements ;
	$LN_ChargesLMNP=0;
	if ($LN_SiLMNP==1) {
		if ($i==1) {
			$LN_FraisNotaire = TOOL_DecodeNum($LO_XML->simulation->frais->fraisnotaire);
			$LN_FraisDossier = TOOL_DecodeNum($LO_XML->simulation->frais->fraisdivers);
			$LN_ChargesLMNP += $LN_FraisNotaire + $LN_FraisDossier ;
		}
	
		$k=$i-2;
		if ($k<0) {
			$k=0;
			$LT_Elements[$k]->LN_ChargesLMNP=0;
		}
	
		$LN_ReliquasChargesLMNP = ($LT_Elements[$k]->LN_ChargesLMNP) - $LT_Elements[$k]->LN_LoyersPercuLMNP ;
							
		if ($LN_ReliquasChargesLMNP<0) {$LN_ReliquasChargesLMNP=0;}
	
		$LN_ChargesLMNP += $LT_Elements[$i-1]->LN_ChargesDeductiblesCommunes	*$LN_SurfLMNP/$LN_SurfaceTotale
							+$LT_Elements[$i-1]->LN_AmortiBati					*$LN_SurfLMNP/$LN_SurfaceTotale
							+$LT_Elements[$i-1]->LN_AmortiMeubles
							+$LT_Elements[$i-1]->LN_AutresCharges				*$LN_SurfLMNP/$LN_SurfaceTotale
							+$LN_ReliquasChargesLMNP;
							//echo "reliqua ch LM?NP :".$LN_ReliquasChargesLMNP."<br>";
	}
	$LT_Elements[$i-1]->LN_ChargesLMNP=round($LN_ChargesLMNP,2);
	//echo "Charges LMNP : ".$LT_Elements[$i-1]->LN_ChargesLMNP."&emsp;&emsp;";
}

function Moteur_ChargesSCI ($i) {
	Global $LO_XML, $LT_Elements ;
	$LN_ChargesSCI=0;
	if ($i==1) {
			$LN_FraisNotaire = TOOL_DecodeNum($LO_XML->simulation->frais->fraisnotaire);
			$LN_FraisDossier = TOOL_DecodeNum($LO_XML->simulation->frais->fraisdivers);
			$LN_ChargesSCI += $LN_FraisNotaire + $LN_FraisDossier ;
	}
	
	$k=$i-2;
		if ($k<0) {
			$k=0;
			$LT_Elements[$k]->LN_ChargesSCI=0;
		}

	$LN_ReliquasChargesSCI = ($LT_Elements[$k]->LN_ChargesSCI) - $LT_Elements[$k]->LN_LoyersPercu ;

	if ($LN_ReliquasChargesSCI<0) {$LN_ReliquasChargesSCI=0;}

	$LN_ChargesSCI += $LT_Elements[$i-1]->LN_ChargesDeductiblesCommunes
					+$LT_Elements[$i-1]->LN_AmortiBati
					+$LT_Elements[$i-1]->LN_AmortiMeubles
					+$LT_Elements[$i-1]->LN_AutresCharges
					+$LN_ReliquasChargesSCI;
					//echo "reliqua ch SCI :".$LN_ReliquasChargesSCI."<br>";

	$LT_Elements[$i-1]->LN_ChargesSCI=round($LN_ChargesSCI,2);
	//echo "Charges SCI :".$LT_Elements[$i-1]->LN_ChargesSCI."<br>";
}


function Moteur_MontantsImposables ($i, $LN_SiLMNP) {
	Global $LO_XML, $LT_Elements ;
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	$LN_MontantImposableSCI = 0;
	$LN_MontantImposableIndiv = 0;
	$LN_MontantImposableLMNP = 0;
		
	If ($LC_TypeFiscalite == "sci") {
		$LN_MontantImposableSCI = 	  $LT_Elements[$i-1]->LN_LoyersImposable
									- $LT_Elements[$i-1]->LN_ChargesSCI;							
																							
	}
	
	If (($LC_TypeFiscalite == "indiv") && ($LN_SiLMNP=="0")) {$LN_MontantImposableIndiv = $LT_Elements[$i-1]->LN_LoyersImposable
																						- $LT_Elements[$i-1]->LN_ChargesIndividuel;}

	If (($LC_TypeFiscalite == "indiv") && ($LN_SiLMNP=="1")) {$LN_MontantImposableIndiv = ($LT_Elements[$i-1]->LN_LoyersImposable
																						- $LT_Elements[$i-1]->LN_LoyersPercuLMNP)
																						- $LT_Elements[$i-1]->LN_ChargesIndividuel;
																						
															  $LN_MontantImposableLMNP = $LT_Elements[$i-1]->LN_LoyersPercuLMNP
																						- $LT_Elements[$i-1]->LN_ChargesLMNP;}
	
	if ($LN_MontantImposableIndiv < 0) {$LN_MontantImposableIndiv =0;}
	if ($LN_MontantImposableLMNP < 0) {$LN_MontantImposableLMNP =0;}
	if ($LN_MontantImposableSCI < 0) {$LN_MontantImposableSCI =0;}
	
	$LT_Elements[$i-1]->LN_MontantImposableIndiv	=round($LN_MontantImposableIndiv,2);
	$LT_Elements[$i-1]->LN_MontantImposableLMNP		=round($LN_MontantImposableLMNP,2);
	$LT_Elements[$i-1]->LN_MontantImposableSCI		=round($LN_MontantImposableSCI,2);
	
	//echo "Montant imposable SCI:".$LT_Elements[$i-1]->LN_MontantImposableSCI."&emsp; &emsp;".
	//	 "Montant imposable Indiv:".$LT_Elements[$i-1]->LN_MontantImposableIndiv."&emsp; &emsp;".
	//	 "Montant imposable LMNP:".$LT_Elements[$i-1]->LN_MontantImposableLMNP."<br>";
	
}

function Moteur_RegimeImpositionIndividuel ($i,$LN_SiLMNP) {
Global $LO_XML, $LT_Elements;

	$LC_RegimeFiscIndiv = "NC";
	$LN_MicroFoncPlafond = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->mfplafond);
	$LN_MicroFoncDeduc = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->mfabattement)/100;

	if (($LT_Elements[$i-1]->LN_LoyersImposable- $LT_Elements[$i-1]->LN_LoyersPercuLMNP)<$LN_MicroFoncPlafond/12){
		if ((($LT_Elements[$i-1]->LN_LoyersImposable - $LT_Elements[$i-1]->LN_LoyersPercuLMNP)*(1-$LN_MicroFoncDeduc))<($LT_Elements[$i-1]->LN_MontantImposableIndiv)){
			//echo "micro foncier ";
			$LC_RegimeFiscIndiv = "MicroFoncier";
		}
		else {
			//echo "regime reel"."<br>";
			$LC_RegimeFiscIndiv = "Reel";
		}
	}
	else {
		//echo "regime reel"."<br>";
		$LC_RegimeFiscIndiv = "Reel";
	}
	$LT_Elements[$i-1]->LC_RegimeFiscIndiv = $LC_RegimeFiscIndiv;
	$LC_RegimeFiscLMNP ="NC";
	if ($LN_SiLMNP==1) {
		$LN_LMNPPlafond = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->lmnplafond);
		$LN_LMNPDeduc = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->lmnpabattement);
		if ($LN_LMNPPlafond/12>=$LT_Elements[$i-1]->LN_LoyersPercuLMNP) {
			if ((($LT_Elements[$i-1]->LN_LoyersPercuLMNP)*(1-$LN_LMNPDeduc))<($LT_Elements[$i-1]->LN_MontantImposableLMNP)){
				//echo "micro BIC"."<br>";
				$LC_RegimeFiscLMNP = "MicroBIC";
			}
			else{
				//echo "regime reel LMNP"."<br>";
				$LC_RegimeFiscLMNP = "ReelLMNP";
			}
		}
		else {
			//echo "regime reel LMNP"."<br>";
			$LC_RegimeFiscLMNP = "ReelLMNP";
		}
	} 
	$LT_Elements[$i-1]->LC_RegimeFiscLMNP = $LC_RegimeFiscLMNP;
}


function Moteur_MontantImposableIndividuel($i) {
Global $LO_XML, $LT_Elements;

	$LN_TotalImposable = $LT_Elements[$i-1]->LN_AutresRevenus;//$LN_TotalImposable = $LT_Elements[$i-1]->LN_AutresRevenus;
					
	if (($LT_Elements[$i-1]->LC_RegimeFiscIndiv=="Reel") && ($LT_Elements[$i-1]->LN_MontantImposableIndiv>0)) {
		$LN_TotalImposable +=  $LT_Elements[$i-1]->LN_MontantImposableIndiv;
		}
	
	if ($LT_Elements[$i-1]->LC_RegimeFiscIndiv=="MicroFoncier") {
		$LN_TotalImposable +=  (($LT_Elements[$i-1]->LN_LoyersImposable - $LT_Elements[$i-1]->LN_LoyersPercuLMNP)*(1-$LT_Elements[$i-1]->LN_MicroFoncDeduc));
	}
	
	if (($LT_Elements[$i-1]->LC_RegimeFiscLMNP == "ReelLMNP")  && ($LT_Elements[$i-1]->LN_MontantImposableLMNP>0)) {
		$LN_TotalImposable += $LT_Elements[$i-1]->LN_MontantImposableLMNP;
	}
	
	if ($LT_Elements[$i-1]->LC_RegimeFiscLMNP == "MicroBIC") {
		$LN_TotalImposable +=  ($LT_Elements[$i-1]->LN_LoyersPercuLMNP*(1-$LLT_Elements[$i-1]->LN_LMNPDeduc));
	}
		
	$LN_TotalImposable = round($LN_TotalImposable,2);
	$LT_Elements[$i-1]->LN_TotalImposable=$LN_TotalImposable;
			
	//echo "Total imposable :".$LN_TotalImposable."<br>";
}


function Moteur_SelectionTrancheImpotsIndividuel($i) {
	Global $LO_XML, $LT_Elements;
	$LN_TxImpots = 0;
	$LN_NbPartsFiscales = 0;
		
	$LN_TrancheImpots1 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche1)/12;
	$LN_TrancheImpots2 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche2)/12;
	$LN_TrancheImpots3 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche3)/12;
	$LN_TrancheImpots4 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche4)/12;

	$LN_TauxImpots1 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux1);
	$LN_TauxImpots2 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux2);
	$LN_TauxImpots3 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux3);
	$LN_TauxImpots4 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux4);

	$LN_AnneeDebut = SubStr($LO_XML->simulation->datedebut, 6, 4);
	//echo "annee debut : ".$LN_AnneeDebut;
	if ($i==1) { // au premier tour de $i :
		$LN_NbPartsFiscales = 0;
		if(IsSet($LO_XML->investisseur->fiscalite->partsfiscales))
		{
			$LN_NbPartsFiscales = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[0]->parts);
		}
	}

	// a partir de la 2ème ligne : j=1
	 else {
		if(IsSet($LO_XML->investisseur->fiscalite->partsfiscales))
		{
			for ($j=0; $j<count ($LO_XML->investisseur->fiscalite->partsfiscales->children()); $j++){
				$LN_NbPartsFiscalesLu = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->parts);
				$LN_DelaiPartsFiscalesLu = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->delai);
										
				if ($LN_NbPartsFiscalesLu==0) {break;}
			
					
				if ($LT_Elements[$i-1]->LN_Annee == ($LN_DelaiPartsFiscalesLu+$LN_AnneeDebut)) {
					$LN_NbPartsFiscales =$LN_NbPartsFiscalesLu;
					break;
				}
				if  ($LT_Elements[$i-1]->LN_Annee <> ($LN_DelaiPartsFiscalesLu+$LN_AnneeDebut)){
					$LN_NbPartsFiscales = $LT_Elements[$i-2]->LN_NbPartsFiscales;
				}				
			}
		}
	}
	//echo "parts fiscales : ". $LN_NbPartsFiscales."<br>";
	$LT_Elements[$i-1]->LN_NbPartsFiscales = $LN_NbPartsFiscales;
		
	if ($LN_NbPartsFiscales != 0)
	{
		if (($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) < $LN_TrancheImpots2)
		{
			$LN_TxImpots = $LN_TauxImpots1;			
		}
		
		if ((($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) < $LN_TrancheImpots3) && 
			(($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) > $LN_TrancheImpots2)) 
		{
			$LN_TxImpots =$LN_TauxImpots2;			
		}
		
		if ((($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) < $LN_TrancheImpots4) && 
			(($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) > $LN_TrancheImpots3))
		{
			$LN_TxImpots =$LN_TauxImpots3;				
		}
			
		if (($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) >= $LN_TrancheImpots4) 
		{
				$LN_TxImpots =$LN_TauxImpots4;
		}
	}
	$LT_Elements[$i-1]->LN_TxImpots = $LN_TxImpots;
	//echo "TAUX impots : ".$LN_TxImpots."<br>";
}

function Moteur_MontantImpotsCRSDIndividuel ($i) {
Global $LO_XML, $LT_Elements;
	
		$LN_TxCSG = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->cotisations);
		$LN_MontantImpots =0;
		$LN_MontantCSG = 0;

		if (($LT_Elements[$i-1]->LN_TotalImposable)-($LT_Elements[$i-1]->LN_AutresRevenus)>0) {
			$LN_MontantImpots = round(($LT_Elements[$i-1]->LN_TotalImposable-$LT_Elements[$i-1]->LN_AutresRevenus) * $LT_Elements[$i-1]->LN_TxImpots/100,2);
			$LN_MontantCSG = round(($LT_Elements[$i-1]->LN_TotalImposable-$LT_Elements[$i-1]->LN_AutresRevenus) * $LN_TxCSG/100,2);
		}
		$LT_Elements[$i-1]->LN_MontantImpots = $LN_MontantImpots;
		$LT_Elements[$i-1]->LN_MontantCSG = $LN_MontantCSG;
		
		
		//echo "Montant impots : ".$LN_MontantImpots." Montant CSG : ".$LN_MontantCSG."<br>";
}

function Moteur_TresorerieIndividuelle($i){
Global $LO_XML, $LT_Elements;
	
	$LN_Tresorerie = 	 $LT_Elements[$i-1]->LN_LoyersPercu
						-$LT_Elements[$i-1]->LN_Mensualite
						-$LT_Elements[$i-1]->LN_ChargesDeductiblesCommunes
						+$LT_Elements[$i-1]->LN_InteretEmprunt
						-$LT_Elements[$i-1]->LN_AutresCharges
						-$LT_Elements[$i-1]->LN_MontantImpots
						-$LT_Elements[$i-1]->LN_MontantCSG;
						
	$LC_PTZExist 	= TOOL_DecodeNum($LO_XML->simulation->credit->ptz);
	$LN_PTZDuree	= TOOL_DecodeNum($LO_XML->simulation->credit->ptzduree);
	$LN_PTZMontant	= TOOL_DecodeNum($LO_XML->simulation->credit->ptzmontant);
	
	if (($LC_PTZExist=="true") && ($i<=$LN_PTZDuree)) {
		$LN_Tresorerie = $LN_Tresorerie - round($LN_PTZMontant/$LN_PTZDuree,2);
		//echo "en EcoPTZ, ".round($LN_PTZMontant/$LN_PTZDuree,2)."<br>";
		}
			
	$LT_Elements[$i-1]->LN_Tresorerie = $LN_Tresorerie;	
	
	if ($i==1) {
		$LT_Elements[$i-1]->LN_TresorerieCumul = $LT_Elements[$i-1]->LN_Tresorerie;
	}
	
	else {
		if ($LT_Elements[$i-1]->LN_CRD>0) {
			$LT_Elements[$i-1]->LN_TresorerieCumul = $LT_Elements[$i-2]->LN_TresorerieCumul+$LT_Elements[$i-1]->LN_Tresorerie;
		}
	}
	
	//echo'<font color = "red">TRESORERIE : '.$LT_Elements[$i-1]->LN_Tresorerie."</font><br><br>";
}

function Moteur_RemboursementAnticipeIndividuel ($i) {
Global $LO_XML, $LT_Elements;
	$LN_Duree =1200;
	
	if (($LT_Elements[$i-1]->LN_TresorerieCumul>0) && ($LT_Elements[$i-1]->LN_CRD>0))
	{
		$LN_TauxEmprunt =  TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal);
		$LN_CRDCourant = $LT_Elements[$i-1]->LN_CRD - $LT_Elements[$i-1]->LN_TresorerieCumul;
		
		for ($j=0;$j<=1200;$j++)
			{
				$LN_CapMensu=$LT_Elements[$i-1]->LN_Mensualite-($LN_CRDCourant*$LN_TauxEmprunt/1200);
				$LN_CRDCourant=$LN_CRDCourant-$LN_CapMensu;
				if ($LN_CRDCourant<=0 )
					{
						$LN_Duree =$j;
						break;
					}	
			}
			//echo "condition remvbours anticipé OK    Durée = ".$LN_Duree."<br>";
	}
	$LT_Elements[$i-1]->LN_DureeRestanteAnticipeIndiv = $LN_Duree;
}


function Moteur_ImpotsSCI($i) {
	Global $LO_XML, $LT_Elements;
	
	$LN_TrancheImpotSCI2 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItranche2);
	$LN_TxImpotSCI = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItaux1)/100;
	
	$LT_Elements[$i-1]->LN_MontantImposableSCI = 0;
		if ($LT_Elements[$i-1]->LN_MontantImposableSCI>0) {
			if ($LT_Elements[$i-1]->LN_MontantImposableSCI>$LN_TrancheImpotSCI2/12) {
				$LN_TxImpotSCI = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItaux2)/100;
			}
		}
	
	$LN_ImpotsSCI = $LT_Elements[$i-1]->LN_MontantImposableSCI*$LN_TxImpotSCI;
	
	$LT_Elements[$i-1]->LN_ImpotsSCI=round($LN_ImpotsSCI,2);
	
	//echo "impots SCI : ".$LT_Elements[$i-1]->LN_ImpotsSCI."<br>";
}

function Moteur_TresorerieSCI ($i) {
	Global $LO_XML, $LT_Elements;
	
	$LN_TresorerieSCI = 	 $LT_Elements[$i-1]->LN_LoyersPercu
						-$LT_Elements[$i-1]->LN_Mensualite
						-$LT_Elements[$i-1]->LN_ChargesDeductiblesCommunes
						+$LT_Elements[$i-1]->LN_InteretEmprunt
						-$LT_Elements[$i-1]->LN_AutresCharges
						-$LT_Elements[$i-1]->LN_ImpotsSCI;
						
	$LC_PTZExist 	= TOOL_DecodeNum($LO_XML->simulation->credit->ptz);
	$LN_PTZDuree	= TOOL_DecodeNum($LO_XML->simulation->credit->ptzduree);
	$LN_PTZMontant	= TOOL_DecodeNum($LO_XML->simulation->credit->ptzmontant);
	
	if (($LC_PTZExist=="true") && ($i<=$LN_PTZDuree)) {
		$LN_TresorerieSCI = $LN_Tresorerie - round($LN_PTZMontant/$LN_PTZDuree,2);
		
		}
			
	$LT_Elements[$i-1]->LN_TresorerieSCI = $LN_TresorerieSCI;	
	//echo '<font color="red">Tresorerie SCI : '.$LN_TresorerieSCI."<br></font>";
}

function Moteur_DividendeAssocie ($i) {
	Global $LO_XML, $LT_Elements;
	
	$LN_DividendeVerse = 0;
	
	if ($LT_Elements[$i-1]->LN_ImpotsSCI>0) {
		$LN_PartDividende = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->dividendesverses)/100;
		
		$LN_DividendeVerse = round($LT_Elements[$i-1]->LN_TresorerieSCI*$LN_PartDividende ,2);
	}
	$LT_Elements[$i-1]->LN_DividendeVerse=$LN_DividendeVerse;
	
		//Echo "Dividende verse : ".$LT_Elements[$i-1]->LN_DividendeVerse."<br>";
	$LT_Elements[$i-1]->LN_TresorerieSCIRestante = $LT_Elements[$i-1]->LN_TresorerieSCI-$LN_DividendeVerse;

	if ($i==1) {
		$LT_Elements[$i-1]->LN_TresorerieSCIRestanteCumul = $LT_Elements[$i-1]->LN_TresorerieSCIRestante;
	}
	
	else {
		if ($LT_Elements[$i-1]->LN_CRD>0) {
			$LT_Elements[$i-1]->LN_TresorerieSCIRestanteCumul = $LT_Elements[$i-2]->LN_TresorerieSCIRestanteCumul+$LT_Elements[$i-1]->LN_TresorerieSCIRestante;
		}
	}
	
}


function Moteur_RemboursementAnticipeSCI ($i) {
	Global $LO_XML, $LT_Elements;
	$LN_Duree =1200;
	
	if (($LT_Elements[$i-1]->LN_TresorerieSCIRestanteCumul>0) && ($LT_Elements[$i-1]->LN_CRD>0))
	{
		$LN_TauxEmprunt =  TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal);
		$LN_CRDCourant = $LT_Elements[$i-1]->LN_CRD - $LT_Elements[$i-1]->LN_TresorerieSCIRestanteCumul;
		
		for ($j=0;$j<=1200;$j++)
		{
			$LN_CapMensu=$LT_Elements[$i-1]->LN_Mensualite-($LN_CRDCourant*$LN_TauxEmprunt/1200);
			$LN_CRDCourant=$LN_CRDCourant-$LN_CapMensu;
			if ($LN_CRDCourant<=0 )
			{
				$LN_Duree =$j;
				break;
			}	
		}
			//echo "SCI condition remvbours anticipé OK    Durée = ".$LN_Duree."<br>";
	}
	$LT_Elements[$i-1]->LN_DureeRestanteAnticipeSCI = $LN_Duree;	
}

function Moteur_DateRemboursementAnticipeIndiv ($i) {
	Global $LO_XML, $LT_Elements;
	//$LN_DateDebut  = $LO_XML->simulation->datedebut;
	//$LN_DateDebutTimestamp = strtotime($LN_DateDebut);
	//$LN_DateActuelle =  date('d-m-Y', strtotime('+'.$i-1 . ' month', $LN_DateDebutTimestamp ));
	//$LN_DateActuelleTimestamp = strtotime($LN_DateActuelle);
	//$LN_DateRemAnticipeIndiv =  date('d-m-Y', strtotime('+'. $LT_Elements[$i-1]->LN_DureeRestanteAnticipeIndiv . ' month', $LN_DateActuelleTimestamp ));
	
	$a = $LT_Elements[$i-1]->LN_Annee;
	$m = $LT_Elements[$i-1]->LN_Mois;
		
	TOOL_VieillirDate($m,$a, $LT_Elements[$i-1]->LN_DureeRestanteAnticipeIndiv );
	$LT_Elements[$i-1]->LN_DateRemAnticipeIndiv= TOOL_NomMois($m)." ".$a;
	$LT_Elements[$i-1]->LN_DateRemAnticipeIndivCalculee = $a*12 + $m;
	
	//$LT_Elements[$i-1]->LN_DateRemAnticipeIndiv=$LN_DateRemAnticipeIndiv;
}

function Moteur_DateRemboursementAnticipeSCI ($i) {
	Global $LO_XML, $LT_Elements;
	//$LN_DateDebut  = $LO_XML->simulation->datedebut;
	//$LN_DateDebutTimestamp = strtotime($LN_DateDebut);
	//$LN_DateActuelle =  date('d-m-Y', strtotime('+'.$i-1 . ' month', $LN_DateDebutTimestamp ));
	//$LN_DateActuelleTimestamp = strtotime($LN_DateActuelle);
	//$LN_DateRemAnticipeSCI =  date('d-m-Y', strtotime('+'. $LT_Elements[$i-1]->LN_DureeRestanteAnticipeSCI . ' month', $LN_DateActuelleTimestamp ));
	
	$a = $LT_Elements[$i-1]->LN_Annee;
	$m = $LT_Elements[$i-1]->LN_Mois;
		
	
	TOOL_VieillirDate($m,$a, $LT_Elements[$i-1]->LN_DureeRestanteAnticipeSCI );
	
	$LT_Elements[$i-1]->LN_DateRemAnticipeSCI=TOOL_NomMois($m)." ".$a;
	$LT_Elements[$i-1]->LN_DateRemAnticipeSCICalculee =$a*12 + $m;
}


function Moteur_ImpotsAssocie ($i) {
Global $LO_XML, $LT_Elements;
	$LN_TxImpots = 0;
	$LN_NbPartsFiscales = 0;
		
	$LN_TrancheImpots1 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche1)/12;
	$LN_TrancheImpots2 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche2)/12;
	$LN_TrancheImpots3 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche3)/12;
	$LN_TrancheImpots4 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche4)/12;
		
	$LN_TauxImpots1 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux1)/100;
	$LN_TauxImpots2 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux2)/100;
	$LN_TauxImpots3 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux3)/100;
	$LN_TauxImpots4 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux4)/100;
	
	
	
	$LN_AnneeDebut = SubStr($LO_XML->simulation->datedebut, 6, 4);
	//echo "annee debut : ".$LN_AnneeDebut;
		
		
	if ($i==1) { // au premier tour de $i :
		if (IsSet($LO_XML->investisseur->fiscalite->partsfiscales))
		{
			$LN_NbPartsFiscales = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[0]->parts);
		}
	}
	// a partir de la 2ème ligne : j=1
	 else {
		if(IsSet($LO_XML->investisseur->fiscalite->partsfiscales))
		{
			for ($j=0; $j<count ($LO_XML->investisseur->fiscalite->partsfiscales->children()); $j++){
				$LN_NbPartsFiscalesLu = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->parts);
				$LN_DelaiPartsFiscalesLu = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->delai);
										
				if ($LN_NbPartsFiscalesLu==0) {break;}
			
					
				if ($LT_Elements[$i-1]->LN_Annee == ($LN_DelaiPartsFiscalesLu+$LN_AnneeDebut)) {
					$LN_NbPartsFiscales =$LN_NbPartsFiscalesLu;
					break;
				}
				if  ($LT_Elements[$i-1]->LN_Annee <> ($LN_DelaiPartsFiscalesLu+$LN_AnneeDebut)){
					$LN_NbPartsFiscales = $LT_Elements[$i-2]->LN_NbPartsFiscales;
				}				
			}
		}
	}
	//echo "parts fiscales : ".$LN_NbPartsFiscales."<br>";
	$LT_Elements[$i-1]->LN_NbPartsFiscales = $LN_NbPartsFiscales;
	
	$LN_TotalImposable = $LT_Elements[$i-1]->LN_AutresRevenus + $LT_Elements[$i-1]->LN_DividendeVerse;
	
	$LN_AbatImpotAssocie1 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->dividendesimposes);
	$LN_AbatImpotAssocie2 = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->dividendessuite)/100;
			
	$LN_TotalImposable=$LN_TotalImposable-$LN_AbatImpotAssocie1;
	$LN_TotalImposable=$LN_TotalImposable*(1-$LN_AbatImpotAssocie2);
	
	if ($LN_TotalImposable<0) { $LN_TotalImposable=0;}
	
	if ($LN_NbPartsFiscales != 0)
	{
		if (($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) < $LN_TrancheImpots2)  
		{
			$LN_TxImpots =$LN_TauxImpots1;			
		}			
		if ((($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) < $LN_TrancheImpots3) && 
			(($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) > $LN_TrancheImpots2)) 
		{
			$LN_TxImpots =$LN_TauxImpots2;			
		}
		
		if ((($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) < $LN_TrancheImpots4) && 
			(($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) > $LN_TrancheImpots3)) 
		{
			$LN_TxImpots =$LN_TauxImpots3;				
		}
			
		if (($LT_Elements[$i-1]->LN_TotalImposable/$LN_NbPartsFiscales) >= $LN_TrancheImpots4) 
		{
			$LN_TxImpots =$LN_TauxImpots4;
		}
	}
	
	$LT_Elements[$i-1]->LN_TxImpotsAssocie = $LN_TxImpots;
	
	
	$LN_TxCSG = TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->tauxcotsociale)/100;
	
	$LN_DividendeImposable = $LT_Elements[$i-1]->LN_DividendeVerse-$LN_AbatImpotAssocie1;
	$LN_DividendeImposable =$LN_DividendeImposable *(1-$LN_AbatImpotAssocie2);
			
	$LT_Elements[$i-1]->LN_ImpotsAssocie = round($LT_Elements[$i-1]->LN_TxImpotsAssocie * $LN_DividendeImposable,2);
	
	$LT_Elements[$i-1]->LN_CSGAssocie = round($LT_Elements[$i-1]->LN_DividendeVerse*$LN_TxCSG,2);
	
	//echo "TAUX impots asocie: ".$LN_TxImpots."<br>";
	
	$LT_Elements[$i-1]->LN_DividendeNetAssocie = $LT_Elements[$i-1]->LN_DividendeVerse - $LT_Elements[$i-1]->LN_ImpotsAssocie - $LT_Elements[$i-1]->LN_CSGAssocie;
			
	
	//echo "Impots associe : ".$LT_Elements[$i-1]->LN_ImpotsAssocie." CSG : ".$LT_Elements[$i-1]->LN_CSGAssocie."<br>";
	//echo '<font color="red">DIVIDENDE NET : '.$LT_Elements[$i-1]->LN_DividendeNetAssocie ."<br></font>";
}


function Moteur_ExportTrace()
{
Global $LT_Elements;
	$LN_Fh = fOpen("../Ajax/TraceMoteur_" . TOOL_SessionLire("GET", "id") . ".csv" , "w");
	// ligne d'entete
	$LC_Ligne = "";
	$LC_Ligne .= "Ligne;";
	//$LC_Ligne .= "Annee;";
	$LC_Ligne .= "Mois;";
	$LC_Ligne .= "Annee;";
	$LC_Ligne .= "Loyer;";
	$LC_Ligne .= "LoyerPercu;";
	$LC_Ligne .= "LoyerImposable;";
	$LC_Ligne .= "CapitalRestantDu;";
	$LC_Ligne .= "Interets;";
	$LC_Ligne .= "InteretsAnnuels;";
	$LC_Ligne .= "Mensualite;";
	$LC_Ligne .= "MensualiteAnnuelle;";
	$LC_Ligne .= "AssurancePret;";
	$LC_Ligne .= "AssurantePretAnnuelle;";
	$LC_Ligne .= "AssurancePNO;";
	$LC_Ligne .= "EvolPNO;";
	$LC_Ligne .= "AssurancePNUAnnuelle;";
	$LC_Ligne .= "AssuranceGRL;";
	$LC_Ligne .= "AssuranceGRLAnnuelle;";
	$LC_Ligne .= "AssuranceCopro;";
	$LC_Ligne .= "AssuranceCoproAnnuelle;";
	$LC_Ligne .= "ChargesCopro;";
	$LC_Ligne .= "ChargesCoproAnnuelle;";
	$LC_Ligne .= "ChargesIndividuel;";
	$LC_Ligne .= "AutresCharges;";
	$LC_Ligne .= "AutresChargesAnnuelles;";
	$LC_Ligne .= "AutresRevenus;";	
	$LC_Ligne .= "ChargesSCI;";
	$LC_Ligne .= "FraisAgence;";
	$LC_Ligne .= "FraisAgenceAnnuel;";
	$LC_Ligne .= "TaxeFonciere;";
	$LC_Ligne .= "TaxeFonciereAnnuelle;";
	$LC_Ligne .= "TravauxRenovation;";
	$LC_Ligne .= "TravauxRenovationAnnuel;";
	$LC_Ligne .= "TravauxEntretien;";
	$LC_Ligne .= "TravauxEntretienAnnuel;";
	$LC_Ligne .= "Tresorerie;";
	$LC_Ligne .= "Tresorerie Cumulee;";
	$LC_Ligne .= "DureeRestanteAnticipeIndiv;";
	$LC_Ligne .= "DateRemAnticipeIndiv;";
	$LC_Ligne .= "DateRemAnticipeIndivCalc;";
	
	$LC_Ligne .= "NbPartsFiscales;";
	$LC_Ligne .= "MontantImposableIndiv;";	
	$LC_Ligne .= "Régime fiscal indiv;";
	$LC_Ligne .= "TotalImposable;";
	$LC_Ligne .= "TxImpots;";
	$LC_Ligne .= "MontantImpots;";
	$LC_Ligne .= "MontantCSG;";
	//$LC_Ligne .= "MensualiteEmprunt;";
	$LC_Ligne .= "ChargesDeductiblesCommunes;";
	$LC_Ligne .= "ChargesLMNP;";
	$LC_Ligne .= "AmortiBati;";
	$LC_Ligne .= "AmortiMeubles;";
	$LC_Ligne .= "TravauxRenovDeduc;";
	$LC_Ligne .= "TravauxRenovNonDeduc;";
	$LC_Ligne .= "MontantImposableSCI;";
	$LC_Ligne .= "ImpotsSCI;";
	$LC_Ligne .= "TresorerieSCI;";
	$LC_Ligne .= "DividendeVerse;";
	$LC_Ligne .= "TresorerieSCIRestante;";
	$LC_Ligne .= "TresorerieSCIRestanteCumulee;";
	$LC_Ligne .= "DureeRestanteAnticipeSCI;";
	$LC_Ligne .= "DateRemAnticipeSCI;";
	$LC_Ligne .= "DateRemAnticipeSCICalculee;";
	$LC_Ligne .= "TotalImposable;";
	$LC_Ligne .= "TxImpotsAssocie;";
	$LC_Ligne .= "ImpotsAssocie;";
	$LC_Ligne .= "CSGAssocie;";
	$LC_Ligne .= "DividendeNetAssocie;";
	
	fwrite($LN_Fh, $LC_Ligne . "\n");

	// boucle de donnees
	for ($i=0; $i < Count($LT_Elements); $i++)
	{
		$LC_Ligne = "";
		$LC_Ligne .= Substr($LT_Elements[$i]->LN_Date, 0, Strlen($LT_Elements[$i]->LN_Date) -6)	. ";";
		//$LC_Ligne .= Substr($LT_Elements[$i]->LN_Date, Strlen($LT_Elements[$i]->LN_Date) -6, 4)	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_Mois	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_Annee	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_Loyer	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_LoyersPercu	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_LoyersImposable	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_CRD	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_InteretEmprunt	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_InteretsAnnuels	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_Mensualite	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_MensualiteAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_MensualiteADI	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssurantePretAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssurancePNO	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_EvolPNO	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssurancePNUAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssuranceGRL	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssuranceGRLAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssuranceCopro	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AssuranceCoproAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ChargesCopro	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ChargesCoproAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ChargesIndividuel	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AutresCharges	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AutresChargesAnnuelles	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AutresRevenus 	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ChargesSCI 	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_FraisAgence	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_FraisAgenceAnnuel	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TaxeFonciere	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TaxeFonciereAnnuelle	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TravauxRenovation	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TravauxRenovationAnnuel	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TravauxEntretien	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TravauxEntretienAnnuel	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_Tresorerie	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TresorerieCumul	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DureeRestanteAnticipeIndiv	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DateRemAnticipeIndiv	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DateRemAnticipeIndivCalculee	. ";";
				
		$LC_Ligne .= $LT_Elements[$i]->LN_NbPartsFiscales	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_MontantImposableIndiv		.";";
		$LC_Ligne .= $LT_Elements[$i]->LC_RegimeFiscIndiv	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TotalImposable	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TxImpots	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_MontantImpots	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_MontantCSG	. ";";
		//$LC_Ligne .= $LT_Elements[$i]->LN_MensualiteEmprunt	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ChargesDeductiblesCommunes	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ChargesLMNP	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AmortiBati	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_AmortiMeubles	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TravauxRenovDeduc	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TravauxRenovNonDeduc	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_MontantImposableSCI	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ImpotsSCI	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TresorerieSCI	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DividendeVerse	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TresorerieSCIRestante	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TresorerieSCIRestanteCumul	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DureeRestanteAnticipeSCI	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DateRemAnticipeSCI	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DateRemAnticipeSCICalculee	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TotalImposable	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_TxImpotsAssocie	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_ImpotsAssocie	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_CSGAssocie	. ";";
		$LC_Ligne .= $LT_Elements[$i]->LN_DividendeNetAssocie	. ";";
		
		
		$LC_Ligne  = str_Replace(".", ",", $LC_Ligne);
		fwrite($LN_Fh, $i . $LC_Ligne . "\n");
	}
	fClose($LN_Fh);
}

function MOTEUR_Graphe()
{
Global $LT_Elements;

	$LC_Image       = "../Ajax/RENTABILITE_" . TOOL_SessionLire("GET", "id") . ".jpg";
	$LC_Image       = STR_Replace(".xml", "", $LC_Image);
	$LC_Commentaire = "";
	$LT_ElemGraphe  = array();
	
	// charger les classes	
	for ($i=1; $i<Count($LT_Elements); $i++)
	{
		if (TOOL_DecodeNum($LT_Elements[$i]->LN_TresorerieCumul) != 0)
		{
			$LN_Valeur = TOOL_DecodeNum($LT_Elements[$i]->LN_TresorerieCumul);
			$LC_Date   = SubStr($LT_Elements[$i]->LN_Date, 4, 2) . 
						 "/" .
						 SubStr($LT_Elements[$i]->LN_Date, 0, 4);
			$LT_ElemGraphe[] = new LO_ClasseLigne($LN_Valeur, $LC_Date);
		}
	}
	//TraceGrapheLigne($LT_ElemGraphe, 3500, 2400, $LC_Image, 12, 2, 40);
	//TraceGrapheLigne($LT_ElemGraphe, 1200, 800, $LC_Image, 5, 1, 10);
	TraceGrapheLigne($LT_ElemGraphe, 600, 400, $LC_Image, 2, 1, 8);
	While (file_exists($LC_Image)==false)
	{
		sleep(1);
	}

	
}


function TOOL_VieillirDate(&$PC_Mois, &$PC_Annee, $PC_Vieillir)
{
	$LN_Mois	= IntVal($PC_Mois);
	$LN_Annee	= IntVal($PC_Annee);
	$LN_Vieillir= IntVal($PC_Vieillir);

	$LN_Mois  = $LN_Mois + $LN_Vieillir;
	$LN_TMP   = (int)($LN_Mois / 12); 
	$LN_Annee = $LN_Annee + $LN_TMP;
	$LN_Mois  = $LN_Mois % 12;
	if ($LN_Mois == 0){$LN_Mois = 12;}

	$PC_Mois  = $LN_Mois;
	$PC_Annee = $LN_Annee;
 }
 
 function TOOL_NomMois($LN_Numero)
 {
	 SWITCH ($LN_Numero){
		Case "1":
			$LC_Mois = "Janvier";
			Break;
		
		Case "2":
			$LC_Mois = "Février";
			Break;
			
		Case "3":
			$LC_Mois = "Mars";
			Break;
			
		Case "4":
			$LC_Mois = "Avril";
			Break;
			
		Case "5":
			$LC_Mois = "Mai";
			Break;
			
		Case "6":
			$LC_Mois = "Juin";
			Break;
		
		Case "7":
			$LC_Mois = "Juillet";
			Break;
		
		Case "8":
			$LC_Mois = "Aout";
			Break;
			
		Case "9":
			$LC_Mois = "Septembre";
			Break;
			
		Case "10":
			$LC_Mois = "Octobre";
			Break;
			
		Case "11":
			$LC_Mois = "Novembre";
			Break;
			
		Case "12":
			$LC_Mois = "Décembre";
			Break;
			
	 }
	 
	 return $LC_Mois;
	 
 }
 
?>