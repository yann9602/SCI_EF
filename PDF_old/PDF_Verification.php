<?php
require('FPDF/fpdf.php');
require('PDF_MiseEnPage.php');

// variables globales du module
$LC_Retour = "";
$LO_Presentation = new LO_PageVerifType;
$LO_XML = "";

// test direct
//BLOC_Verification("SIM_00001_00038.xml");

function BLOC_Verification($PC_XML)
{
Global $LC_Retour, $LO_XML;
	$LO_XML = simplexml_load_file($PC_XML);
	$LN_OK = 0;
	if (BLOC_Fiscalite()){$LN_OK +=1;}
	if (BLOC_Travaux()){$LN_OK +=1;}
	if (BLOC_Description()){$LN_OK +=1;}
	if (BLOC_Charges()){$LN_OK +=1;}
	if (BLOC_Financement()){$LN_OK +=1;}
	if ($LN_OK == 5){
		$LC_Retour = "Tout va bien. Vous pouvez demander vos résultats";
		return "OK" . $LC_Retour;
	}
	else
	{
		return "KO" . $LC_Retour;
	}
}

Function BLOC_Fiscalite() {
Global $LC_Retour, $LO_XML;
	
	$LC_Texte = "";
	$LB_Result= true;
	
	if (TOOL_DecodeNum($LO_XML->investisseur->ressources->salaire) + TOOL_DecodeNum($LO_XML->investisseur->ressources->foncier) < "100") 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet investisseur :".
				"Les revenus de l'investisseur (salaire + fonciers) doivent être valide (supérieur à 100)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
		$LB_Result= false;
	}
	
	if ($LO_XML->investisseur->fiscalite->typefiscalite == "") 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez définir le type de fiscalité (SCi ou Individuel) " .
				"Indiquez la régime fiscal en cochant la case correspondante dans l'onglet Fiscalité.";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[0]->parts) == 0) 
	{
		$LC_Texte = 	"Dans l'onglet fiscalité :".
				"le nombre de parts fiscales ne peut pas être inférieur à 1.";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
		$LB_Result= false;
	}				
	
	if(IsSet($LO_XML->investisseur->fiscalite->partsfiscales)==false)
	{
		$LC_Texte = "Dans l'onglet fiscalité : ".
					"Les parts fiscales ont été mal définies.";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
		$LB_Result= false;
		return $LB_Result;
	}
	for ($j=1; $j<count($LO_XML->investisseur->fiscalite->partsfiscales->children());$j++){
		if ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->parts) > 0) and
		    (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->delai) < 1)) 
		{
			$LC_Texte = 	"Dans l'onglet fiscalité : ".
					"Le délai d'évolution des parts fiscales ne peut pas être inférieur à 1 an.";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
			$LB_Result= false;
			break;
		}

		if ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->delai) > 0) and
		    (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->partsfiscales->periode[$j]->parts) < 1)) 
		{
			$LC_Texte = 	"Dans l'onglet fiscalité : ".
					"Le nombre de parts fiscales ne peut pas être inférieur à 1.";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
			$LB_Result= false;
			break;
		}
	}
	
	if ($LO_XML->investisseur->fiscalite->typefiscalite == "indiv") 
	{
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->taxefonciere) < 1)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer un montant de taxe foncière valide (supérieur à 1)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
			$LB_Result= false;
		}
		
		if ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche2) > 0) and
		   ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche1) >= 
		     TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche2)))) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"La deuxième tranche d'imposition doit être supérieure à la première" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
 			$LB_Result= false;
		}
		
		if ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche3) > 0) and
		   ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche2) >= 
		     TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche3)))) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"La troisième tranche d'imposition doit être supérieure à la deuxième" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche4) > 0) and
		   ((TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche3) >= 
		     TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche4)))) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"La quatrième tranche d'imposition doit être supérieure à la troisième" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux1) >= 
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux2))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition de la deuxième tranche d'imposition doit être supérieure au taux de la la première tranche" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux2) >= 
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux3))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition de la troisième tranche d'imposition doit être supérieure au taux de la la deuxièmetranche" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux3) >= 
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux4))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition de la troisième tranche d'imposition doit être supérieure au taux de la la quatrième tranche." ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->cotisations) < 1)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer un taux de cotisations sociales valide (supérieur à 1)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->amortimm) < 5)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer une durée d'amortissement des immeubles valide (supérieur à 5)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);	
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->amortmob) < 1)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer une durée d'amortissement des meubles valide (supérieur à 1)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
	}
	
	if ($LO_XML->investisseur->fiscalite->typefiscalite == "sci") 
	{
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->taxefonciere) < 1)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer un montant de taxe foncière valide (supérieur à 1)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItranche2) >= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItranche1))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"La deuxième tranche d'imposition aux sociétés doit être supérieure à la première" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItaux2) >= TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TISCItaux1))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition aux sociétés de la deuxième tranche d'imposition doit être supérieure à celui de la première" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->amortimm) < 5)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer une durée d'amortissement des immeubles valide (supérieur à 5)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->amortmob) < 1)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer une durée d'amortissement des meubles valide (supérieur à 1)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->dividendesverses) < 1)
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Vous devez indiquer % de trésorerie reversé à l'associé valide (supérieur à 1)" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche1) >=
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche2))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité : ".
				"La deuxième tranche d'imposition doit être supérieure à la première" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche2) >=
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche3) )
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"La troisième tranche d'imposition doit être supérieure à la deuxième" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche3) >= 
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStranche4))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"La quatrième tranche d'imposition doit être supérieure à la troisième" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux1) >=
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux2))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition de la deuxième tranche d'imposition doit être supérieure au taux de la la première tranche" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux2) >= 
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux3))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition de la troisième tranche d'imposition doit être supérieure au taux de la la deuxièmetranche" ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux3) >=
		    TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->TIASStaux4))
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet fiscalité :".
				"Le taux d'imposition de la troisième tranche d'imposition doit être supérieure au taux de la la quatrième tranche." ;
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
	
	}
	return $LB_Result;
}

