<?php
Session_Start();
// bloc de rattachement au projet ne rien changer

// fichiers de rattachement
require('../FPDF/fpdf.php');
require('PDF_MiseEnPage.php');
require('PDF_Moteur.php');

// variables globales du module
$LT_Elements = array();
// la ligne d'element est creee à la ligne 315
// il y a des valorisation en ligne 125, 218, 219
$LT_Elements[]   = new ClasseCalculs("". "", "");
$LO_Presentation = new LO_PageSyntheseType;

$LO_PDF = new FPDF('P','mm','A4');
$LO_XML = simplexml_load_file("../" . $_GET["xml"]);
$LO_PDF->AddPage("Portrait");
BLOC_Titre(20);
BLOC_Investissement(55, 10);
BLOC_Financement (55,115);
BLOC_Recettes(110,115);
BLOC_Charges(110,10);
BLOC_Resultats(195,80);
$LO_PDF->Output("../Ajax/PDF_Synthese_" . TOOL_SessionLire("GET", "id") . ".pdf", 'F');


function BLOC_Titre($PN_Ligne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_Ligne = $PN_Ligne;

	// titre du bloc
	PDF_Style("TITRE");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("PROJET D'INVESTISSEMENT LOCATIF", 210));

	$LN_Ligne += $LO_Presentation->LN_HautLigTitre*1.25;
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("FICHE DE SYNTHESE", 210));

	$LN_Ligne += $LO_Presentation->LN_HautLigTitre;
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	PDF_Style("DETAIL");
	$LO_PDF->Text(5,  $LN_Ligne, ($LO_XML->simulation->descriptif->descriptif));
}


function BLOC_Investissement($PN_Ligne, $PN_Colonne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_ColonneLibelle = $PN_Colonne + $LO_Presentation->LN_BlocColLib;
	$LN_ColonneValeur  = $PN_Colonne + $LO_Presentation->LN_BlocColVal;
	$LN_Ligne = $PN_Ligne;
	$LN_DecalGraph = 20;

	PDF_Style("SOUSTITRE");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("L'investissement",210/2));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigSTitre;
	PDF_Style("DETAIL");
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Prix d'achat :");
	$LN_PrixGlobal = ($LO_XML->simulation->frais->prixachat)+
	                 ($LO_XML->simulation->frais->fraisnotaire);
	$LO_PDF->Text($LN_ColonneValeur-$LN_DecalGraph,  $LN_Ligne, PDF_AligneDroite($LN_PrixGlobal)." €");
	
	PDF_Style("REMARQUES");
	$LN_Ligne += $LO_Presentation->LN_HautLigRem;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "(frais de notaires");
	$LN_Ligne += $LO_Presentation->LN_HautLigRem;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "frais d'agence inclus)");
	
	PDF_Style("DETAIL");
	
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Travaux :");
	$LN_TravauxGlobal=($LO_XML->simulation->frais->travauxndeduc)+($LO_XML->simulation->frais->travauxdeduc);
	$LO_PDF->Text($LN_ColonneValeur-$LN_DecalGraph,  $LN_Ligne, PDF_AligneDroite($LN_TravauxGlobal). " €");
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Apport :");
	$LO_PDF->Text($LN_ColonneValeur-$LN_DecalGraph,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->frais->apport)." €");
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;		
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Emprunt :");
	$LO_PDF->Text($LN_ColonneValeur-$LN_DecalGraph,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->credit->emprunt)." €");
	//Graphique du financement :
	
	//Graphique du financement :
	$LC_Image = "../Ajax/FINANCEMENT_" . $_SESSION["SCI_ALIAS"] . ".jpg";
	$LC_Image = Str_Replace(".xml", "", $LC_Image);
	$LO_PDF->Text(10, 10, $LC_Image);
	if (file_exists($LC_Image))
	{
		$LO_PDF->Image($LC_Image, 75, 60, 55, 35);
	}
}


