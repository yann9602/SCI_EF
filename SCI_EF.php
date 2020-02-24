<?PHP
Session_Start();
Require("SCI_EF.inc");
Require("Classe_IAD_MySQL.php");
$LO_IAD  = New Classe_IAD();

	// traiter les méthodes directes
	if (IsSet($_GET["verifid"]))
	{
		LOGIN_Verifie();
		return;
	}
	if (IsSet($_GET["achattickets"]))
	{
		TICKET_Achat($_GET["id"], $_GET["achattickets"]);
		return;
	}

	// traiter le POST des pages
	if (IsSet($_POST["ed_Phase"]))
	{
		$LC_Phase = $_POST["ed_Phase"];
		Switch ($LC_Phase){
			case "ADMIN" :
				ADMIN_Identifie();
				break;
			case "LOGIN" :
				LOGIN_Identifie();
				break;
			case "CreeLOGIN" :
				LOGIN_Creation();
				break;
			case "ModifLOGIN" :
				LOGIN_Modif();
				break;
			case "OubliLOGIN" :
				LOGIN_Oubli();
				break;
			case "EffaceLOGIN" :
				LOGIN_Efface();
				break;
			case "ModifACTU" :
				ACTU_Modif();
				break;
			case "TestREQ" :
				SQL_Exec();
				break;
			case "ENREGISTREXML" :
				if (intVal($_POST["ed_ID"])==0)	
					 {SIMULATION_Insert();}
				else {SIMULATION_Update();}	
				break;
			case "ENREGISTREDEMO" :
				SIMULATION_EnregistreXML("Data/Demonstrations/" . $_POST["ed_Alias"]);
				break;
			case "EFFACEXML" :
				SIMULATION_Efface();
				break;
			case "ENREGISTREDOSSIER" :
				if (intVal($_POST["ed_ID"])==0)
					 {INVESTISSEUR_Insert();}
				else {INVESTISSEUR_Update();}	
				break;
			case "EFFACEDOSSIER" :
				INVESTISSEUR_Efface();
				break;
		}	
	}
	//Echo "Erreur  --".$_POST["ed_Phase"]. "---";
//--------------------------------------------------------------------------------------
function ADMIN_Identifie()
{
	if ($_POST["ed_PW"] =="jbmd")
		{
			$_SESSION["ADMIN_OK"] = "00";		
			TOOL_Chainage("SCI_EF_index.php");
			return;
		}

}


function LOGIN_Identifie()
{
Global $LO_IAD;
	// login admin
	if (($_POST["ed_Mail"] == "admin") && 
	    ($_POST["ed_PW"] == "jbmd"))
		{
			$_SESSION["USER_ID"]     = "00";
			$_SESSION["USER_MODE"]   = "ADMIN";
			$_SESSION["USER_TYPE"]   = "";
			$_SESSION["USER_STATUT"] = "";
			$_SESSION["SCI_Message"] = utf8_encode("Identifié en mode administrateur");
			TOOL_Chainage("SCI_EF_Index.php?page=admin");
			return;
		}

	// login classique
	$LC_SQL  = "Select LOG_ID, LOG_Statut, LOG_Type From sci_login ";
	$LC_SQL .= "Where LOG_Mail = '" . $_POST["ed_Mail"] . "' ";
	$LC_SQL .= "  And LOG_PW = '" . $_POST["ed_PW"] . "' ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	if ($LO_IAD->NombreLignes() == 0)
	{
		// les informations d'identification sont fausses
		$_SESSION["SCI_Message"] = utf8_encode("Identification refusée");
		TOOL_Chainage("SCI_EF_Index.php?page=login");
		return;
	}
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
	if ($LT_Ligne["LOG_Statut"] == "X")
	{
		// le login n'est pas vérifié
		$_SESSION["SCI_Message"] = utf8_encode("Vous devez d'abord traiter le mail de confirmation qui vous a été envoyé");
		TOOL_Chainage("SCI_EF_Index.php?page=login");
		return;
	}
	if ($LT_Ligne["LOG_Statut"] == "Y")
	{
		// le login a été mis en indésirable
		TOOL_Chainage("Google.fr");
		return;
	}
	// tout va bien
	$_SESSION["USER_STATUT"] = $LT_Ligne["LOG_Statut"];
	$_SESSION["USER_MODE"]   = "USER";
	$_SESSION["USER_ID"]     = $LT_Ligne["LOG_ID"];
	$_SESSION["USER_TYPE"]   = $LT_Ligne["LOG_Type"];
	$_SESSION["SCI_Message"] = "";
	if (isSet($_POST["cb_Login"]))
		{
			// créer les cookies
			setcookie("SCIEF_Login", $_POST["ed_Mail"], time()+ (60 * 60 * 24 * 10)); // nombre de secondes
			setcookie("SCIEF_PW",    $_POST["ed_PW"],   time()+ (60 * 60 * 24 * 10)); // nombre de secondes
		}
	else
		{
			// effacer les cookies
			setcookie("SCIEF_Login", "", 0); // on tue
			setcookie("SCIEF_PW",    "", 0); // on tue
		}
	// préparer le fichier de memorisation
	$LC_SQL = "Select Count(*) as nb from sci_memoire where LOG_ID = ";
	$LC_SQL = $LC_SQL . TOOL_SessionLire("SESSION", "USER_ID");
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
	if ($LT_Ligne["nb"] == 0)
	{
		$LC_SQL  = "Insert into sci_memoire ";
		$LC_SQL .= "(MEM_Page, MEM_Option, LOG_ID, INV_ID, SIM_ID)";
		$LC_SQL .= "values (";
		$LC_SQL .= "'ecr_Liste.php', ";
		$LC_SQL .= "null, ";
		$LC_SQL .= "0" . TOOL_SessionLire("SESSION", "USER_ID") . ", ";
		$LC_SQL .= "null, ";
		$LC_SQL .= "null) ";
		$LO_IAD->ExecuteSQL($LC_SQL);
	}
	TOOL_Chainage("SCI_EF_Index.php?page=liste");
	return;
}