Function BLOC_Travaux() {
Global $LC_Retour, $LO_XML;
	
	$LC_Texte = "";
	$LB_Result= true;
	
	if(IsSet($LO_XML->simulation->travaux->detail)==false)
	{
		$LC_Texte = "Dans l'onglet travaux : ".
					"Les lignes ont été mal définies.";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
		$LB_Result= false;
		return $LB_Result;
	}
	for ($j=0; $j<count($LO_XML->simulation->travaux->detail->children());$j++){
	
		if (TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->delai) < 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet travaux, pour " . $LO_XML->simulation->travaux->detail[$j]->libelle . " : ".
					"Les travaux ne peuvent être réalisés avant l'acquisition (le délais doit être supérieur ou égal à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
	
		if (TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->montantdeductible) < 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet travaux, pour " . $LO_XML->simulation->travaux->detail[$j]->libelle . " : ".
					"Le montant des travaux déductibles doit être valide (supérieur ou égal à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
		
		if (TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->montantnondeductible) < 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet travaux, pour " . $LO_XML->simulation->travaux->detail[$j]->libelle . " : ".
					"Le montant des travaux non déductibles doit être valide (supérieur ou égal à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
		
		if (TOOL_DecodeNum($LO_XML->simulation->travaux->detail[$j]->meuble) < 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet travaux, pour " . $LO_XML->simulation->travaux->detail[$j]->libelle . " : ".
					"Le prix d'acquisition des meubles doit être valide (supérieur ou égal à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
	}
	return $LB_Result;
}

Function BLOC_Description() {
Global $LC_Retour, $LO_XML;
	
	$LC_Texte = "";
	$LB_Result= true;
	if(IsSet($LO_XML->simulation->descriptifs) == false)
	{
		$LC_Texte = "Dans l'onglet descriptif il doit y avoir au moins 1 bien d'indiqué";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
		$LB_Result= false;
		return;
	}	
	for ($j=0; $j<count($LO_XML->simulation->descriptifs->children());$j++){
	
		if (TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->surface) <= 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet descriptif, pour " . $LO_XML->simulation->descriptifs->descriptif[$j]->libelle . " : ".
					"La surface doit être valide (supérieur à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";
			$LB_Result= false;
			break;
		}
		
		if (TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->prixmetre) <= 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet descriptif, pour " . $LO_XML->simulation->descriptifs->descriptif[$j]->libelle . " : ".
					"Le prix par m² doit être valide (supérieur à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
		
		if (TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->valeurlocative) <= 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet descriptif, pour " . $LO_XML->simulation->descriptifs->descriptif[$j]->libelle . " : ".
					"Le prix du loyer doit être valide (supérieur à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
		
		if (TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->loyerdecale) < 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet descriptif, pour " . $LO_XML->simulation->descriptifs->descriptif[$j]->libelle . " : ".
					"Le décallage du premier loyer doit être valide (supérieur ou égal à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
			break;
		}
		
		if ($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaleligible == "true") 
		{
			if (TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscalduree) <=0) 
			{
				$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet descriptif, pour " . $LO_XML->simulation->descriptifs->descriptif[$j]->libelle . " : ".
					"La durée du dispositif fiscal doit être valide (supérieur à 0)";
				$LC_Texte = VERIF_Texte($LC_Texte);
				$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
				$LB_Result= false;
				break;				
			}
			
			if (TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaldeduc) <=0) 
			{
				$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet descriptif, pour " . $LO_XML->simulation->descriptifs->descriptif[$j]->libelle . " : ".
					"La déduction fiscale doit être valide (supérieur à 0)";
				$LC_Texte = VERIF_Texte($LC_Texte);
				$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
				$LB_Result= false;
				break;
			}
		}
	}
	return $LB_Result;
}

