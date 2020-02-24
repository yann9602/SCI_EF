<?PHP
Session_Start();
require('SCI_EF.inc');
require('Classe_IAD_MySQL.php');
MB_Internal_Encoding("UTF-8");
$LO_IAD  = New Classe_IAD();

// connexion automatique
if ((IsSet($_SESSION["USER_ID"])==false) &&
	(IsSet($_COOKIE["SCIEF_Login"])==true) &&
	(IsSet($_COOKIE["SCIEF_PW"]))==true)
	{
		$LC_SQL  = "Select LOG_ID From sci_login ";
		$LC_SQL .= "Where LOG_Mail = '" . $_COOKIE["SCIEF_Login"] . "' ";
		$LC_SQL .= "  And LOG_PW = '" . $_COOKIE["SCIEF_PW"] . "' ";
		$LO_IAD->ExecuteSQL($LC_SQL);
		if ($LO_IAD->NombreLignes() == 1)
			{
				$LT_Ligne = $LO_IAD->EclateSQL("Noms");
				$_SESSION["USER_ID"] = $LT_Ligne["LOG_ID"];
			}
	}
// stocker en session les paramètres page et onglet
if (TOOL_SessionLire("GET", "page")   != ""){$_SESSION["SCI_Page"]   = TOOL_SessionLire("GET", "page");}
if (TOOL_SessionLire("GET", "onglet") != ""){$_SESSION["SCI_Onglet"] = TOOL_SessionLire("GET", "onglet");}
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Simulation Investissement locatif</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" />
		<meta http-equiv="Expires" CONTENT="0"> 
		<meta http-equiv="Cache-Control" CONTENT="no-store">
 		<meta http-equiv="Pragma" CONTENT="no-cache">
		<META NAME="ROBOTS" CONTENT="noindex,nofollow"> 
		<META NAME="ROBOTS" CONTENT="noarchive">
		<META NAME="ROBOTS" CONTENT="nosnippet">
		<META NAME="ROBOTS" CONTENT="noodp">
		<META NAME="ROBOTS" CONTENT="noydir">

		<LINK	REL	= StyleSheet 
				Type= Text/css
				HREF= "SCI_EF.css">

		<Script Language="JavaScript">	
			function TOOL_CacherObjet(PC_Objet, PC_Action){
				var LO_Objet = document.getElementById(PC_Objet);
				if (LO_Objet != null)
					{
						LO_Objet.style.display=PC_Action;
					}
			}
			
			function TOOL_RecupereXML(PC_XML, PC_Balise, PN_Rang){
				var LO_Data   = PC_XML.getElementsByTagName(PC_Balise)[PN_Rang];
				return LO_Data.childNodes[0].nodeValue;
			}
			
			function TOOL_DecodeInt(PC_Valeur){
				var LC_Retour = "0";
				if (PC_Valeur!=""){LC_Retour=PC_Valeur;}
				return parseInt(LC_Retour);
			}
			
			function TOOL_FiltreSaisie(PC_Type, event){
				var key = window.event ? event.keyCode : event.which;
				// touches de gestion
				if (event.keyCode ==  8 || event.keyCode == 46 || 
					event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 ||
					event.keyCode ==  9) {
					return true;
				}
				//touches alphanuméques
				if (PC_Type == "ENTIER"){LC_Filtre = "1234567890";}
				if (PC_Type == "DECIMAL"){LC_Filtre = "1234567890.";}
				if (LC_Filtre.indexOf(String.fromCharCode(key)) < 0) {
					return false;
				}	
				return true;
			}

			function TOOL_ControleChampObligatoire(PC_Champ, PC_Libelle)
			{
				if (document.getElementById(PC_Champ).value == ""){return PC_Libelle;}
				                                        else	{return "";}
			}
			
			function MESSAGE_Ferme(){
				document.getElementById("di_Message").innerHTML="";
			}
			
			function TOOL_DecodeDate(PC_Date)
			{
				if (PC_Date.length<8)
					{
						return PC_Date;
					}
				LC_Date = PC_Date.substr(6,2) + "/" + 
						  PC_Date.substr(4,2) + "/" +
						  PC_Date.substr(0,4);
				return LC_Date;	
			}			
		</script>		
	</head>
	
	<body   onload  = 'Global_Init()'	
			onkeyup = 'MESSAGE_Ferme()'>
	<form	name	= "fo_SCIEF"
			method	= "POST"
			action	= "SCI_EF.php"
			enctype	= "multipart/form-data">
		<input type='hidden' id='ed_Phase' name='ed_Phase' value=''/> 
		<?PHP Include "Principal/ecr_Entete.php"; ?>
		<?PHP Include "Principal/ecr_ToolBar.php"; ?>
		<div class="CADRE_Main">
			<?PHP
			if (isSet($_SESSION["USER_ID"])==false)
			{
				// gestion des aiguillages accessibles sans login
				if (IsSet($_GET["page"]))
				{
					Switch ($_GET["page"]){
						case "PresentationAbout" :
							Include "Presentation/ecr_About.php";
							break;
						case "CGU" :
							Include "Presentation/ecr_Mentionslegales.htm";
							break;
						case "simulation" :
							Include "Simulations/ecr_Simulation.php";
							break;
						case "PDF_Accueil" :
							Include "PDF/PDF_Verification.php";
							Include "PDF/PDF_Accueil.php";
							break;
						default:
							UnSet($_GET["page"]);
							Include "Login/ecr_Droits.php";
							break;
					}
				}
				else
				{
					// première entrée dans le site
					UnSet($_GET["page"]);
					Include "Login/ecr_Droits.php";
				}
			}
			else
			{
				if (IsSet($_GET["page"]))
				{
					Switch ($_GET["page"]){
						case "admin" :
							$LO_IAD->PointeVisite("ADMIN");
							Include "Administration/ecr_Menu.php";
							break;
						case "visites" :
							$LO_IAD->PointeVisite("VISITES");
							Include "Administration/ecr_VoirVisites.php";
							break;
						case "testrequetes" :
							$LO_IAD->PointeVisite("VISITES");
							Include "Administration/ecr_SaisieRequetes.php";
							break;
						case "css" :
							$LO_IAD->PointeVisite("CSS");
							Include "Administration/ecr_SaisieCSS.php";
							break;
						case "PresentationAbout" :
							Include "Presentation/ecr_About.php";
							break;
						case "liste" :
							$LO_IAD->PointeVisite("LISTE");
							Include "Investisseur/ecr_Liste.php";
							break;
						case "actualite" :
							$LO_IAD->PointeVisite("ACTUALITE");
							Include "Principal/ecr_PageActualite.php";
							break;
						case "saisieactualite" :
							$LO_IAD->PointeVisite("SAISIEACTUALITE");
							$_SESSION["ACT_ID"] = TOOL_SessionLire("GET", "id");
							Include "Administration/ecr_SaisieActualite.php";
							break;
						case "simulation" :
							$LO_IAD->PointeVisite("SIMULATION");
							$_SESSION["SCI_CTX"]  ="REEL";
							$_SESSION["SCI_ALIAS"]=TOOL_SessionLire("GET", "alias");
							$_SESSION["EMP_ID"]   =TOOL_SessionLire("GET", "invid");
							$_SESSION["SIM_ID"]   =TOOL_SessionLire("GET", "id");
							Include "Simulations/ecr_Simulation.php";
							break;
						case "login" :
							$LO_IAD->PointeVisite("LOGIN");
							unSet($_SESSION["LOG_Option"]);
							Include "Login/ecr_Droits.php";
							break;
						case "ModifLogin" :
							$LO_IAD->PointeVisite("ModifLogin");
							Include "Login/ecr_ModifLogin.php";
							break;
						case "NewLogin" :
							$LO_IAD->PointeVisite("NewLogin");
							unSet($_GET["page"]);
							unSet($_SESSION["USER_STATUT"]);
							unSet($_SESSION["USER_MODE"]);
							unSet($_SESSION["USER_ID"]);
							unSet($_SESSION["USER_TYPE"]);
							Include "Login/ecr_Droits.php";
							break;
						case "investisseur" :
							$LO_IAD->PointeVisite("INVESTISSEUR");
							Include "Simulations/ecr_Investisseur.php";
							break;	
						case "PDF_Accueil" :
							$LO_IAD->PointeVisite("PDF_Accueil");
							Include "PDF/PDF_Verification.php";
							Include "PDF/PDF_Accueil.php";
							break;
						case "RLV" :
							Include "Tickets/ecr_RLV.php";
							break;
						case "achattickets" :
							Include "Tickets/ecr_AchatTickets.php";
							break;
						case "CGU" :
							Include "Presentation/ecr_Mentionslegales.htm";
							break;
					}
				}
				else
				{
					Include "Investisseur/ecr_Liste.php";
				}
			}
			?>	
		</div>
		<div class="CADRE_Actualite">			
			<?PHP Include "Principal/ecr_Actualites.php"; ?>
		</div>
		<div>
			<?PHP Include "Principal/ecr_Pied.php";?>
		</div>
	</form>	
	</body>
</html>