function LOGIN_Creation()
{
Global $LO_IAD;

	// chercher l'existant
	$LC_SQL  = "Select LOG_ID, LOG_Statut From sci_login ";
	$LC_SQL .= "Where LOG_Mail = '" . $_POST["ed_MailLogin"] . "' ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	if ($LO_IAD->NombreLignes() > 0)
		{
			$_SESSION["SCI_Message"] = utf8_encode("Cet identifiant existe déjà");
			TOOL_Chainage("SCI_EF_Index.php?page=login");
			return;
		}
	// insérer
	$LC_SQL  =" insert into sci_login ";
	$LC_SQL .="(LOG_PW, LOG_Mail, LOG_Statut, LOG_Type) ";
	$LC_SQL .="values (";
	$LC_SQL .= "'" . $_POST["ed_CreePW"] . "', ";
	$LC_SQL .= "'" . $_POST["ed_MailLogin"] . "', ";
	$LC_SQL .= "'X', ";
	$LC_SQL .= "'" . $_POST["ed_TypeLogin"] . "') ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	// poster un mail
	$LC_Lien    = Def_URL . "/sci_ef.php?verifid=" . TOOL_Brouillage() . $LO_IAD->PN_ID . TOOL_Brouillage();
	$LC_Message = $LO_IAD->AfficheTexte("INSCACC");
	$LC_Message = Str_Replace("~lien~", $LC_Lien, $LC_Message);
	if (mail(StripSlashes($_POST['ed_MailLogin']), 
		"Ouverture login", 
		$LC_Message, 
		"From: ".StripSlashes(Def_MailEmetteur)))
	{	
		$_SESSION["SCI_Message"] = utf8_encode("Votre identifiant a bien été créé. un mail vient de vous être posté");
	} else
	{
		$_SESSION["SCI_Message"] = utf8_encode("Le mail n'a pas pu être envoyé");
	}
	// chainer
	TOOL_Chainage("SCI_EF_Index.php?page=login");
}

function LOGIN_Verifie()
{
Global $LO_IAD;
	if (StrLen($_GET["verifid"]) < 8)
	{
		$_SESSION["SCI_Message"] = utf8_encode("Votre identifiant de confirmation est invalide");
		TOOL_Chainage("SCI_EF_Index.php?page=login");
		return;
	}
	$LC_SQL  = "update sci_login Set ";
	$LC_SQL .= "LOG_Statut = '' ";
	$LC_SQL .= "Where LOG_ID = " . Substr($_GET["verifid"], 3, 2);
	$LO_IAD->ExecuteSQL($LC_SQL);
	// chainer
	$_SESSION["SCI_Message"] = utf8_encode("Votre identifiant est maintenant opérationnel");
	TOOL_Chainage("SCI_EF_Index.php?page=login");
}