function BLOC_Financement($PN_Ligne, $PN_Colonne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_ColonneLibelle = $PN_Colonne + $LO_Presentation->LN_BlocColLib+18;
	$LN_ColonneValeur  = $PN_Colonne + $LO_Presentation->LN_BlocColVal;
	$LN_Ligne = $PN_Ligne;
	
	PDF_Style("SOUSTITRE");
	$LO_PDF->Text(105, $LN_Ligne, PDF_CentreLibelle("Le financement",210/2));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigSTitre;
	PDF_Style("DETAIL");	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Duree du credit :");
	$LO_PDF->Text($LN_ColonneValeur, $LN_Ligne,TOOL_DecodeNum(PDF_AligneDroite($LO_XML->simulation->credit->duree)));
		
	PDF_Style("REMARQUES");	
	$LN_Ligne += $LO_Presentation->LN_HautLigRem;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "(en mois)");
		
	PDF_Style("DETAIL");
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Taux d'emprunt :");
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite(TOOL_DecodeNum($LO_XML->simulation->credit->tauxnominal))." %");
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Taux d'assurance :");
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->credit->tauxassurance)." %");
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Mensualite du credit :");
	
	PDF_Style("REMARQUES");	
	//$LN_Ligne += $LO_Presentation->LN_HautLigRem;	
	$LC_PTZ = $LO_XML->simulation->credit->ptz;
	if ($LC_PTZ=="true") {
		$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne+$LO_Presentation->LN_HautLigRem, "(hors ADI et hors EcoPTZ)");
	}
	else {
		$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne+$LO_Presentation->LN_HautLigRem, "(hors ADI)");
	}
	
	PDF_Style("DETAIL");
	$LN_Ligne += $LO_Presentation->LN_HautLigRem;		
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->credit->mensualite)." €");

	}

function BLOC_Recettes($PN_Ligne, $PN_Colonne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_ColonneLibelle = $PN_Colonne + $LO_Presentation->LN_BlocColLib;
	$LN_ColonneValeur  = $PN_Colonne + $LO_Presentation->LN_BlocColVal;
	$LN_Ligne = $PN_Ligne;
	$LN_Largeur = 209;
	
	PDF_Style("SOUSTITRE");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Les recettes",210/2));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale*2;
	PDF_Style("DETAIL");
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Loyes bruts mensuels :");	
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite(PDF_CalculLoyers())." €");
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Nombre de loyers consideres par an :");
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->charges->nbloyers));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Loyers annuels percus :");
	$LN_LoyersPercu = round(PDF_CalculLoyers() *TOOL_DecodeNum($LO_XML->simulation->charges->nbloyers),2) ;
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite($LN_LoyersPercu)." €");
}

function BLOC_Charges($PN_Ligne, $PN_Colonne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_ColonneLibelle = $PN_Colonne + $LO_Presentation->LN_BlocColLib;
	$LN_ColonneValeur  = $PN_Colonne + $LO_Presentation->LN_BlocColVal;
	$LN_Ligne = $PN_Ligne;
		
	PDF_Style("SOUSTITRE");
	$LO_PDF->Text(105, $LN_Ligne, PDF_CentreLibelle("Les charges",210/2));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale*2;
	PDF_Style("DETAIL");	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Frais de gestion annuels :");
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->charges->fraisgestion)." €");

	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Assurances annuelles :");
	$LN_AssurancesTotales = $LO_XML->simulation->charges->assurancepno+ $LO_XML->simulation->charges->assurancegrl+$LO_XML->simulation->charges->assurancecopro;
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.2, PDF_AligneDroite($LN_AssurancesTotales)." €");

	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	PDF_Style("REMARQUES");	
				$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne-$LO_Presentation->LN_HautLigNormale*.5, "(PNO, GRL, Copro)");

	PDF_Style("DETAIL");	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Taxe foncière :");
	
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	if ($LC_TypeFiscalite=="indiv") {
		$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne,PDF_AligneDroite(TOOL_DecodeNum($LO_XML->investisseur->fiscalite->PPHYSIQUE->taxefonciere)." €"));
	}
	
	if ($LC_TypeFiscalite == "sci") {
		$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne,PDF_AligneDroite(TOOL_DecodeNum($LO_XML->investisseur->fiscalite->SCI->taxefonciere)." €"));
	}		
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;		
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Impots + CSG CRDS :");
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite(PDF_ImpotsBilan(). " €"));
	
	$LN_ExistDispFiscal=0;
	for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){			
		$LC_DispFiscal = $LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaleligible;		
		if ($LC_DispFiscal=="true") {
			$LN_ExistDispFiscal=1;
			break;
		}
	}
		if ($LN_ExistDispFiscal==1) {
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			PDF_Style("REMARQUES");	
			$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne-$LO_Presentation->LN_HautLigNormale*.5, "(après dispositif fiscal)");

			
			//$LN_Ligne += $LO_Presentation->LN_HautLigRem;
			//PDF_Style("REMARQUES");	
