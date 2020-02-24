<?PHP
header("Content-Type:text/xml;charset:utf-8");

// pour les tests
// neutraliser la ligne de header et l'inclusion de PDF_VerifDroits.
// lancer le test avec http://localhost/sci_ef/PDF/PDF_BilanMensuel.php?id=1&xml=Data/XML/SIM_00001_00001.xml

// fichiers de rattachement
require('../FPDF/fpdf.php');
require('PDF_MiseEnPage.php');
require('PDF_Moteur.php');
require('PDF_VerifDroits.php');

// variables globales du module
$LT_Elements   = array();
$LT_Impression = array();
$LT_Elements[]   = new ClasseCalculs("". "", "");
$LO_Presentation = new LO_PageBilanType;

// bloc  principal
$LO_XML = simplexml_load_file("../" . $_GET["xml"]);
$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
Moteur_Charge();
if ($LC_TypeFiscalite=="indiv") {
	BLOC_Annees();
	BLOC_Loyers(2);
	BLOC_Traite(3);
	BLOC_Charges(4);
	BLOC_TaxeFonciere(5);
	BLOC_ImpotsIndiv(6);
	BLOC_TresoAnnu(7);
	BLOC_TresoCumul(8);
	BLOC_TresoMensu(9);
	$LO_PDF = new FPDF('L','mm','A4');
	$LO_PDF->AddPage("landscape");
	BLOC_Titre(10);
	BLOC_EntetesIndiv(40, 5);
	$LN_Ligne = 50;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
		if ($LN_Ligne > $LO_Presentation->LN_HauteurPage)
		{
			$LO_PDF->AddPage("landscape");
			BLOC_EntetesIndiv(10, 5);
			$LN_Ligne = 25;
		}
		PDF_Pyjama(5, $LN_Ligne);
		PDF_Style("DETAIL");
		$LO_PDF->Text(  5, $LN_Ligne, PDF_AligneDroite ($LT_Impression[$i][1]));
		$LO_PDF->Text( 25, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][2]));
		$LO_PDF->Text( 60, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][3]));
		$LO_PDF->Text(100, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][4]));
		$LO_PDF->Text(130, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][5]));
		$LO_PDF->Text(160, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][6]));
		$LO_PDF->Text(195, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][7]));
		$LO_PDF->Text(230, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][8]));
		$LO_PDF->Text(270, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][9]));
	}
}


if ($LC_TypeFiscalite=="sci") {
	BLOC_Annees();			//1
	BLOC_Loyers(2);
	BLOC_Traite(3);
	BLOC_Charges(4);
	BLOC_Amortissements(5);
	BLOC_TaxeFonciere(6);
	BLOC_ImpotsSCI(7);
	BLOC_TresoSCI(8);
	BLOC_DividAsso(9);
	BLOC_TresoSCIBis(10);
	BLOC_TresoSCICumul(11);
	$LO_PDF = new FPDF('L','mm','A4');
	$LO_PDF->AddPage("landscape");
	BLOC_Titre(10);
	BLOC_EntetesSCI(40, 5);
	$LN_Ligne = 50;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
		if ($LN_Ligne > $LO_Presentation->LN_HauteurPage)
		{
			$LO_PDF->AddPage("landscape");
			BLOC_EntetesSCI(10, 5);
			$LN_Ligne = 25;
		}
		PDF_Pyjama(5, $LN_Ligne);
		PDF_Style("DETAIL");
		$LO_PDF->Text(  5, $LN_Ligne, PDF_AligneDroite ($LT_Impression[$i][1]));
		$LO_PDF->Text( 20, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][2]));
		$LO_PDF->Text( 40, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][3]));
		$LO_PDF->Text( 65, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][4]));
		$LO_PDF->Text( 90, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][5]));
		$LO_PDF->Text(120, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][6]));
		$LO_PDF->Text(140, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][7]));
		$LO_PDF->Text(165, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][8]));
		$LO_PDF->Text(195, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][9]));
		$LO_PDF->Text(220, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][10]));
		$LO_PDF->Text(250, $LN_Ligne, PDF_AligneMontant($LT_Impression[$i][11]));
	}
}

$LO_PDF->Output("../Ajax/PDF_BilanMensuel_" . TOOL_SessionLire("GET", "id") . ".pdf", 'F');
echo $LC_XML;

