<?php
Session_Start();
// bloc de rattachement au projet ne rien changer

// fichiers de rattachement
require('../FPDF/fpdf.php');
require('PDF_MiseEnPage.php');
require('PDF_Moteur.php');

// variables globales du module
$LT_Elements   = array();
$LT_Impression = array();
$LT_Elements[]   = new ClasseCalculs("". "", "");
$LO_Presentation = new LO_PageBilanType;

// bloc  principal
$LO_XML = simplexml_load_file("../" . $_GET["xml"]);
Moteur_Charge();
Moteur_Graphe();

// générer le PDF
$LC_Graphe = "../Ajax/RENTABILITE_" . TOOL_SessionLire("GET", "id") . ".jpg";
$LO_PDF = new FPDF('L','mm','A4');
$LO_PDF->AddPage("landscape");
$LO_PDF->Image($LC_Graphe, 10, 10, 270, 190);
$LO_PDF->Output("../Ajax/PDF_Rentabilite_" . TOOL_SessionLire("GET", "id") . ".pdf", 'F');