//$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne-$LO_Presentation->LN_HautLigNormale*.5, "(après dispositif fiscal)");
		}	
	PDF_Style("DETAIL");
		
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Annuite du credit :");

	$LN_Ligne += $LO_Presentation->LN_HautLigRem;		
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite(PDF_Annuite())." €");
	
	//$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	$LN_Ligne += $LO_Presentation->LN_HautLigRem;
	PDF_Style("REMARQUES");	
	
	$LC_PTZ = $LO_XML->simulation->credit->ptz;
	if ($LC_PTZ=="true") {
		$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne-$LO_Presentation->LN_HautLigNormale*.5, "(ADI incluse, hors EcoPTZ");
	}
	else {
		$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne-$LO_Presentation->LN_HautLigNormale*.5, "(ADI incluse)");
	}
		
	
	PDF_Style("DETAIL");	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	$LO_PDF->Text($LN_ColonneLibelle, $LN_Ligne, "Autres charges annuelles");
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite($LO_XML->simulation->charges->autrescharges)." €");
}
	
function BLOC_Resultats($PN_Ligne, $PN_Colonne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_ColonneLibelle = $PN_Colonne + $LO_Presentation->LN_BlocColLib;
	$LN_ColonneValeur  = $PN_Colonne + $LO_Presentation->LN_BlocColVal;
	$LN_Ligne = $PN_Ligne;
	$LN_Largeur = 209;
	
	PDF_Style("BILAN");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Ratio Traite / Loyers :",210*2/3));
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite(PDF_RatioTraiteLoyers() . " %"));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;	
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Rendement brut à l'achat :",210*2/3));
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, PDF_AligneDroite(PDF_RendemmentBrut() . " %"));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	//$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Tresorerie totale à la fin",210*2/3));
	
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	if ($LC_TypeFiscalite=="indiv") {
		$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Tresorerie totale à la fin",210*2/3));
	}
	
	if ($LC_TypeFiscalite == "sci") {
		$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Tresorerie totale de la SCI à la fin",210*2/3));
		//$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.5;
		
	}	
	
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.25, PDF_TresoFinCredit ()." €");
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.75;
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("du remboursement du prêt :",210*2/3));
	if ($LC_TypeFiscalite == "sci") {
		$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.5;
		PDF_Style("DETAIL");
		$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("après paiement des dividendes ",210*2/3));
	}	
	PDF_Style("BILAN");
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	
	if ($LC_TypeFiscalite == "indiv") {
		list($LC_PossiblRembAnt, $LN_DateRembAnt, $LN_DateUtilisRembAnt)=PDF_RembAntIndiv();
		if ($LC_PossiblRembAnt=="true") {
			$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Possibilite de remboursement anticipe en",210*2/3));
			$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, $LN_DateRembAnt);
			$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.5;
			PDF_Style("DETAIL");
			$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("en utilisant la tresorerie degagee en ",210*2/3));
			$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, $LN_DateUtilisRembAnt);
		}
	}
	else {
		list($LC_PossiblRembAnt, $LN_DateRembAnt, $LN_DateUtilisRembAnt)=PDF_RembAntSCI();
		if ($LC_PossiblRembAnt=="true") {
			$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Possibilite de remboursement anticipe en",210*2/3));
			$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, $LN_DateRembAnt);
			$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.5;
			PDF_Style("DETAIL");
			$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("en utilisant la tresorerie degagee en ",210*2/3));
			$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne, $LN_DateUtilisRembAnt);
		}
	}
	
	

	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	PDF_Style("BILAN");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Tresorerie mensuelle minimun",210*2/3));
		
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	if ($LC_TypeFiscalite=="indiv") {
		$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.25, PDF_TresoMiniPretIndiv()." €");

	}	
	else {//if ($LC_TypeFiscalite == "sci") {
		$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.25, PDF_TresoMiniPretSCI()." €");
	}	
	
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.75;
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("sur la duree du prêt :",210*2/3));
	
	
	if ($LC_TypeFiscalite == "sci") {
		$LN_Ligne += $LO_Presentation->LN_HautLigNormale*2;
		PDF_Style("BILAN");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Dividendes total perçu par l'associé",210*2/3));
	$LO_PDF->Text($LN_ColonneValeur,  $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.25, PDF_DividendeNetTotalAssocie()." €");
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.75;
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("sur la duree du prêt :",210*2/3));
	PDF_Style("DETAIL");
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale*.5;
			$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Après impots et cotisations sociales ",210*2/3));
	}
}
	
