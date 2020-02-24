<?PHP
Session_start();


header("Content-Type:text/xml;charset:utf-8");


require('SCI_EF.inc');
require('Graphes/GrapheCamembert.php');
require('Graphes/GrapheHistoEmpiles.php');
require('Graphes/GrapheHisto.php');
require('Graphes/GrapheLignes.php');


$LC_Commentaire = "OK";
$LT_Couleur=[];
$LT_Couleur[] = "133,235,099";
$LT_Couleur[] = "255,255,102";
$LT_Couleur[] = "240,000,005";
$LT_Couleur[] = "051,102,204";
$LT_Couleur[] = "251,100,010";
$LT_Couleur[] = "051,200,010";
$LT_Couleur[] = "051,100,100";
$LT_Couleur[] = "255,255,102";
$LT_Couleur[] = "204,000,051";
$LT_Couleur[] = "051,102,204";

	Switch ($_GET["cle"]){
		case "grapheFinancement" :
				AJAX_GrapheFinancement($_GET["param"]);
				break;
		case "grapheEndettement" :
				AJAX_GrapheEndettement($_GET["param"]);
				break;
		case "grapheTest" :
				AJAX_GrapheEvolution($_GET["param"]);
				break;
	}	

	
function AJAX_ControleObligatoire($PC_Champ, $PC_Message){
Global $LC_Commentaire;
	// sortie anticipée
	if (StrPos($LC_Commentaire, $PC_Message) > 0){return;}
	// controles
	if ($PC_Champ=="0"){$LC_Commentaire=$LC_Commentaire . $PC_Message . " \n";}
	if ($PC_Champ=="") {$LC_Commentaire=$LC_Commentaire . $PC_Message . " \n";}
}


function AJAX_SupprimerFichiers($PC_Racine){
	$LC_Repertoire = "Ajax";
	$LC_Contenu = opendir($LC_Repertoire);
	while (false !== ($LC_Fichier = readdir($LC_Contenu)))
		{
			$LC_NomComplet = $LC_Repertoire."/".$LC_Fichier; 
			if ($LC_Fichier != ".." AND 
				$LC_Fichier != "." AND 
				StrPos("*" . StrToUpper($LC_Fichier), $PC_Racine) == 1 AND
				!is_dir($LC_Fichier))
				{
					//unlink($LC_NomComplet); 
				}
		}
	closedir($LC_Contenu);
}

	
function AJAX_GrapheFinancement($PC_Param){
Global $LT_Couleur;

	$LC_Image       = "FINANCEMENT_" . $_SESSION["SCI_ALIAS"] . ".jpg";
	$LC_Image       = Str_Replace(".xml", "", $LC_Image);
	$LC_Commentaire = "";
	$LT_Param       = Explode(";", $PC_Param);
	$LT_Elements    = array();
	$LT_Totaux      = array();
	
	// initialiser les totaux Ã  0
	for ($i=0; $i<10; $i++)
		{
			$LT_Totaux[] = 0;
		}
	// charger les classes	
	for ($i=0; $i<Count($LT_Param); $i++)
		{
			$LT_SParam = Explode("_", $LT_Param[$i]);
			if (Count($LT_SParam)==3)
				{
					$LT_Elements[] = new LO_ClasseHistoEmpiles($LT_SParam[0], 
															$LT_SParam[2],
															$LT_Couleur[$i],
															$LT_SParam[1]);
					$LT_Totaux[$LT_SParam[0]] += $LT_SParam[2];
				}	
		}

	// construire le commentaire
	if ($LT_Totaux[1]==$LT_Totaux[2])
		{
			$LC_Commentaire = "Le budget est équilibré";		
		}
	if ($LT_Totaux[1]>$LT_Totaux[2])
		{
			$LC_Commentaire = "Le budget est n'est pas équilibré." .
							  "Les dépenses représentent " . $LT_Totaux[1] . " euros " . 
							  "et le financement ". $LT_Totaux[2] . " euros.";
		}
	if ($LT_Totaux[1]<$LT_Totaux[2])
		{
			$LC_Commentaire = "Le budget est n'est pas équilibré." .
							  "Les dépenses representent " . $LT_Totaux[1] . " euros " . 
							  "et le financement ". $LT_Totaux[2] . " euros.";		
		}

	// construire l'image
	TraceGrapheHistoEmpiles($LT_Elements, 300, 200, $LC_Image);	
	
	// générer le fichier de retour
	if ($LC_Commentaire!="OK"){$LC_Commentaire = Str_Replace("OK", "", $LC_Commentaire);}
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\" ?> \n";
	$LC_XML .= "<data> \n";
	$LC_XML .= "<retour> \n";	
	$LC_XML .= "<image>" . $LC_Image . "</image> \n";
	$LC_XML .= "<texte>" . utf8_encode($LC_Commentaire) . "</texte> \n";
	$LC_XML .= "</retour> \n";
	$LC_XML .= "</data> \n";
	echo $LC_XML;
}