function BLOC_Titre($PN_Ligne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_Ligne = $PN_Ligne;

	// titre du bloc
	PDF_Style("TITRE");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("PROJET D'INVESTISSEMENT LOCATIF", 297));

	$LN_Ligne += $LO_Presentation->LN_HautLigTitre*1.25;
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("DETAIL MENSUEL", 297));

	$LN_Ligne += $LO_Presentation->LN_HautLigTitre;
	//PDF_Style("SOUSTITRE");
	//$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Description du projet d'investissement locatif :", 210));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	PDF_Style("DETAIL");
	$LO_PDF->Text(5,  $LN_Ligne, ($LO_XML->simulation->descriptif->descriptif));
}

function BLOC_EntetesIndiv($PN_Ligne, $PN_Colonne) {
Global $LO_PDF, $LO_Presentation;

	$LN_Ligne = $PN_Ligne;
	$LN_Colonne = $PN_Colonne;
	PDF_Style("DETAIL");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Date");
		
	$LN_Colonne  +=  20;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Loyers annuels");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "perçu");
	
	$LN_Colonne  +=  35;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Traites de l'emprunt");
	PDF_Style("REMARQUES");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigRem, "(ADI incluse)");
	PDF_Style("DETAIL");
	
	$LN_Colonne  +=  40;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Charges");
	PDF_Style("REMARQUES");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigRem, "(PNO + GRL, ...");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigRem*2, "+ Entretien)");
	PDF_Style("DETAIL");
	
	$LN_Colonne  +=  30;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Taxe foncière");
	
	$LN_Colonne  +=  30;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Impots +");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5,"CSG CRDS");
	
	$LN_Colonne  +=  35;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Trésorerie");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "annuelle");
	
	$LN_Colonne  +=  35;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Trésorerie cumulée");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "sur la durée du prêt");
	
	$LN_Colonne  +=  40;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Trésorerie");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "mensuelle");
}

function BLOC_Annees() {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_Annee = $LT_Elements[0]->LN_Annee;
	$LN_Mois = $LT_Elements[0]->LN_Mois;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Annee = $LT_Elements[$i]->LN_Annee;
		$LN_Mois = $LT_Elements[$i]->LN_Mois;
		$LT_Impression[$i] = array();
		$LT_Impression[$i][1] = $LN_Mois . "/" . $LN_Annee;
		for ($j=2; $j<12; $j++){$LT_Impression[$i][$j]=0;}
	}
}

function BLOC_Loyers($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Loyer = $LT_Elements[$i]->LN_LoyersPercu;
		$LT_Impression[$i][$PN_Col] = $LN_Loyer;
	}
}

function BLOC_Traite($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Traite = $LT_Elements[$i]->LN_Mensualite+$LT_Elements[$i]->LN_MensualiteADI;
		$LT_Impression[$i][$PN_Col] = $LN_Traite;
	}
}

function BLOC_Charges($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Charges = 	  $LT_Elements[$i]->LN_AssurancePNO
						+ $LT_Elements[$i]->LN_AssuranceGRL
						+ $LT_Elements[$i]->LN_ChargesCopro
						+ $LT_Elements[$i]->LN_AutresCharges 
						+ $LT_Elements[$i]->LN_FraisAgence
						+ $LT_Elements[$i]->LN_TravauxEntretien;
		$LT_Impression[$i][$PN_Col] = $LN_Charges;
	}
}

function BLOC_TaxeFonciere($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_TF = $LT_Elements[$i]->LN_TaxeFonciere;
		$LT_Impression[$i][$PN_Col] = $LN_TF;
	}
}

function BLOC_ImpotsIndiv($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_ImpoCSG = 	  $LT_Elements[$i]->LN_MontantImpots;
						+ $LT_Elements[$i]->LN_MontantCSG;
		$LT_Impression[$i][$PN_Col] = $LN_ImpoCSG;
	}
}

function BLOC_TresoAnnu($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	

	$LN_TresoAnnu= 0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		
		if ($LT_Elements[$i]->LN_Mois == 1) {
			$LN_TresoAnnu = 0;
		}
		$LN_TresoAnnu += $LT_Elements[$i]->LN_Tresorerie;
		$LT_Impression[$i][$PN_Col] = $LN_TresoAnnu;
	}
}