Function PDF_RatioTraiteLoyers(){
Global $LO_XML;
	$LN_ValeurLoc  = PDF_CalculLoyers();
	$LN_Mensualite = TOOL_DecodeNum($LO_XML->simulation->credit->mensualite);
	$LN_Result = 0;
	if ($LN_ValeurLoc != 0)
		{
			$LN_Result = Round(($LN_Mensualite/$LN_ValeurLoc)*100, 2);
		}
	return $LN_Result;
}


Function PDF_Annuite(){
Global $LO_XML;
	$LN_Emprunt = TOOL_DecodeNum($LO_XML->simulation->credit->emprunt);
	$LN_Mensualite = TOOL_DecodeNum($LO_XML->simulation->credit->mensualite);
	$LN_TxAssurEmprunt = TOOL_DecodeNum($LO_XML->simulation->credit->tauxassurance);
	
	$LN_Result=round(($LN_Emprunt*$LN_TxAssurEmprunt/100)+($LN_Mensualite*12),2);

	return $LN_Result;
}


Function PDF_RendemmentBrut(){
Global $LO_XML;
	$LN_ValeurLoc  = PDF_CalculLoyers();
	$LN_PrixAchat = TOOL_DecodeNum($LO_XML->simulation->frais->prixachat);
	$LN_Result = 0;
	if ($LN_PrixAchat != 0)
		{
			$LN_Result = Round((($LN_ValeurLoc*12) / $LN_PrixAchat) * 100, 2);
		}
	//TOOL_Debug($LN_Result);
	return $LN_Result;
}

function PDF_CalculLoyers () {
	Global $LO_XML, $LO_PDF;
	$LN_LoyerAffiche = 0;
	
	for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
		$LC_LoyersSaisonniers = $LO_XML->simulation->descriptifs->descriptif[$j]->loyerssaisonniers;
	
	$LN_LoyerLu = 0;					
		if ($LC_LoyersSaisonniers=="false") {
				$LN_LoyerLu = TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->valeurlocative);
		}
				
		if ($LC_LoyersSaisonniers=="true") {
			$LN_LoyerLuPourMoyenne=0;
			for ($k=1;$k<=12;$k++) {
			$LN_LoyerLuPourMoyenne += TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->loyerssaison[$k]);
			}							
			$LN_LoyerLu = round($LN_LoyerLuPourMoyenne/12,2) ;
		}
	$LN_LoyerAffiche += $LN_LoyerLu;
	}
	
	return $LN_LoyerAffiche;
}

Function PDF_TresoFinCredit() {
	Global $LO_XML, $LT_Elements;
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree);
	$LN_TFC = 0;
	//echo "***" . $LO_XML->investisseur->nom . "***";
	//echo "***" . $LO_XML->simulation->credit->duree . "***";
	//echo "****" . $LN_DureeCredit;
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	if ($LC_TypeFiscalite=="indiv") {
		$LN_TFC =  $LT_Elements[$LN_DureeCredit-1]->LN_TresorerieCumul;
	}
	
	if ($LC_TypeFiscalite == "sci") {
		$LN_TFC =  $LT_Elements[$LN_DureeCredit-1]->LN_TresorerieSCIRestanteCumul;
	}	
	
	return $LN_TFC;
}