function LOGIN_Modif()
{
Global $LO_IAD;

	if (IsSet($_SESSION["USER_ID"])==false)
	{
		$_SESSION["SCI_Message"] = utf8_encode("Vous devez être identifié pour modifier votre profil");
		TOOL_Chainage("SCI_EF_Index.php?page=login");
		return;
	}
	$LC_SQL  ="update sci_login Set ";
	$LC_SQL .= "LOG_PW = '" . $_POST["ed_ModifPW"] . "', ";
	$LC_SQL .= "LOG_Mail = '" . $_POST["ed_ModifLogin"] . "', ";
	$LC_SQL .= "LOG_Type = '" . $_POST["ed_ModifTypeLogin"] . "' ";
	$LC_SQL .= "where LOG_ID = 0" . $_SESSION["USER_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);
	$_SESSION["SCI_Message"] = utf8_encode("Vos informations ont bien été modifiées");
	unSet($_GET["page"]);
	unSet($_SESSION["USER_STATUT"]);
	unSet($_SESSION["USER_MODE"]);
	unSet($_SESSION["USER_ID"]);
	unSet($_SESSION["USER_TYPE"]);
	TOOL_Chainage("SCI_EF_Index.php");
	return;
}


function LOGIN_Efface()
{
Global $LO_IAD;
	// déclarer les tableaux de XML a effacer
	$LT_AliasSIM = array();
	$LT_AliasEMP = array();
	// recencer les emprunteurs liés à ce Login
	$LC_ListeID = "";
	$LC_SQL  = "Select sci_emprunteurs.EMP_ID, sci_emprunteurs.EMP_Alias, ";
	$LC_SQL .= "SIM_Alias ";
	$LC_SQL .= "from sci_emprunteurs, sci_simulation ";
	$LC_SQL .= "where LOG_ID = 0" .  $_SESSION["USER_ID"] . " ";
	$LC_SQL .= "and sci_emprunteurs.EMP_ID = sci_simulation.EMP_ID ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
	{
		$LT_Ligne = $LO_IAD->EclateSQL("Noms");
		$LC_ListeID .= $LT_Ligne["EMP_ID"];
		$LC_ListeID .= ",";
		$LT_AliasSIM[] = $LT_Ligne["SIM_Alias"];
		$LT_AliasEMP[] = $LT_Ligne["EMP_Alias"];
	}
	// effacer les simulations
	$LC_SQL  = "Delete from sci_simulation ";
	$LC_SQL .= "where EMP_ID IN (" . $LC_ListeID . "0) ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	// effacer les emprunteurs
	$LC_SQL  = "Delete from sci_emprunteurs ";
	$LC_SQL .= "where EMP_ID IN (" . $LC_ListeID . "0) ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	// virer les XML
	for ($i=0;$i<Count($LT_AliasSIM);$i++)
	{
		$LC_XML  = "Data/XML/" . $LT_AliasSIM[$i];
		if (file_exists($LC_XML)){unLink($LC_XML);}
	}
	for ($i=0;$i<Count($LT_AliasEMP);$i++)
	{
		$LC_XML  = "Data/XML/" . $LT_AliasEMP[$i];
		if (file_exists($LC_XML)){unLink($LC_XML);}
	}
	// virer l'environnement
	unSet($_GET["page"]);
	unSet($_SESSION["USER_STATUT"]);
	unSet($_SESSION["USER_MODE"]);
	unSet($_SESSION["USER_ID"]);
	unSet($_SESSION["USER_TYPE"]);
	TOOL_Chainage("SCI_EF_Index.php");
	return;
}


function LOGIN_Oubli()
{
Global $LO_IAD;

	// chercher l'existant
	$LC_SQL  = "Select LOG_ID, LOG_Statut, LOG_PW, LOG_Mail  From sci_login ";
	$LC_SQL .= "Where LOG_Mail = '" . $_POST["ed_OubliLogin"] . "' ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	if ($LO_IAD->NombreLignes() == 0)
		{
			$_SESSION["SCI_Message"] = utf8_encode("Cet identifiant est inconnu");
			TOOL_Chainage("SCI_EF_Index.php?page=login");
			return;
		}
	// poster un mail
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
	$LC_Message = $LO_IAD->AfficheTexte("OUBLIPW");
	$LC_Message = Str_Replace("~login~", $LT_Ligne["LOG_Mail"], $LC_Message);
	$LC_Message = Str_Replace("~pw~", $LT_Ligne["LOG_PW"], $LC_Message);
	if (mail(StripSlashes($LT_Ligne["LOG_Mail"]), 
		"Ientifiants oubliés", 
		$LC_Message, 
		"From: ".StripSlashes(Def_MailEmetteur)))
	{	
		$_SESSION["SCI_Message"] = utf8_encode("Vos informations de connexion viennent de vous être envoyées par mail");
	} else
	{
		$_SESSION["SCI_Message"] = utf8_encode("Le mail n'a pas pu être envoyé");
	}
	// chainer
	TOOL_Chainage("SCI_EF_Index.php?page=login");
	return;
}


function INVESTISSEUR_Update()
{
Global $LO_IAD;
	// calculer l'alias
	$LC_Alias = "INV_" .
				str_pad($_SESSION["EMP_ID"], 5, "0", STR_PAD_LEFT) . 
				".xml";
	// requete SQL
	$LN_Charges=$_POST["ed_AutreMensualite"] + $_POST["ed_ChargeResPrincipale"] + $_POST["ed_ChargePension"];
	$LN_Recettes=$_POST["ed_Salaire"] + $_POST["ed_RevenusFonciers"] + $_POST["ed_RevenusFonciers"];
	$LC_SQL  = "update sci_emprunteurs set ";
	$LC_SQL .= "EMP_Nom=" . TOOL_EncodeSQL("C", $_POST["ed_InvestisseurNom"]) . ", ";
	$LC_SQL .= "EMP_Prenom=" .  TOOL_EncodeSQL("C", $_POST["ed_InvestisseurPrenom"]) . ", ";
	$LC_SQL .= "EMP_DateNaissance=" . TOOL_EncodeSQL("C", $_POST["ed_InvestisseurDateNaissance"]) . ", ";
	$LC_SQL .= "EMP_Alias='" . $LC_Alias ."', ";
	$LC_SQL .= "EMP_Recettes=" . $LN_Recettes . ", ";
	$LC_SQL .= "EMP_Charges=" . $LN_Charges . ", ";
	$LC_SQL .= "EMP_CapaciteEmprunt=0" . $_POST["ed_CapaciteEmprunt"] . " ";
	$LC_SQL .= "where EMP_ID=" . $_POST["ed_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);	
	INVESTISSEUR_EnregistreXML("Data/XML/" . $LC_Alias);	
	// message de prise en compte
	$_SESSION["SCI_Message"] = utf8_encode("L'investisseur a été modifié");
	// chainer vers la page de saisie
	TOOL_Chainage("SCI_EF_Index.php?page=investisseur");	
	return;
}


function INVESTISSEUR_Insert()
{
Global $LO_IAD;

	// créer l'emprunteur
	$LN_Charges=$_POST["ed_AutreMensualite"] + $_POST["ed_ChargeResPrincipale"] + $_POST["ed_ChargePension"];
	$LN_Recettes=$_POST["ed_Salaire"] + $_POST["ed_RevenusFonciers"] + $_POST["ed_RevenusFonciers"];	
	$LC_SQL  = "insert into sci_emprunteurs ";
	$LC_SQL .= "(LOG_ID, EMP_Nom, EMP_Prenom, EMP_Charges, EMP_Recettes, EMP_CapaciteEmprunt, ";
	$LC_SQL .= "EMP_SeuilsTIPPtranche, EMP_SeuilsTIPPtaux, ";
	$LC_SQL .= "EMP_SeuilsTISCItranche, EMP_SeuilsTISCItaux, ";
	$LC_SQL .= "EMP_SeuilsTIASStranche, EMP_SeuilsTIASStaux) ";
	$LC_SQL .= " values (";
	$LC_SQL .= $_SESSION["USER_ID"] . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_InvestisseurNom"]) . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_InvestisseurPrenom"]) . ", ";
	$LC_SQL .= $LN_Charges . ", ";
	$LC_SQL .= $LN_Recettes . ", ";
	$LC_SQL .= "0" . $_POST["ed_CapaciteEmprunt"] . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_TIPPtranche"]) . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_TIPPtaux"]) . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_TISCItranche"]) . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_TISCItaux"]) . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_TIASStranche"]) . ", ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_TIASStaux"]) . ") ";
	$LO_IAD->ExecuteSQL($LC_SQL);	
	$_SESSION["EMP_ID"] = $LO_IAD->PN_ID;
	// calculer l'alias et actualiser la page XML
	$LC_Alias = "INV_" .
	            str_pad($_SESSION["EMP_ID"], 5, "0", STR_PAD_LEFT) . 
				".xml";
	INVESTISSEUR_EnregistreXML("Data/XML/" . $LC_Alias);								
	// affecter l'alias à l'investisseur
	$LC_SQL  = "update sci_emprunteurs ";	
	$LC_SQL .= "set EMP_Alias = '" . $LC_Alias . "' ";
	$LC_SQL .= "where EMP_ID = " . $_SESSION["EMP_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);	
				
	// créer la premièrere simulation
	$LC_SQL  = "insert into sci_simulation ";
	$LC_SQL .= "(EMP_ID, SIM_Libelle) ";
	$LC_SQL .= " values (";
	$LC_SQL .= $_SESSION["EMP_ID"] . ", ";
	$LC_SQL .= "'Simulation' )";
	$LO_IAD->ExecuteSQL($LC_SQL);	
	$_SESSION["SIM_ID"] = $LO_IAD->PN_ID;	
	// message de prise en compte
	$_SESSION["SCI_Message"] = utf8_encode("L'investisseur a été créé");	
	// chainer vers la page de saisie
	$LC_URL = "SCI_EF_Index.php?" . 
				"page=simulation" . 
				"&id=" . $_SESSION["SIM_ID"] .
				"&invid=" . $_SESSION["EMP_ID"];
	TOOL_Chainage($LC_URL);
	return;
}