Function BLOC_TresoCumul($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_TresoCumul = 0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->credit->duree); $i++) {
		$LN_TresoCumul += $LT_Elements[$i]->LN_Tresorerie;
		$LT_Impression[$i][$PN_Col] = $LN_TresoCumul;
	}
}

function BLOC_TresoMensu($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_Treso= 0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Treso = $LT_Elements[$i]->LN_Tresorerie;
		$LT_Impression[$i][$PN_Col] = $LN_Treso;
	}
}

function BLOC_EntetesSCI($PN_Ligne, $PN_Colonne) {
Global $LO_PDF, $LO_Presentation, $LO_XML;

	PDF_Ruban($PN_Colonne, $PN_Ligne, 287, 18);
	$LN_Ligne = $PN_Ligne;
	$LN_Colonne = $PN_Colonne;
	PDF_Style("DETAIL");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Date");
		
	$LN_Colonne  +=  15;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Loyers");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "annuels");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale, "perçu");
	
	$LN_Colonne  +=  20;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Traites de");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "l'emprunt");
	PDF_Style("REMARQUES");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5+$LO_Presentation->LN_HautLigRem, "(ADI incluse)");
	PDF_Style("DETAIL");
	
	$LN_Colonne  +=  25;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Charges");
	PDF_Style("REMARQUES");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigRem, "(PNO + GRL, ...");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigRem*2, "+ Entretien)");
	PDF_Style("DETAIL");
	
	$LN_Colonne  +=  25;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Amortissements");
	PDF_Style("REMARQUES");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigRem, "(bâti + meubles)");
	PDF_Style("DETAIL");
	
	$LN_Colonne  +=  30;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Taxe ");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "foncière");
	
	$LN_Colonne  +=  20;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Impots SCI");
		
	$LN_Colonne  +=  25;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Trésorerie SCI");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "annuelle");
	PDF_Style("REMARQUES");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5+$LO_Presentation->LN_HautLigRem, "(avant dividendes)");
	
	PDF_Style("DETAIL");
	
	//$LN_Colonne  +=  30;
	//$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Dividendes");
	//$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "bruts versés");
	
	//$LN_Colonne  +=  25;
	//$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Impots + CSG");
	//$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "payé par associé");
	//$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale, "sur les dividendes");
	
	$LN_Colonne  +=  30;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Dividendes");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "nets perçu");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale, "par l'associé");

	$LN_Colonne  +=  25;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Trésorerie SCI");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "après paiement");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale, "des dividendes");
		
	$LN_Colonne  +=  30;
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Trésorerie SCI");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale*.5, "cumulée");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne+$LO_Presentation->LN_HautLigNormale, "sur la durée du prêt");
	
	}
	
Function BLOC_Amortissements($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_Amorti=0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Amorti = $LT_Elements[$i]->LN_AmortiBati
					+$LT_Elements[$i]->LN_AmortiMeubles;
		$LT_Impression[$i][$PN_Col] = $LN_Amorti;
	}
}
	
Function BLOC_ImpotsSCI($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_Impo=0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Impo = $LT_Elements[$i]->LN_ImpotsSCI;
		$LT_Impression[$i][$PN_Col] = $LN_Impo;
	}
}

Function BLOC_TresoSCI($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Treso = $LT_Elements[$i]->LN_TresorerieSCI;
		$LT_Impression[$i][$PN_Col] = $LN_Treso;
	}
}

Function BLOC_DividAsso($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_Divid=0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Divid = $LT_Elements[$i]->LN_DividendeNetAssocie;
		$LT_Impression[$i][$PN_Col] = $LN_Divid;
	}
}

Function BLOC_TresoSCIBis($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_TresoBis=0;
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_TresoBis = $LT_Elements[$i]->LN_TresorerieSCIRestante;
		$LT_Impression[$i][$PN_Col] = $LN_TresoBis;
	}
}

Function BLOC_TresoSCICumul($PN_Col) {
Global $LO_XML, $LT_Impression, $LT_Elements;
	
	$LN_TresoSCICumul= 0;
	for ($i=0; $i<= TOOL_DecodeNum($LO_XML->simulation->credit->duree)*12; $i++) {
		$LN_TresoSCICumul += 	  $LT_Elements[$i]->LN_TresorerieSCIRestante;
		$LT_Impression[$i][$PN_Col] = $LN_TresoSCICumul;
	}
}

?>