Function PDF_ImpotsBilan () {
Global $LO_XML, $LT_Elements;
	$LN_DureeMax= 0;
	$LN_ImpotsBilan = 0;
	for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
			
		$LC_DispFiscal = $LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaleligible;
		$LN_LoyerDecale = TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->loyerdecale);
		//echo "loyer decal = ".$LN_LoyerDecale."<br>";
				
		if ($LC_DispFiscal=="true") {
			$LN_DispFiscalDuree =TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscalduree);
				if 	($LN_DispFiscalDuree*12>$LN_DureeMax) {
					$LN_DureeMax = $LN_DispFiscalDuree;
				}				
		}
		
		if ($LN_LoyerDecale>$LN_DureeMax) {			
			$LN_DureeMax = $LN_LoyerDecale ;
			//echo"max = decal ".$LN_DureeMax."<br><br>";
		}
			
	}
	
	if ($LN_DureeMax==0) {
		$LN_DureeMax=1;
	}
	
	//echo "=> duree max : ".$LN_DureeMax;
	
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	
	if ($LC_TypeFiscalite=="indiv") {
	
		for ($i=12;$i<25;$i++) {
			if ($LT_Elements[($LN_DureeMax)+$i]->LN_Mois == 12) {
				$LN_ImpotsBilan = 	$LT_Elements[($LN_DureeMax)+$i]->LN_MontantImpots*12
									+$LT_Elements[($LN_DureeMax)+$i]->LN_MontantCSG*12;
			}
		}
	}
	
	if ($LC_TypeFiscalite=="sci") {
		for ($i=12;$i<25;$i++) {
			if ($LT_Elements[($LN_DureeMax)+$i]->LN_Mois == 12) {
				$LN_ImpotsBilan = 	$LT_Elements[($LN_DureeMax)+$i]->LN_ImpotsSCI*12;
			}
		}
	}
	
	
	return $LN_ImpotsBilan;
	
}

Function PDF_ImpotsBilan_BAK () {
Global $LO_XML, $LT_Elements;
	$LN_DureeMaxDispFisc = 0;
	$LN_ImpotsBilan = 0;
	for ($j=0; $j<count ($LO_XML->simulation->descriptifs->children());$j++){
			
		$LC_DispFiscal = $LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscaleligible;
				
		if ($LC_DispFiscal=="true") {
			$LN_DispFiscalDuree =TOOL_DecodeNum($LO_XML->simulation->descriptifs->descriptif[$j]->dispfiscalduree);
				if 	($LN_DispFiscalDuree>$LN_DureeMaxDispFisc) {
					$LN_DureeMaxDispFisc = $LN_DispFiscalDuree;
				}
		}
			
	}
	
	$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
	
	if ($LC_TypeFiscalite=="indiv") {
	
		for ($i=1;$i<13;$i++) {
			if ($LT_Elements[($LN_DureeMaxDispFisc*12)+$i]->LN_Mois == 12) {
				$LN_ImpotsBilan = 	$LT_Elements[($LN_DureeMaxDispFisc*12)+$i]->LN_MontantImpots*12
									+$LT_Elements[($LN_DureeMaxDispFisc*12)+$i]->LN_MontantCSG*12;
			}
		}
	}
	
	if ($LC_TypeFiscalite=="sci") {
		for ($i=1;$i<13;$i++) {
			if ($LT_Elements[($LN_DureeMaxDispFisc*12)+$i]->LN_Mois == 12) {
				$LN_ImpotsBilan = 	$LT_Elements[($LN_DureeMaxDispFisc*12)+$i]->LN_ImpotsSCI*12;
			}
		}
	}
	
	
	return $LN_ImpotsBilan;
	
}

Function PDF_TresoMiniPretIndiv() {
	Global $LO_XML, $LT_Elements;
	
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree);//*12;
	$LN_TMDP = 10000*10000;
	 for ($i=1;$i<$LN_DureeCredit;$i++)	{
		 if ($LT_Elements[$i]->LN_Tresorerie < $LN_TMDP) {
			 $LN_TMDP=$LT_Elements[$i]->LN_Tresorerie;
		 }
	 }
	 return $LN_TMDP ;
	
}

