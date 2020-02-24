<?PHP
Session_start();
require('SCI_EF.inc');
Require("Classe_IAD_MySQL.php");
$LO_IAD  = New Classe_IAD();

// sortie anticipée
//if (TOOL_SessionLire("SESSION", "USER_ID") == ""){return;}

// gestion XML
header("Content-Type:text/xml;charset:utf-8");

	Switch ($_GET["cle"]){
		case "calculFinancement" :
				AJAX_CalculFinancement($_GET["param"], $_GET["paramvaleurs"]);
				break;
		case "calculEndettement" :
				AJAX_CalculEndettement($_GET["param"], $_GET["paramvaleurs"]);
				break;
		case "calculInvestisseur" :
				AJAX_CalculCapaciteEndettement($_GET["param"], $_GET["paramvaleurs"]);
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

function AJAX_CalculFinancement($PC_Param, $PC_ParamValeurs){
Global $LC_Commentaire;
	$LC_Commentaire = "OK";
	$LC_Retour = $PC_Param;
	$LT_Champs = Explode(";", Def_CalculFinancement);
	$LT_Param  = Explode(";", $PC_ParamValeurs);
	$LT_Valeurs= array();
	
	// charger les informations reÃ§ues
	for($i=0;$i<Count($LT_Champs);$i++)
		{
			$LT_Valeurs[$LT_Champs[$i]]=$LT_Param[$i];
		}
	
	// calculs spÃ©cifiques
	if ($LT_Valeurs["ed_PTZ"]=="PTZ")
		{
			AJAX_ControleObligatoire($LT_Valeurs["ed_PTZMontant"], "Le montant de l'eco PTZ doit etre saisi.");
			//AJAX_ControleObligatoire($LT_Valeurs["ed_PTZDuree"], "La durée de l'eco PTZ doit etre saisie.");
			if ($LT_Valeurs["ed_PTZMontant"]>$LT_Valeurs["ed_PrixTravauxDeduc"])
				{
					$LC_Commentaire .= "Le montant de l'eco PTZ ne peut etre supérieur au prix des travaux déductibles.\n";
				}
		}
		
	if ($PC_Param=="ed_PrixAchat"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_Apport"], "Il n'y a aucun financement prévu.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_FraisNotaire"], "Les frais de notaire sont obligatoires.");
		$LT_Valeurs["ed_PrixAchat"]=$LT_Valeurs["ed_Apport"] + 
									$LT_Valeurs["ed_Emprunt"] - (
									$LT_Valeurs["ed_FraisNotaire"] +
									$LT_Valeurs["ed_PrixTravauxNDeduc"] +
									$LT_Valeurs["ed_PrixTravauxDeduc"]);
	}
	if ($PC_Param=="ed_FraisNotaire"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_PrixAchat"], "Le prix d'achat doit être défini");
		$LT_Valeurs["ed_FraisNotaire"]=Round($LT_Valeurs["ed_PrixAchat"] * 10 / 100, 0);
	}	
	if ($PC_Param=="ed_Apport"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_PrixAchat"], "Veuillez indiquer un prix d'achat..");
		AJAX_ControleObligatoire($LT_Valeurs["ed_FraisNotaire"], "Les frais de notaire sont obligatoires.");	
		$LT_Valeurs["ed_Apport"]=($LT_Valeurs["ed_PrixAchat"] + 
								  $LT_Valeurs["ed_FraisNotaire"] +
								  $LT_Valeurs["ed_FraisDivers"] +
								  $LT_Valeurs["ed_PrixTravauxNDeduc"] +
								  $LT_Valeurs["ed_PrixTravauxDeduc"])-$LT_Valeurs["ed_Emprunt"];
	}
	if ($PC_Param=="ed_Emprunt"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_PrixAchat"], "Veuillez indiquer un prix d'achat..");
		AJAX_ControleObligatoire($LT_Valeurs["ed_FraisNotaire"], "Les frais de notaire sont obligatoires.");	
		$LT_Valeurs["ed_Emprunt"]=($LT_Valeurs["ed_PrixAchat"] + 
								   $LT_Valeurs["ed_FraisNotaire"] +
								   $LT_Valeurs["ed_FraisDivers"] +
								   $LT_Valeurs["ed_PrixTravauxNDeduc"] +
								   $LT_Valeurs["ed_PrixTravauxDeduc"])-$LT_Valeurs["ed_Apport"];
		if ($LT_Valeurs["ed_PTZ"]=="PTZ")
		{ 
			$LT_Valeurs["ed_Emprunt"] = $LT_Valeurs["ed_Emprunt"]-$LT_Valeurs["ed_PTZMontant"];
		}
	}							   
	if ($PC_Param=="ed_Mensualite"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_PrixAchat"], "Veuillez indiquer un prix d'achat.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_Duree"], "Veuillez indiquer une durée du prêt.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_TauxNominal"], "Veuillez indiquer un taux d'emprunt.");	
		if ($LC_Commentaire == "OK")
			{
				if ($LT_Valeurs["rd_Pret"] == "PretClassique")
				{
					$LT_Valeurs["ed_Mensualite"]=($LT_Valeurs["ed_Emprunt"]*($LT_Valeurs["ed_TauxNominal"]/1200))/(1-pow(1+($LT_Valeurs["ed_TauxNominal"]/1200),(-1*$LT_Valeurs["ed_Duree"])));
				}
				if ($LT_Valeurs["rd_Pret"] == "PretInFine")
				{
					$LT_Valeurs["ed_Mensualite"]=$LT_Valeurs["ed_Emprunt"]*($LT_Valeurs["ed_TauxNominal"]/1200);
				}
				$LT_Valeurs["ed_Mensualite"]=Round($LT_Valeurs["ed_Mensualite"],2);							
			}	
	}
	if ($PC_Param=="ed_Duree"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_Emprunt"], "Veuillez indiquer un montant à emprunter.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_TauxNominal"], "Veuillez indiquer un taux d'emprunt.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_Mensualite"], "Veuillez indiquer une mensualité.");	
		if ($LC_Commentaire == "OK")
			{
				$LN_CapMensu=0;
				$LN_CapRestantDu=$LT_Valeurs["ed_Emprunt"];
				for ($i=0;$i<=1200;$i++)
					{
					$LN_CapMensu=$LT_Valeurs["ed_Mensualite"]-($LN_CapRestantDu*$LT_Valeurs["ed_TauxNominal"]/1200);
					$LN_CapRestantDu=$LN_CapRestantDu-$LN_CapMensu;
					if ($LN_CapRestantDu<=0) 
						{
							$LT_Valeurs["ed_Duree"] =$i;
							break;
						}	
					}							
			}	
	}
	if ($PC_Param=="ed_Emprunt??"){
		AJAX_ControleObligatoire($LT_Valeurs["ed_Duree"], "Veuillez indiquer une durée du prêt.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_TauxNominal"], "Veuillez indiquer un taux d'emprunt.");
		AJAX_ControleObligatoire($LT_Valeurs["ed_Mensualite"], "Veuillez indiquer une mensualité.");	
		if ($LC_Commentaire == "OK")
			{				
				$LT_Valeurs["ed_Emprunt"]= $LT_Valeurs["ed_Mensualite"]*(1-pow((1+$LT_Valeurs["ed_TauxNominal"]/1200),-1*$LT_Valeurs["ed_Duree"]*12))/($LT_Valeurs["ed_TauxNominal"]/1200)	;							
				$LT_Valeurs["ed_Emprunt"]= round($LT_Valeurs["ed_Emprunt"] ,2);
			}	
	}
	

	
//$LC_Champs = "ed_PrixAchat;ed_FraisNotaire;ed_PrixTravauxNDeduc;ed_PrixTravauxDeduc;" .
//             "ed_Apport;ed_Emprunt;rd_Pret;ed_TauxNominal;ed_TauxAssurance;ed_Duree;ed_Mensualite;" .
			 //"ed_PTZMontant;ed_PTZDuree";
//localhost/sci_ef/sci_ef_ajax.php?cle=calculFinancement&param=ed_PrixAchat&paramvaleurs=90000;8500;6000;0;20000;;PRET_EchClassique;2.5;0;12;1000;50000;5;
			 
	// retour
	if ($LC_Commentaire!="OK"){$LC_Commentaire = Str_Replace("OK", "", $LC_Commentaire);}
	$LT_Retour = Explode(";", $LC_Retour);
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\"?> \n";
	$LC_XML .= "<data> \n";
	for ($i=0;$i<Count($LT_Retour);$i++)
		{
			$LC_XML .= "<objet> \n";	
			$LC_XML .= "<nom>" . $LT_Retour[$i] . "</nom> \n";
			$LC_XML .= "<valeur>" . $LT_Valeurs[$LT_Retour[$i]] . "</valeur> \n";
			$LC_XML .= "</objet> \n";
		}
	$LC_XML .= "<texte>" . utf8_encode($LC_Commentaire) . "</texte> \n";
	$LC_XML .= "</data> \n";
	echo $LC_XML;
}