Function BLOC_Financement() {
Global $LC_Retour, $LO_XML;
	
	$LC_Texte = "";
	$LB_Result= true;
	
	if (TOOL_DecodeNum($LO_XML->simulation->frais->prixachat) < 1) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement :".
				"Le prix d'achat doit être valide (supérieur à 1)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->simulation->frais->fraisnotaire) < 1) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement : ".
				"Les frais de notaire doivent être valide (supérieur à 1)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->simulation->credit->emprunt) < 0) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement : ".
				"Le montant de l'emprunt doit être valide (supérieur ou égal à  0)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal) < 0) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement : ".
				"Le taux d'emprunt doit être valide (supérieur ou égal à  0)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->simulation->credit->tauxassurance) < 0) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement : ".
				"Le taux d'assurance emprunteur doit être valide (supérieur ou égal à  0)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->simulation->credit->duree) <= 1) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement : ".
				"La durée de l'emprunt doit être valide (supérieur ou égal à 1)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if (TOOL_DecodeNum($LO_XML->simulation->credit->mensualite) < 0) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet financement : ".
				"La mensualitée de l'emprunt doit être valide (supérieur ou égal à  0)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	
	if ($LO_XML->simulation->credit->ptz == "true")  {
		if (TOOL_DecodeNum($LO_XML->simulation->credit->ptzmontant) <= 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet financement : ".
					"Le montant de l'éco PTZ doit être valide (supérieur à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
		
		if (TOOL_DecodeNum($LO_XML->simulation->credit->ptzduree) <= 0) 
		{
			$LC_Texte = 	//"~NomPrenom~, \n" .
					"Dans l'onglet financement : ".
					"La durée de l'éco PTZ doit être valide (supérieur à 0)";
			$LC_Texte = VERIF_Texte($LC_Texte);
			$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
			$LB_Result= false;
		}
	}
	return $LB_Result;
}

Function BLOC_Charges() {
Global $LC_Retour, $LO_XML;
	
	$LC_Texte = "";
	$LB_Result= true;
	if (TOOL_DecodeNum($LO_XML->simulation->charges->nbloyers) > 12) 
	{
		$LC_Texte = 	//"~NomPrenom~, \n" .
				"Dans l'onglet Charges : ".
				"Le nombre de loyers considéré doit être valide (inférieur ou égal à 12)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>";	
		$LB_Result= false;
	}
	if (TOOL_DecodeNum($LO_XML->simulation->charges->nbloyers) < 9) 
	{
		$LC_Texte = "Dans l'onglet Charges : ".
					"Le nombre de loyers considéré doit être valide (supérieur à 9)";
		$LC_Texte = VERIF_Texte($LC_Texte);
		$LC_Retour= $LC_Retour . $LC_Texte . "<br/>"; 
		$LB_Result= false;
	}
	return $LB_Result;
}

Function VERIF_Texte($PC_Texte){
Global $LO_XML;

	$LC_Texte = $PC_Texte;
	$LC_Texte = Str_Replace("~NomPrenom~", $LO_XML->investisseur->prenom . ' ' . $LO_XML->investisseur->nom, $LC_Texte);
	return $LC_Texte;
}

?>