function PDF_RembAntIndivBak() {
	Global $LO_XML, $LT_Elements, $LO_PDF;
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree);//*12;
	$LN_DateDebut  = $LO_XML->simulation->datedebut;
	$LN_DateDebutTimestamp = strtotime($LN_DateDebut);
	$LN_DateRembAnt =   date('d-m-Y', strtotime('+'.$LN_DureeCredit . ' month', $LN_DateDebutTimestamp ));
	$LN_DateUtilisRembAnt = $LN_DateRembAnt;
	$LN_DateRembAntTimestamp = strtotime($LN_DateRembAnt);
	$LC_PossiblRembAnt = "false";
	//$LO_PDF->Text(10,10, $LN_DateRembAnt);
	//$j=10;
	for ($i=1; $i<$LN_DureeCredit; $i++) {
		$LN_DateMoteurTimestamp = strtotime($LT_Elements[$i-1]->LN_DateRemAnticipeIndiv);
		if (($LN_DateMoteurTimestamp<$LN_DateRembAntTimestamp) && ($LN_DateMoteurTimestamp>strtotime('+'.$i. ' month', $LN_DateDebutTimestamp))){
			$LN_DateRembAntTimestamp = $LN_DateMoteurTimestamp;
			$LN_DateUtilisRembAnt = strtotime('+'.$i. ' month', $LN_DateDebutTimestamp );
			$LC_PossiblRembAnt = "true";
			//$LO_PDF->Text(10,$j, date('d-m-Y',$LN_DateRembAntTimestamp));
			//$j+=5;
		}
	}
	setlocale (LC_TIME, 'fr_FR.utf8','fra');	
	//return 	array(strftime('%B %Y',$LN_DateRembAntTimestamp), strftime('%B %Y',$LN_DateUtilisRembAnt) );
	return 	array($LC_PossiblRembAnt, $LN_DateRembAntTimestamp, $LN_DateUtilisRembAnt);
	
}

function PDF_RembAntIndiv() {
	Global $LO_XML, $LT_Elements, $LO_PDF;
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree);//*12;

	
	$a = $LT_Elements[0]->LN_Annee;
	$m = $LT_Elements[0]->LN_Mois;
	
	TOOL_VieillirDate($m, $a, $LN_DureeCredit);
	
	$LN_DateFinCreditCalculee = $a*12 + $m;
	$LC_PossiblRembAnt = "false";
	$LN_DatePossibRembAnt = 0;
	$LN_DateUtilisRembAnt = 0;
	
	$LN_DateMiniRembAnt = $LT_Elements[0]->LN_DateRemAnticipeIndivCalculee;
	
	for ($i=2; $i<$LN_DureeCredit; $i++) {
		$LN_DateMiniRembAntLu = $LT_Elements[$i-1]->LN_DateRemAnticipeIndivCalculee;
		if ($LN_DateMiniRembAntLu<$LN_DateFinCreditCalculee){
			if ($LN_DateMiniRembAntLu<$LN_DateMiniRembAnt) 
			 {
				$LN_DateMiniRembAnt=$LN_DateMiniRembAntLu;
				
				$LC_PossiblRembAnt = "true";
				$LN_DatePossibRembAnt = $LT_Elements[$i-1]->LN_DateRemAnticipeIndiv;
				$LN_DateUtilisRembAnt = TOOL_NomMois($LT_Elements[$i-1]->LN_Mois)." ".$LT_Elements[$i-1]->LN_Annee;
			}
		}
	}
	
	return 	array($LC_PossiblRembAnt, $LN_DatePossibRembAnt, $LN_DateUtilisRembAnt);
	
}