function AJAX_CalculEndettement($PC_Param, $PC_ParamValeurs){
	$LC_Commentaire = "OK";
	$LC_Retour = $PC_Param;
	$LT_Champs = Explode(";", Def_CalculFinancement);
	$LT_Param  = Explode(";", $PC_ParamValeurs);
	$LT_Valeurs= array();
	
	// charger les informations recues
	for($i=0;$i<Count($LT_Champs);$i++)
		{
			$LT_Valeurs[$LT_Champs[$i]]=$LT_Param[$i];
		}
	
	// calculs specifiques
	if ($PC_Param=="ed_PrixAchat"){}
	$LC_Champs = "";
	// retour
	if ($LC_Commentaire!="OK"){$LC_Commentaire = Str_Replace("OK", "", $LC_Commentaire);}
	$LT_Retour = Explode(";", $LC_Retour);
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\"?> \n";
	$LC_XML .= "<data> \n";
	for ($i=0;$i<Count($LT_Retour);$i++)
		{
			$LC_XML .= "<objet> \n";	
			$LC_XML .= "<nom>" . $LT_Retour[$i] . "</nom> \n";
			$LC_XML .= "<valeur>" . $LT_Valeurs[$LT_Retour[$i]] . "</valeur> \n";
			$LC_XML .= "</objet> \n";
		}
	$LC_XML .= "<texte>" . utf8_encode($LC_Commentaire) . "</texte> \n";
	$LC_XML .= "</data> \n";
	echo $LC_XML;
}


