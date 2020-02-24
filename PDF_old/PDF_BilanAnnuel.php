<?php
Session_Start();
// bloc de rattachement au projet ne rien changer

// fichiers de rattachement
require('../FPDF/fpdf.php');
require('PDF_MiseEnPage.php');
require('PDF_Moteur.php');

// variables globales du module
$LT_Elements = array();
$LT_Elements[]   = new ClasseCalculs("". "", "");
$LO_Presentation = new LO_PageBilanType;

// bloc  principal
$LO_XML = simplexml_load_file("../" . $_GET["xml"]);
$LO_PDF = new FPDF('L','mm','A4');
$LO_PDF->AddPage("landscape");

$LC_TypeFiscalite = $LO_XML->investisseur->fiscalite->typefiscalite;
Moteur_Charge();

if ($LC_TypeFiscalite=="indiv") {
	BLOC_Titre(10);
	BLOC_EntetesIndiv(40, 5);
	BLOC_Annees(55, 5);
	BLOC_Loyers(55, 25);
	BLOC_Traite(55, 60);
	BLOC_Charges(55, 100);
	BLOC_TaxeFonciere(55, 130);
	BLOC_ImpotsIndiv(55, 160);
	BLOC_TresoAnnu(55,195);
	BLOC_TresoCumul(55,230);
	BLOC_TresoMensu(55,270);
}
if ($LC_TypeFiscalite=="sci") {
	BLOC_Titre(10);
	BLOC_EntetesSCI(40, 5);
	BLOC_Annees(55, 5);
	BLOC_Loyers(55, 20);
	BLOC_Traite(55, 40);
	BLOC_Charges(55, 65);
	BLOC_Amortissements(55,90);
	BLOC_TaxeFonciere(55, 120);
	BLOC_ImpotsSCI(55,140);
	BLOC_TresoSCI(55,165);
	BLOC_DividAsso(55,195);
	BLOC_TresoSCIBis(55,220);
	BLOC_TresoSCICumul(55,250);
}

$LO_PDF->Output("../Ajax/PDF_BilanAnnuel_" . TOOL_SessionLire("GET", "id") . ".pdf", 'F');


function BLOC_Titre($PN_Ligne)
{
Global $LO_PDF, $LO_Presentation, $LO_XML;
	// variables internes
	$LN_Ligne = $PN_Ligne;

	// titre du bloc
	PDF_Style("TITRE");
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("PROJET D'INVESTISSEMENT LOCATIF", 297));

	$LN_Ligne += $LO_Presentation->LN_HautLigTitre*1.25;
	$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("DETAIL ANNUEL", 297));

	$LN_Ligne += $LO_Presentation->LN_HautLigTitre;
	//PDF_Style("SOUSTITRE");
	//$LO_PDF->Text(1, $LN_Ligne, PDF_CentreLibelle("Description du projet d'investissement locatif :", 210));
	
	$LN_Ligne += $LO_Presentation->LN_HautLigNormale;
	PDF_Style("DETAIL");
	$LO_PDF->Text(5,  $LN_Ligne, ($LO_XML->simulation->descriptif->descriptif));
}

function BLOC_EntetesIndiv($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML;
	$LN_Ligne = $PN_Ligne;
	$LN_Colonne = $PN_Colonne;
	PDF_Style("DETAIL");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Année");
		
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

function BLOC_Annees($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Ligne = $PN_Ligne;
	$LN_Colonne = $PN_Colonne;
	
	$LN_Annee = $LT_Elements[0]->LN_Annee;
	PDF_Style("DETAIL");
	PDF_Pyjama($LN_Colonne, $LN_Ligne);
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, $LN_Annee);
	for ($i=1; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		if ($LN_Annee == $LT_Elements[$i]->LN_Annee) {
		}
		else {
			$LN_Annee = $LT_Elements[$i]->LN_Annee;
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			PDF_Pyjama($LN_Colonne, $LN_Ligne);
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, $LN_Annee);
		}
	}
}

function BLOC_Loyers($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_LoyerAnnuel = 0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_LoyerAnnuel += $LT_Elements[$i]->LN_LoyersPercu;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne,PDF_AligneDroite($LN_LoyerAnnuel));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_LoyerAnnuel = 0;
		}
	}
}

function BLOC_Traite($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_Traite = 0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Traite += $LT_Elements[$i]->LN_Mensualite+$LT_Elements[$i]->LN_MensualiteADI;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_Traite));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_Traite = 0;
		}
	}
}