function PDF_RembAntSCIBAK() {
	Global $LO_XML, $LT_Elements, $LO_PDF;
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree);
	$LN_DateDebut  = $LO_XML->simulation->datedebut;
	$LN_DateDebutTimestamp = strtotime($LN_DateDebut);
	$LN_DateRembAnt =   date('d-m-Y', strtotime('+'.$LN_DureeCredit . ' month', $LN_DateDebutTimestamp ));
	$LN_DateUtilisRembAnt = $LN_DateRembAnt;
	$LN_DateRembAntTimestamp = strtotime($LN_DateRembAnt);
	$LC_PossiblRembAnt = "false";
	
	//$LO_PDF->Text(10,10, $LN_DateRembAnt);
	//$j=10;
	for ($i=1; $i<$LN_DureeCredit; $i++) {
		$LN_DateMoteurTimestamp = strtotime($LT_Elements[$i-1]->LN_DateRemAnticipeSCI);
		if (($LN_DateMoteurTimestamp<$LN_DateRembAntTimestamp) && ($LN_DateMoteurTimestamp>strtotime('+'.$i. ' month', $LN_DateDebutTimestamp))){
			$LN_DateRembAntTimestamp = $LN_DateMoteurTimestamp;
			$LN_DateUtilisRembAnt = strtotime('+'.$i. ' month', $LN_DateDebutTimestamp );
			$LC_PossiblRembAnt = "true";
			//$LO_PDF->Text(10,$j, date('d-m-Y',$LN_DateRembAntTimestamp));
			//$j+=5;
		}
	}
	setlocale (LC_TIME, 'fr_FR.utf8','fra');	
	//return 	array(strftime('%B %Y',$LN_DateRembAntTimestamp), strftime('%B %Y',$LN_DateUtilisRembAnt) );
	return 	array($LC_PossiblRembAnt,$LN_DateRembAntTimestamp, $LN_DateUtilisRembAnt);
}

function PDF_RembAntSCI() {
	Global $LO_XML, $LT_Elements, $LO_PDF;
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree);//*12;

	
	$a = $LT_Elements[0]->LN_Annee;
	$m = $LT_Elements[0]->LN_Mois;
	
	TOOL_VieillirDate($m, $a, $LN_DureeCredit);
	
	$LN_DateFinCreditCalculee = $a*12 + $m;
	
	$LC_PossiblRembAnt = "false";
	$LN_DatePossibRembAnt = 0;
	$LN_DateUtilisRembAnt = 0;
	
	$LN_DateMiniRembAnt = $LT_Elements[0]->LN_DateRemAnticipeSCICalculee;
	
	for ($i=2; $i<$LN_DureeCredit; $i++) {
		$LN_DateMiniRembAntLu = $LT_Elements[$i-1]->LN_DateRemAnticipeSCICalculee;
		if ($LN_DateMiniRembAntLu<$LN_DateFinCreditCalculee){
			if ($LN_DateMiniRembAntLu<$LN_DateMiniRembAnt) 
			 {
				$LN_DateMiniRembAnt=$LN_DateMiniRembAntLu;
				
				$LC_PossiblRembAnt = "true";
				$LN_DatePossibRembAnt = $LT_Elements[$i-1]->LN_DateRemAnticipeSCI;
				$LN_DateUtilisRembAnt = TOOL_NomMois($LT_Elements[$i-1]->LN_Mois)." ".$LT_Elements[$i-1]->LN_Annee;
			}
		}
	}
	
	return 	array($LC_PossiblRembAnt, $LN_DatePossibRembAnt, $LN_DateUtilisRembAnt);
}

Function PDF_TresoMiniPretSCI() {
	Global $LO_XML, $LT_Elements;
	
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree)*12;
	$LN_TMDP = 10000*10000;
	 for ($i=1;$i<$LN_DureeCredit;$i++)	{
		 if ($LT_Elements[$i]->LN_TresorerieSCI < $LN_TMDP) {
			 $LN_TMDP=$LT_Elements[$i]->LN_TresorerieSCI;
		 }
	 }
	 return $LN_TMDP ;
	
}

Function PDF_DividendeNetTotalAssocie () {
	global $LO_XML, $LT_Elements;
	
	$LN_DureeCredit = TOOL_DecodeNum($LO_XML->simulation->credit->duree)*12;
	
	$LN_DNTA = 0;
	for ($i=1; $i<$LN_DureeCredit; $i++) {
		$LN_DNTA +=$LT_Elements[$i-1]->LN_DividendeNetAssocie;
	}
	return $LN_DNTA;
}


Function ESSAI_Date(){
Global $LO_XML;
	$LN_DateDebut  = $LO_XML->simulation->datedebut;
	$LN_Date = date_parse($LN_DateDebut);
	$LN_Jour = $LN_Date['day'];
	$LN_Mois = $LN_Date['month'];
	$LN_Annee = $LN_Date['year'];
	$LC_Result="Origine :".$LN_DateDebut ."   Sortie :".$LN_Jour."/".$LN_Mois."/".$LN_Annee; 
	return $LC_Result;
}
?>