function AJAX_GrapheEndettement($PC_Param){
Global $LT_Couleur;

	$LC_Image       = "INVESTISSEUR_" . $_SESSION["SCI_ALIAS"] . ".jpg";
	$LC_Image       = Str_Replace(".xml", "", $LC_Image);
	$LC_Commentaire = "";
	$LT_Param       = Explode(";", $PC_Param);
	$LT_Elements    = array();

	// charger les classes	
	$LN_Charges =0;
	$LN_Recettes=0;
	for ($i=0; $i<Count($LT_Param); $i++)
		{
			$LT_SParam = Explode("_", $LT_Param[$i]);
			if (Count($LT_SParam)==3)
				{
					$LT_Elements[] = new LO_ClasseHisto($LT_SParam[2], 
														$LT_Couleur[$i],
														$LT_SParam[1]);
					if ($i==0){$LN_Charges  = $LT_SParam[2];}
					if ($i==1){$LN_Recettes = $LT_SParam[2];}
				}	
		}

	// construire le commentaire
	$LN_Ratio = 0;
	if ($LN_Recettes !=0){$LN_Ratio=Round(100/($LN_Recettes/$LN_Charges), 1);}
	if ($LN_Ratio < 30)
		{
			$LC_Commentaire = "La capacite d endettement est correcte (" . $LN_Ratio . "%.)";		
		}
	else
		{
			if ($LN_Ratio <= 0)
				{
					$LC_Commentaire = "La capacité d endettement est nulle.";
				}
			else
				{
					$LC_Commentaire = "La capacité d endettement est insuffisante (" . $LN_Ratio . "%.)" .
									  " Il faut essayer de diminuer les charges";
				}
		}		
	
	// construire l'image
	TraceGrapheHisto($LT_Elements, 300, 200, $LC_Image);	

	// generer le fichier de retour
	if ($LC_Commentaire!="OK"){$LC_Commentaire = Str_Replace("OK", "", $LC_Commentaire);}
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\" ?> \n";
	$LC_XML .= "<data> \n";
	$LC_XML .= "<retour> \n";	
	$LC_XML .= "<image>" . $LC_Image . "</image> \n";
	$LC_XML .= "<texte>" . utf8_encode($LC_Commentaire) . "</texte> \n";
	$LC_XML .= "</retour> \n";
	$LC_XML .= "</data> \n";
	echo $LC_XML;
}


function AJAX_GrapheEvolution($PC_Param){
Global $LT_Couleur;

	$LC_Image       = "EVOLUTION_" . $_SESSION["SCI_ALIAS"] . ".jpg";
	$LC_Image       = Str_Replace(".xml", "", $LC_Image);
	$LC_Commentaire = "";
	$LT_Param       = Explode(";", $PC_Param);
	$LT_Elements    = array();
	
	// charger les classes	
	for ($i=0; $i<Count($LT_Param); $i++)
		{
			$LT_SParam = Explode("_", $LT_Param[$i]);
			if (Count($LT_SParam)==3)
				{
					$LT_Elements[] = new LO_ClasseLigne($LT_SParam[0], 
														$LT_SParam[2],
														$LT_Couleur[$i],
														$LT_SParam[1]);
				}	
		}

	// construire l'image
	TraceGrapheLigne($LT_Elements, 300, 200, $LC_Image);	
	
	// générer le fichier de retour
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\" ?> \n";
	$LC_XML .= "<data> \n";
	$LC_XML .= "<retour> \n";	
	$LC_XML .= "<image>" . $LC_Image . "</image> \n";
	$LC_XML .= "<texte>" . "</texte> \n";
	$LC_XML .= "</retour> \n";
	$LC_XML .= "</data> \n";
	echo $LC_XML;
}


?>