function INVESTISSEUR_Efface()
{
Global $LO_IAD;
	//effacer les fichier
	$LC_SQL  = "SELECT SIM_Alias ";
	$LC_SQL .= "from sci_simulation ";
	$LC_SQL .= "where EMP_ID = " . $_POST["ed_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			$LC_XML  = "Data/XML/" . $LT_Ligne["SIM_Alias"];
			if (file_exists($LC_XML)){unLink($LC_XML);}
		}	
	// effacer les simulations
	$LC_SQL  = "Delete from sci_simulation ";
	$LC_SQL .= "where EMP_ID = " . $_POST["ed_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);
	// effacer 
	$LC_SQL  = "Delete from sci_emprunteurs ";
	$LC_SQL .= "where EMP_ID = " . $_POST["ed_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);
	// message de prise en compte
	$_SESSION["SCI_Message"] = utf8_encode("L'investisseur a été effacé");
	// afficher la liste
	TOOL_Chainage("SCI_EF_Index.php?page=liste");
	return;
}


function INVESTISSEUR_EnregistreXML($PC_XML)
{
	// créer l'instance XML
	$LO_XML = new SimpleXMLElement("<root></root>");

	// créer le noeud Investisseur
	$LO_NoeudClient = $LO_XML->addChild("investisseur"); 
	$LO_NoeudClient->addChild("nom", $_POST["ed_InvestisseurNom"]);
	$LO_NoeudClient->addChild("prenom", $_POST["ed_InvestisseurPrenom"]);
	// investisseur ressources
	$LO_NoeudRessource = $LO_NoeudClient->addChild("ressources"); 
	$LO_NoeudRessource->addChild("salaire", $_POST["ed_Salaire"]);
	$LO_NoeudRessource->addChild("foncier", $_POST["ed_RevenusFonciers"]);
	// investisseur charges
	$LO_NoeudCharges = $LO_NoeudClient->addChild("charges"); 
	$LO_NoeudCharges->addChild("capacite", $_POST["ed_CapaciteEmprunt"]);
	$LO_NoeudCharges->addChild("autres", $_POST["ed_AutreMensualite"]);
	$LO_NoeudCharges->addChild("residenceprincipale", $_POST["ed_ChargeResPrincipale"]);
	$LO_NoeudCharges->addChild("pensions", $_POST["ed_ChargePension"]);
	// investisseur fiscalite
	$LO_NoeudFiscalite = $LO_NoeudClient->addChild("fiscalite"); 
	$LO_NoeudParts = $LO_NoeudFiscalite->addChild("partsfiscales"); 	
	for ($i=1; $i<=5; $i++)
		{
			$LO_NoeudPeriode = $LO_NoeudParts->addChild("periode");
			$LO_NoeudPeriode->addChild("parts", $_POST["ed_FiscNbPart" . $i]);
			$LO_NoeudPeriode->addChild("delai", $_POST["ed_FiscDelai" . $i]);
		}
	$LO_NoeudFiscalitephy = $LO_NoeudFiscalite->addChild("PPHYSIQUE"); 
	$LT_TItranche = Explode(";", $_POST["ed_TIPPtranche"]);
	$LT_TItaux    = Explode(";", $_POST["ed_TIPPtaux"]);
	for ($i=1; $i<=4; $i++)
		{
			$LO_NoeudFiscalitephy->addChild("TIPPtranche" . $i, $LT_TItranche[$i]);
			$LO_NoeudFiscalitephy->addChild("TIPPtaux" . $i, $LT_TItaux[$i]);
		}
	$LO_NoeudFiscalitesci = $LO_NoeudFiscalite->addChild("SCI");
	$LT_TItranche = Explode(";", $_POST["ed_TISCItranche"]);
	$LT_TItaux    = Explode(";", $_POST["ed_TISCItaux"]);
	for ($i=1; $i<=2; $i++)
		{
			$LO_NoeudFiscalitesci->addChild("TISCItranche" . $i, $LT_TItranche[$i]);
			$LO_NoeudFiscalitesci->addChild("TISCItaux" . $i, $LT_TItaux[$i]);
		}
	$LT_TItranche = Explode(";", $_POST["ed_TIASStranche"]);
	$LT_TItaux    = Explode(";", $_POST["ed_TIASStaux"]);
	for ($i=1; $i<=4; $i++)
		{
			$LO_NoeudFiscalitesci->addChild("TIASStranche" . $i, $LT_TItranche[$i]);
			$LO_NoeudFiscalitesci->addChild("TIASStaux" . $i, $LT_TItaux[$i]);
		}
	
	$LO_XML->asXML($PC_XML);
}


function SIMULATION_Efface()
{
Global $LO_IAD;
	// gérer la base de données
	$LC_SQL  = "Delete From sci_simulation ";
	$LC_SQL .= "where SIM_Alias='" . $_POST["ed_Alias"] . "' ";	
	$LO_IAD->ExecuteSQL($LC_SQL);		
	// supprimer le fichier XML
	$LC_XML  = "Data/XML/" . $_POST["ed_Alias"];
	if (file_exists($LC_XML)){unLink($LC_XML);}
	// message de prise en compte
	$_SESSION["SCI_Message"] = utf8_encode("La simulation a été effacée");	
	// afficher la liste
	TOOL_Chainage("SCI_EF_Index.php?page=liste");
	return;
}


function SIMULATION_Insert()
{
Global $LO_IAD;
	// en isertion
	$LC_SQL  = "Insert into sci_simulation ";
	$LC_SQL .= "(EMP_ID, SIM_Alias, SIM_Libelle, SIM_Date) ";
	$LC_SQL .= "values (";
	$LC_SQL .= $_SESSION["EMP_ID"] . ", ";
	$LC_SQL .= "'xx', ";
	$LC_SQL .= TOOL_EncodeSQL("C", $_POST["ed_EtudeNom"]) . ", ";
	$LC_SQL .= "'" . Date("Ymd") . "') ";

	$LO_IAD->ExecuteSQL($LC_SQL);		
	$_SESSION["SIM_ID"] = $LO_IAD->PN_ID;	
	// calculer l'alias
	$LC_Alias = "SIM_" .
				str_pad($_SESSION["EMP_ID"], 5, "0", STR_PAD_LEFT) . 
				"_" .
				str_pad($_SESSION["SIM_ID"], 5, "0", STR_PAD_LEFT) . 
				".xml";
	// calculer le nom de photo				
	$LC_Photo= "PHOTO_" .
				str_pad($_SESSION["EMP_ID"], 5, "0", STR_PAD_LEFT) . 
				"_" .			
				str_pad($_SESSION["SIM_ID"], 5, "0", STR_PAD_LEFT) . 
				".jpg";
	$LC_Message = FICHIER_Upload("ed_PhotoBien", $LC_Photo);
	if ($LC_Message!="OK")
		{
			$LC_Photo="";
		}

	// affecter l'alias
	$LC_SQL  = "update sci_simulation ";	
	$LC_SQL .= "set SIM_Alias = '" . $LC_Alias . "', ";
	$LC_SQL .= "SIM_Photo = '" . $LC_Photo . "' ";
	$LC_SQL .= "where SIM_ID = " . $_SESSION["SIM_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);	
	// message de prise en compte
	$_SESSION["SCI_Message"] = utf8_encode("La simulation a été ajoutéee");	
	// afficher la liste
	$_SESSION["SCI_Message"] = utf8_encode("Les informations sont prises en compte");
	// actualiser les donnees
	SIMULATION_EnregistreXML("Data/XML/" . $LC_Alias);
	// chainer
	$LC_URL = "SCI_EF_Index.php?" . 
			"page=simulation" . 
			"&id=" . $_SESSION["SIM_ID"] .
			"&invid=" . $_SESSION["EMP_ID"];	
	TOOL_Chainage($LC_URL);
}


function SIMULATION_Update()
{
Global $LO_IAD;
	// traiter la photo
	$LC_Photo= "PHOTO_" .
				str_pad($_SESSION["EMP_ID"], 5, "0", STR_PAD_LEFT) . 
				"_" .			
				str_pad($_SESSION["SIM_ID"], 5, "0", STR_PAD_LEFT) . 
				".jpg";
	$LC_Message = FICHIER_Upload("ed_PhotoBien", $LC_Photo);
	if ($LC_Message!="OK")
		{
			$LC_Photo="";
		}
	// recalculer l'alias
	$LC_Alias = "SIM_" .
				str_pad($_SESSION["EMP_ID"], 5, "0", STR_PAD_LEFT) . 
				"_" .
				str_pad($_SESSION["SIM_ID"], 5, "0", STR_PAD_LEFT) . 
				".xml";
	//nom du fichier XML
	$LC_XML  = "Data/XML/" . $LC_Alias;
	$LC_SQL  = "update sci_simulation Set ";
	$LC_SQL .= "SIM_Libelle=" . TOOL_EncodeSQL("C", $_POST["ed_EtudeNom"]) . ", ";
	$LC_SQL .= "SIM_Alias = " . TOOL_EncodeSQL("C", $LC_Alias) . ", ";
	if ($LC_Photo  != ""){$LC_SQL .= "SIM_Photo= '" . $LC_Photo . "', ";}
	$LC_SQL .= "SIM_Date = '" . Date("Ymd") . "' ";
	$LC_SQL .= "where SIM_ID=" . $_SESSION["SIM_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);	
	// ecrire le fichier de données
	SIMULATION_EnregistreXML("Data/XML/" . $LC_Alias);
	// afficher la liste
	$_SESSION["SCI_Message"] = "Les informations sont prises en compte";
	// chainer
	$LC_URL = "SCI_EF_Index.php?" . 
			"page=simulation" . 
			"&id=" . $_POST["ed_ID"] .
			"&invid=" . $_SESSION["EMP_ID"];
	TOOL_Chainage($LC_URL);
}


function SIMULATION_EnregistreXML($PC_XML)
{
	// créer l'instance XML
	$LO_XML = new SimpleXMLElement("<root></root>");

	// créer le noeud client
	$LO_NoeudClient = $LO_XML->addChild("client"); 
	$LO_NoeudClient->addChild("nom", $_POST["ed_InvestisseurNom"]);

	// créer le noeud Investisseur
	$LO_NoeudClient = $LO_XML->addChild("investisseur"); 
	$LO_NoeudClient->addChild("nom", $_POST["ed_InvestisseurNom"]);
	$LO_NoeudClient->addChild("prenom", $_POST["ed_InvestisseurPrenom"]);

	// investisseur ressources
	$LO_NoeudRessource = $LO_NoeudClient->addChild("ressources"); 
	$LO_NoeudRessource->addChild("salaire", $_POST["ed_Salaire"]);
	$LO_NoeudRessource->addChild("foncier", $_POST["ed_RevenusFonciers"]);
	$LO_NoeudRessource->addChild("evolrevenus", $_POST["ed_EvolRevenus"]);
	// investisseur charges
	$LO_NoeudCharges = $LO_NoeudClient->addChild("charges"); 
	$LO_NoeudCharges->addChild("capacite", $_POST["ed_CapaciteEmprunt"]);
	$LO_NoeudCharges->addChild("autres", $_POST["ed_AutreMensualite"]);
	$LO_NoeudCharges->addChild("residenceprincipale", $_POST["ed_ChargeResPrincipale"]);
	$LO_NoeudCharges->addChild("pensions", $_POST["ed_ChargePension"]);
	// investisseur fiscalite
	$LO_NoeudFiscalite = $LO_NoeudClient->addChild("fiscalite"); 
	$LO_NoeudParts = $LO_NoeudFiscalite->addChild("partsfiscales"); 
	// investisseur fiscalite
	for ($i=1; $i<=5; $i++)
		{
			$LO_NoeudPeriode = $LO_NoeudParts->addChild("periode");
			$LO_NoeudPeriode->addChild("parts", $_POST["ed_FiscNbPart" . $i]);
			$LO_NoeudPeriode->addChild("delai", $_POST["ed_FiscDelai" . $i]);
		}
	$LO_NoeudFiscalite->addChild("typefiscalite", $_POST["ed_TypeFiscalite"]);
	$LO_NoeudFiscalitephy = $LO_NoeudFiscalite->addChild("PPHYSIQUE"); 
	$LO_NoeudFiscalitephy->addChild("taxefonciere", $_POST["ed_PhyMontantTF"]);
	$LO_NoeudFiscalitephy->addChild("evoltaxefonciere", $_POST["ed_PhyEvolTF"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtranche1", $_POST["ed_TIPP_Tranche1"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtaux1", $_POST["ed_TIPP_Taux1"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtranche2", $_POST["ed_TIPP_Tranche2"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtaux2", $_POST["ed_TIPP_Taux2"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtranche3", $_POST["ed_TIPP_Tranche3"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtaux3", $_POST["ed_TIPP_Taux3"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtranche4", $_POST["ed_TIPP_Tranche4"]);
	$LO_NoeudFiscalitephy->addChild("TIPPtaux4", $_POST["ed_TIPP_Taux4"]);
	$LO_NoeudFiscalitephy->addChild("cotisations", $_POST["ed_PhyCotisations"]);
	$LO_NoeudFiscalitephy->addChild("mfplafond", $_POST["ed_MfPlafond"]);
	$LO_NoeudFiscalitephy->addChild("mfabattement", $_POST["ed_MfAbattement"]);
	$LO_NoeudFiscalitephy->addChild("lmnpplafond", $_POST["ed_LMNPPlafond"]);
	$LO_NoeudFiscalitephy->addChild("lmnpabattement", $_POST["ed_LMNPAbattement"]);
	$LO_NoeudFiscalitephy->addChild("amortimm", $_POST["ed_LMNPAmortimm"]);
	$LO_NoeudFiscalitephy->addChild("amortmob", $_POST["ed_LMNPAmortmob"]);
	$LO_NoeudFiscalitesci = $LO_NoeudFiscalite->addChild("SCI"); 	
	$LO_NoeudFiscalitesci->addChild("taxefonciere", $_POST["ed_SciMontantTF"]);
	$LO_NoeudFiscalitesci->addChild("evoltaxefonciere", $_POST["ed_SciEvolTF"]);
	$LO_NoeudFiscalitesci->addChild("TISCItranche1", $_POST["ed_TISCI_Tranche1"]);
	$LO_NoeudFiscalitesci->addChild("TISCItaux1", $_POST["ed_TISCI_Taux1"]);
	$LO_NoeudFiscalitesci->addChild("TISCItranche2", $_POST["ed_TISCI_Tranche2"]);
	$LO_NoeudFiscalitesci->addChild("TISCItaux2", $_POST["ed_TISCI_Taux2"]);
	$LO_NoeudFiscalitesci->addChild("amortimm", $_POST["ed_SciAmortImm"]);
	$LO_NoeudFiscalitesci->addChild("amortmob", $_POST["ed_SciAmortMob"]);
	$LO_NoeudFiscalitesci->addChild("dividendesverses", $_POST["ed_SciDividendesVerses"]);
	$LO_NoeudFiscalitesci->addChild("dividendesimposes", $_POST["ed_SciDividendesImposes"]);
	$LO_NoeudFiscalitesci->addChild("dividendessuite", $_POST["ed_SciDividendesSuite"]);
	$LO_NoeudFiscalitesci->addChild("TIASStranche1", $_POST["ed_TIASS_Tranche1"]);
	$LO_NoeudFiscalitesci->addChild("TIASStaux1", $_POST["ed_TIASS_Taux1"]);
	$LO_NoeudFiscalitesci->addChild("TIASStranche2", $_POST["ed_TIASS_Tranche2"]);
	$LO_NoeudFiscalitesci->addChild("TIASStaux2", $_POST["ed_TIASS_Taux2"]);
	$LO_NoeudFiscalitesci->addChild("TIASStranche3", $_POST["ed_TIASS_Tranche3"]);
	$LO_NoeudFiscalitesci->addChild("TIASStaux3", $_POST["ed_TIASS_Taux3"]);
	$LO_NoeudFiscalitesci->addChild("TIASStranche4", $_POST["ed_TIASS_Tranche4"]);
	$LO_NoeudFiscalitesci->addChild("TIASStaux4", $_POST["ed_TIASS_Taux4"]);
	$LO_NoeudFiscalitesci->addChild("tauxcotsociale", $_POST["ed_TauxCotSociale"]);

	// créer le noeud détail de l'étude
	$LO_NoeudSimulation = $LO_XML->addChild("simulation"); 	
	$LO_NoeudSimulation->addChild("nom", $_POST["ed_EtudeNom"]);
	$LO_NoeudSimulation->addChild("datedebut", $_POST["ed_EtudeDebut"]);
	$LO_NoeudSimulation->addChild("duree", $_POST["ed_EtudeDuree"]);

	// simulation travaux
	$LO_NoeudTravaux = $LO_NoeudSimulation->addChild("travaux");
	$LO_NoeudTravaux->addChild("delai", $_POST["cb_DelaiTravaux"]);
	for ($i=1;$i<15;$i++){
		$LO_NoeudDetail = $LO_NoeudTravaux->addChild("detail");
		$LO_NoeudDetail->addChild("libelle", $_POST["ed_TRAVAUX_Lib" . $i]);
		$LO_NoeudDetail->addChild("delai", $_POST["ed_TRAVAUX_Delai" . $i]);
		$LO_NoeudDetail->addChild("montantdeductible", $_POST["ed_TRAVAUX_Deductible" . $i]);
		$LO_NoeudDetail->addChild("montantnondeductible", $_POST["ed_TRAVAUX_NonDeductible" . $i]);
		$LO_NoeudDetail->addChild("partdeductible", $_POST["ed_TRAVAUX_PartDeductible" . $i]);
		$LO_NoeudDetail->addChild("partnondeductible", $_POST["ed_TRAVAUX_PartNonDeductible" . $i]);
		$LO_NoeudDetail->addChild("meuble", $_POST["ed_TRAVAUX_Mobilier" . $i]);
		$LO_NoeudDetail->addChild("total", $_POST["ed_TRAVAUX_Global" . $i]);
	}
	
	// simulation descriptif
	$LO_NoeudDescriptifs = $LO_NoeudSimulation->addChild("descriptifs");
	$LC_Biens = $_POST["ed_BlocBiens"];
	$LT_Biens = Explode("°", $LC_Biens);
	For ($i=0; $i<Count($LT_Biens); $i++)
	{
		$LT_LigneBien = Explode("~", $LT_Biens[$i]);
		if (Count($LT_LigneBien) > 2)
		{
			$LO_NoeudDescriptif = $LO_NoeudDescriptifs->addChild("descriptif");
			$LO_NoeudDescriptif->addChild("libelle", $LT_LigneBien[1]);	
			$LO_NoeudDescriptif->addChild("surface", $LT_LigneBien[2]);	
			$LO_NoeudDescriptif->addChild("prixmetre", $LT_LigneBien[3]);		
			$LO_NoeudDescriptif->addChild("valeurlocative", $LT_LigneBien[4]);		
			$LO_NoeudDescriptif->addChild("loyerdecale", $LT_LigneBien[5]);		
			$LO_NoeudDescriptif->addChild("dispfiscalnom", $LT_LigneBien[6]);		
			$LO_NoeudDescriptif->addChild("dispfiscalduree", $LT_LigneBien[7]);		
			$LO_NoeudDescriptif->addChild("dispfiscaldeduc", $LT_LigneBien[8]);		
			$LO_NoeudDescriptif->addChild("dispfiscal", $LT_LigneBien[9]);		
			$LO_NoeudDescriptif->addChild("dispfiscaleligible", $LT_LigneBien[10]);		
			$LO_NoeudDescriptif->addChild("dispfiscallmnp", $LT_LigneBien[11]);	
			$LO_NoeudDescriptif->addChild("loyersfixes", $LT_LigneBien[12]);	
			$LO_NoeudDescriptif->addChild("loyerssaisonniers", $LT_LigneBien[13]);	
			$LO_NoeudDescriptif->addChild("loyerssaison01", $LT_LigneBien[14]);	
			$LO_NoeudDescriptif->addChild("loyerssaison02", $LT_LigneBien[15]);	
			$LO_NoeudDescriptif->addChild("loyerssaison03", $LT_LigneBien[16]);	
			$LO_NoeudDescriptif->addChild("loyerssaison04", $LT_LigneBien[17]);	
			$LO_NoeudDescriptif->addChild("loyerssaison05", $LT_LigneBien[18]);	
			$LO_NoeudDescriptif->addChild("loyerssaison06", $LT_LigneBien[19]);	
			$LO_NoeudDescriptif->addChild("loyerssaison07", $LT_LigneBien[20]);	
			$LO_NoeudDescriptif->addChild("loyerssaison08", $LT_LigneBien[21]);	
			$LO_NoeudDescriptif->addChild("loyerssaison09", $LT_LigneBien[22]);	
			$LO_NoeudDescriptif->addChild("loyerssaison10", $LT_LigneBien[23]);	
			$LO_NoeudDescriptif->addChild("loyerssaison11", $LT_LigneBien[24]);	
			$LO_NoeudDescriptif->addChild("loyerssaison12", $LT_LigneBien[25]);	
		}
	}
	
	// simulation charges
	$LO_NoeudFrais = $LO_NoeudSimulation->addChild("frais");
	$LO_NoeudFrais->addChild("prixachat", $_POST["ed_PrixAchat"]);
	$LO_NoeudFrais->addChild("fraisnotaire", $_POST["ed_FraisNotaire"]);
	$LO_NoeudFrais->addChild("fraisdivers", $_POST["ed_FraisDivers"]);
	$LO_NoeudFrais->addChild("travauxndeduc", $_POST["ed_PrixTravauxNDeduc"]);
	$LO_NoeudFrais->addChild("travauxdeduc", $_POST["ed_PrixTravauxDeduc"]);
	$LO_NoeudFrais->addChild("mobilier", $_POST["ed_PrixMobilier"]);
	$LO_NoeudFrais->addChild("apport", $_POST["ed_Apport"]);
	
	// simu
	$LO_NoeudCredit = $LO_NoeudSimulation->addChild("credit");
	$LC_PTZ      = "";	
	if (IsSet($_POST["cb_PTZ"])){$LC_PTZ = "PTZ";}
	$LO_NoeudCredit->addChild("typepret", TOOL_EncodeRadio("rd_Pret"));
	$LO_NoeudCredit->addChild("emprunt", $_POST["ed_Emprunt"]);
	$LO_NoeudCredit->addChild("tauxnominal", $_POST["ed_TauxNominal"]);
	$LO_NoeudCredit->addChild("tauxassurance", $_POST["ed_TauxAssurance"]);
	$LO_NoeudCredit->addChild("duree", $_POST["ed_Duree"]);
	$LO_NoeudCredit->addChild("mensualite", $_POST["ed_Mensualite"]);
	$LO_NoeudCredit->addChild("ptz", $LC_PTZ);
	$LO_NoeudCredit->addChild("ptzmontant", $_POST["ed_PTZMontant"]);
	$LO_NoeudCredit->addChild("ptzduree", $_POST["ed_PTZDuree"]);
	
	// simulation charges
	$LO_NoeudCharges = $LO_NoeudSimulation->addChild("charges");
	$LO_NoeudCharges->addChild("assurancepno", $_POST["ed_AssurancePNO"]);
	$LO_NoeudCharges->addChild("evolassurancepno", $_POST["ed_EvolAssurancePNO"]);
	$LO_NoeudCharges->addChild("assurancegrl", $_POST["ed_AssuranceGRL"]);
	$LO_NoeudCharges->addChild("evolassurancegrl", $_POST["ed_EvolAssuranceGRL"]);
	$LO_NoeudCharges->addChild("assurancecopro", $_POST["ed_AssuranceCOPRO"]);
	$LO_NoeudCharges->addChild("evolassurancecopro", $_POST["ed_EvolAssuranceCOPRO"]);
	$LO_NoeudCharges->addChild("chargescopro", $_POST["ed_ChargesCOPRO"]);
	$LO_NoeudCharges->addChild("evolchargescopro", $_POST["ed_EvolChargesCOPRO"]);
	$LO_NoeudCharges->addChild("autrescharges", $_POST["ed_AutresCharges"]);
	$LO_NoeudCharges->addChild("evolautrescharges", $_POST["ed_EvolAutresCharges"]);
	$LO_NoeudCharges->addChild("fraisgestion", $_POST["ed_FraisGestion"]);
	$LO_NoeudCharges->addChild("evolfraisgestion", $_POST["ed_EvolFraisGestion"]);
	$LO_NoeudCharges->addChild("travauxannuels", $_POST["ed_TravauxAnnuels"]);
	$LO_NoeudCharges->addChild("evoltravauxannuels", $_POST["ed_EvolTravauxAnnuels"]);
	$LO_NoeudCharges->addChild("nbloyers", $_POST["ed_NbLoyers"]);
	$LO_NoeudCharges->addChild("evolloyers", $_POST["ed_EvolLoyers"]);

	$LO_XML->asXML($PC_XML);
}

function FICHIER_Upload($PC_NomFichier, $PC_NomDistant)
{
	// préparer les données
	$LC_Erreur		= "";
	$LC_Dossier     = 'Data/Photos/';
	$LC_Fichier     = basename($_FILES[$PC_NomFichier]['name']);
	$LN_taille_maxi = 200000;
	$LN_taille      = filesize($_FILES[$PC_NomFichier]['tmp_name']);
	$LT_extensions  = array('.png', '.jpg', '.jpeg');
	$LC_extension   = StrToLower(strrchr($_FILES[$PC_NomFichier]['name'], '.')); 

	// sortie anticipée
	if ($LC_Fichier == ""){return "";}
	
	//Début des vérifications de sécurité...
	if(!in_array($LC_extension, $LT_extensions)) //Si l'extension n'est pas dans le tableau
		{
			$LC_Erreur = 'Vous ne devez uploader un fichier que de type png, jpg ou jpeg...';
		}
	if($LN_taille > $LN_taille_maxi)
		{
			$LC_Erreur = 'Le fichier est trop gvolumineux...';
		}
	if ($LC_Erreur != "")
		{
			return $LC_Erreur;
		}
		
	//On update
	if(move_uploaded_file($_FILES[$PC_NomFichier]['tmp_name'], $LC_Dossier . $PC_NomDistant))
		{
			return "OK";
		}
	else 
		{
			return "Echec de l\'upload !";
		}
}

function TICKET_Achat($PC_ID, $PC_NombreTickets)
{
Global $LO_IAD;
	$LC_SQL  = "Insert into sci_tickets ";
	$LC_SQL .= "(LOG_ID, TI_Date, TI_IP, TI_Libelle, TI_Achat, TI_Utilise, TI_Paiement, TI_CleMD5) ";
	$LC_SQL .= "values (";
	$LC_SQL .= $PC_ID . ", ";
	$LC_SQL .= "'" . Date("ymd") . "', ";
	$LC_SQL .= "'" . $_SERVER["REMOTE_ADDR"] . "', ";
	$LC_SQL .= "'" . "Achat tickets " . "', ";
	$LC_SQL .= $PC_NombreTickets . ", ";
	$LC_SQL .= "0, ";
	$LC_SQL .= "0, ";
	$LC_SQL .= "'') ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	TOOL_Chainage("SCI_EF_Index.php?page=PDF_Accueil");
	return;
}

function ACTU_Modif()
{
Global $LO_IAD;

	$LC_SQL  = "update sci_actualite Set ";
	$LC_SQL .= "ACTU_Titre = " . TOOL_EncodeSQL("C", $_POST["ed_Titre"]) . ", ";
	$LC_SQL .= "ACTU_DateDeb = " . TOOL_EncodeSQL("C", $_POST["ed_DateDeb"]) . ", ";
	$LC_SQL .= "ACTU_DateFin = " . TOOL_EncodeSQL("C", $_POST["ed_DateFin"]) . ", ";
	$LC_SQL .= "ACTU_CouleurFond = " . TOOL_EncodeSQL("C", $_POST["ed_CouleurFond"]) . ", ";
	$LC_SQL .= "ACTU_CouleurTexte = " . TOOL_EncodeSQL("C", $_POST["ed_CouleurTexte"]) . ", ";
	$LC_SQL .= "ACTU_Debut = " . TOOL_EncodeSQL("C", $_POST["ed_Debut"]) . ", ";
	$LC_SQL .= "ACTU_Texte = " . TOOL_EncodeSQL("C", $_POST["ed_Texte"]) . " ";
	$LC_SQL .= "Where ACTU_ID = " . $_POST["ed_ID"];
	$LO_IAD->ExecuteSQL($LC_SQL);
	$_SESSION["SCI_Message"] = utf8_encode("Les informations sur cet encart ont bien été mises a jour");
	TOOL_Chainage("SCI_EF_Index.php");
	return;
}

function SQL_Exec()
{
Global $LO_IAD;
	if ($_POST["ed_PW"] != "jbmd")
	{
		$_SESSION["SQL"] = "Mot de passe invalide";
		TOOL_Chainage("SCI_EF_Index.php?page=testrequetes");
		return;
	}
	$LC_SQL = $_POST["ed_SQL"];
	If (StrPos("*" . StrToUpper($LC_SQL), "SELECT") == 1)
	{
		$_SESSION["SQL"] = $_POST["ed_SQL"];
		TOOL_Chainage("SCI_EF_Index.php?page=testrequetes");
		return;
	}
	else
	{
		$LO_IAD->ExecuteSQL($LC_SQL);
		$_SESSION["SQL"] = "";
		TOOL_Chainage("SCI_EF_Index.php?page=admin");
		return;
	}
}

?>