function AJAX_CalculCapaciteEndettement($PC_Param, $PC_ParamValeurs){
	$LC_Commentaire = "OK";
	$LC_Retour = $PC_Param;
	$LT_Champs = Explode(";", Def_CalculInvestisseur);
	$LT_Param  = Explode(";", $PC_ParamValeurs);
	$LT_Valeurs= array();

	// charger les informations recues
	for($i=0;$i<Count($LT_Champs);$i++)
		{
			$LT_Valeurs[$LT_Champs[$i]]=$LT_Param[$i];
		}
	//http://localhost/sci_ef/sci_ef_ajax.php/?cle=calculInvestisseur&param=ed_CapaciteEmprunt&paramvaleurs=300;0;0;3000;4000;
	// calculs specifiques
	if ($PC_Param=="ed_CapaciteEmprunt")
		{
			$LT_Valeurs["ed_MensualiteCapaciteEmprunt"] = 	 0.33*($LT_Valeurs["ed_Salaire"] + $LT_Valeurs["ed_RevenusFonciers"])
								-  ($LT_Valeurs["ed_AutreMensualite"] +  $LT_Valeurs["ed_ChargeResPrincipale"] + $LT_Valeurs["ed_ChargePension"]);
									
			$LT_Valeurs["ed_MensualiteCapaciteEmprunt"] = round($LT_Valeurs["ed_MensualiteCapaciteEmprunt"]/12.2);
			
			$LT_Taux = 1.65;
			
			$LT_Valeurs["ed_CapaciteEmprunt"]= $LT_Valeurs["ed_MensualiteCapaciteEmprunt"]*(1-pow((1+$LT_Taux/1200),-1*20*12))/($LT_Taux/1200);
			$LT_Valeurs["ed_CapaciteEmprunt"]= round($LT_Valeurs["ed_CapaciteEmprunt"] ,0);
		}
	$LC_Champs = "";
	// retour
	if ($LC_Commentaire!="OK"){$LC_Commentaire = Str_Replace("OK", "", $LC_Commentaire);}
	$LT_Retour = Explode(";", $LC_Retour);
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\"?> \n";
	$LC_XML .= "<data> \n";
	for ($i=0;$i<Count($LT_Retour);$i++)
		{
			$LC_XML .= "<objet> \n";	
			$LC_XML .= "<nom>" . $LT_Retour[$i] . "</nom> \n";
			$LC_XML .= "<valeur>" . $LT_Valeurs[$LT_Retour[$i]] . "</valeur> \n";
			$LC_XML .= "</objet> \n";
		}
	$LC_XML .= "<texte>" . utf8_encode($LC_Commentaire) . "</texte> \n";
	$LC_XML .= "</data> \n";
	echo $LC_XML;
}


?>