function BLOC_Charges($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_Charges = 0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Charges += 	  $LT_Elements[$i]->LN_AssurancePNO
						+ $LT_Elements[$i]->LN_AssuranceGRL
						+ $LT_Elements[$i]->LN_ChargesCopro
						+ $LT_Elements[$i]->LN_AutresCharges 
						+ $LT_Elements[$i]->LN_FraisAgence
						+ $LT_Elements[$i]->LN_TravauxEntretien;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_Charges));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_Charges = 0;
		}
	}
}

function BLOC_TaxeFonciere ($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_TF = 0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_TF += 	  $LT_Elements[$i]->LN_TaxeFonciere;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite(number_format((float)$LN_TF, 2, '.', '')));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_TF = 0;
		}
	}
}

function BLOC_ImpotsIndiv ($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_ImpoCSG = 0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_ImpoCSG += 	  $LT_Elements[$i]->LN_MontantImpots;
						+ $LT_Elements[$i]->LN_MontantCSG;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_ImpoCSG));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_ImpoCSG = 0;
		}
	}
}

function BLOC_TresoAnnu ($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_TresoAnnu= 0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_TresoAnnu += 	  $LT_Elements[$i]->LN_Tresorerie;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_TresoAnnu));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_TresoAnnu = 0;
		}
	}
}

Function BLOC_TresoCumul($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_TresoCumul= 0;
	
	for ($i=0; $i<= TOOL_DecodeNum($LO_XML->simulation->credit->duree); $i++) {
		$LN_TresoCumul += 	  $LT_Elements[$i]->LN_Tresorerie;
		
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_TresoCumul));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			//$LN_TresoAnnu = 0;
			//$LO_PDF->Text($LN_Colonne, $LN_Ligne, "-");
			//$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
		}
	}
}

function BLOC_TresoMensu ($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_TresoAnnu= 0;
	$LN_NbMois = 1;
	
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_NbMois+=1;
		$LN_TresoAnnu += 	  $LT_Elements[$i]->LN_Tresorerie;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite(round($LN_TresoAnnu/$LN_NbMois,2)));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_TresoAnnu = 0;
			$LN_NbMois=0;
		}
	}
}

function BLOC_EntetesSCI($PN_Ligne, $PN_Colonne) {
Global $LO_PDF, $LO_Presentation, $LO_XML;

	PDF_Ruban($PN_Colonne, $PN_Ligne, 287, 18);
	$LN_Ligne = $PN_Ligne;
	$LN_Colonne = $PN_Colonne;
	PDF_Style("DETAIL");
	$LO_PDF->Text($LN_Colonne, $LN_Ligne, "Année");
		
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
	
Function BLOC_Amortissements($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_Amorti=0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Amorti += $LT_Elements[$i]->LN_AmortiBati
					+$LT_Elements[$i]->LN_AmortiMeubles;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_Amorti));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_Amorti = 0;
		}
	}
}
	
Function BLOC_ImpotsSCI($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_Impo=0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Impo += $LT_Elements[$i]->LN_ImpotsSCI;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_Impo));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_Impo = 0;
		}
	}
}

Function BLOC_TresoSCI($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_Treso=0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Treso += $LT_Elements[$i]->LN_TresorerieSCI;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_Treso));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_Treso = 0;
		}
	}
}

Function BLOC_DividAsso($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_Divid=0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_Divid += $LT_Elements[$i]->LN_DividendeNetAssocie;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_Divid));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_Divid = 0;
		}
	}
}

Function BLOC_TresoSCIBis($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_TresoBis=0;
	
	for ($i=0; $i< TOOL_DecodeNum($LO_XML->simulation->duree)*12; $i++) {
		$LN_TresoBis += $LT_Elements[$i]->LN_TresorerieSCIRestante;
		if ($LT_Elements[$i]->LN_Mois == 12) {
			$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_TresoBis));
			$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			$LN_TresoBis = 0;
		}
	}
}

Function BLOC_TresoSCICumul($PN_Ligne, $PN_Colonne) {
	Global $LO_PDF, $LO_Presentation, $LO_XML, $LT_Elements;
	
	$LN_Colonne = $PN_Colonne;
	$LN_Ligne = $PN_Ligne;
	$LN_TresoSCICumul= 0;
	
	for ($i=0; $i<= TOOL_DecodeNum($LO_XML->simulation->credit->duree)*12; $i++) {
		$LN_TresoSCICumul += $LT_Elements[$i]->LN_TresorerieSCIRestante;
		
		$LO_PDF->Text($LN_Colonne, $LN_Ligne, PDF_AligneDroite($LN_TresoSCICumul));
		$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
			//$LN_TresoAnnu = 0;
		
			//$LO_PDF->Text($LN_Colonne, $LN_Ligne, "-");
			//$LN_Ligne+=$LO_Presentation->LN_HautLigNormale;
		
	}
}

?>