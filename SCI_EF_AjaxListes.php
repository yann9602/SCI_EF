<?PHP
Session_start();
require('SCI_EF.inc');
Require("Classe_IAD_MySQL.php");
$LO_IAD  = New Classe_IAD();

// sortie anticipée
//if (TOOL_SessionLire("SESSION", "USER_ID") == ""){return;}

// fichier XML
header("Content-Type:text/xml;charset:utf-8");

// gestion de l'aiguillage
$LC_Commentaire = "OK";
Switch ($_GET["cle"]){
	case "listeinvestisseurs" :
			AJAX_ListeInvestisseur();
			break;
	case "listedossiers" :
			AJAX_ListeDossiers($_GET["id"]);
			break;
}	

	
function AJAX_ListeInvestisseur(){
Global $LO_IAD;
	//memoriser les informations
	$LC_SQL  = "Update sci_memoire Set ";
	$LC_SQL .= "MEM_InvTri  = '"           . $_GET["tri"] ."', ";
	$LC_SQL .= "MEM_InvSens = '"           . $_GET["sens"] ."', ";
	$LC_SQL .= "MEM_InvFiltreNom = '"      . TOOL_SessionLire("GET", "filtrenom") ."', ";
	$LC_SQL .= "MEM_InvFiltreCapacite = '" . TOOL_SessionLire("GET", "filtrecapacite") ."', ";
	$LC_SQL .= "MEM_InvPage = '"           . TOOL_SessionLire("GET", "page") . "' ";
	$LC_SQL .= "Where LOG_ID =0" . TOOL_SessionLire("SESSION", "USER_ID") . " ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	// selectionner les informations
	$LC_SQL  = "SELECT EMP_ID, Concat(EMP_Nom, ' ') as EMP_Nom, EMP_CapaciteEmprunt ";
	$LC_SQL .= "from sci_emprunteurs ";
	$LC_SQL .= "where LOG_ID in (" . TOOL_SessionLire("SESSION", "USER_ID") . ",0) ";
	//$LC_SQL .= "where 1=1 ";
	// critères
	if (TOOL_SessionLire("GET", "filtrenom")!="")
		{
			$LC_SQL .= "and EMP_Nom COLLATE utf8_unicode_ci like '%" . TOOL_SessionLire("GET", "filtrenom") . "%' ";
		}		
	if (TOOL_SessionLire("GET", "filtrecapacite")!="")
		{
			$LC_SQL .= "and EMP_CapaciteEmprunt > " . TOOL_SessionLire("GET", "filtrecapacite") . " ";
		}		
		
	// ordre de tri
	if ($_GET["tri"]=="Nom"){$LC_SQL .= "order by UCASE(EMP_Nom) ";}	
	if ($_GET["tri"]=="Capacite"){$LC_SQL .= "order by EMP_CapaciteEmprunt ";}	
	if (TOOL_SessionLire("GET", "sens")=="A"){$LC_SQL .= "Asc ";}	
	if (TOOL_SessionLire("GET", "sens")=="D"){$LC_SQL .= "Desc ";}
	
	//pagination	
	$LN_TaillePage=10;
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LN_NombreLignes=Max(Round($LO_IAD->NombreLignes() / $LN_TaillePage)+1, 1); 	
	$LN_Page=TOOL_SessionLire("GET", "page")-1;
	$LN_Page=$LN_Page * $LN_TaillePage;
	if ($LN_Page<0){$LN_Page=0;}
	$LC_SQL .= "Limit ";
	$LC_SQL .= $LN_Page;
	$LC_SQL .= ", " . $LN_TaillePage;
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	// bloc XML
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\" ?> \n";
	$LC_XML .= "<data> \n";
	$LC_XML .= "<nombrelignes>" . $LN_NombreLignes . "</nombrelignes> \n";

	// boucle
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			$LC_XML .= "<element> \n";
			$LC_XML .= "<id>" . utf8_encode($LT_Ligne["EMP_ID"]) . "</id> \n";
			$LC_XML .= "<libelle>" . utf8_encode($LT_Ligne["EMP_Nom"]) . "</libelle> \n";
			$LC_XML .= "<capacite>" . utf8_encode($LT_Ligne["EMP_CapaciteEmprunt"]) . "</capacite> \n";
			$LC_XML .= "</element> \n";
		}	
		
	// queue	
	$LC_XML .= "</data> \n";		
	echo $LC_XML;
}


function AJAX_ListeDossiers($PC_ID){
Global $LO_IAD;

	//memoriser les informations
	$LC_SQL  = "Update sci_memoire Set ";
	$LC_SQL .= "MEM_SimTri  = '" . $_GET["tri"] ."', ";
	$LC_SQL .= "MEM_SimSens = '" . $_GET["sens"] ."', ";
	$LC_SQL .= "MEM_SimPage = '" . TOOL_SessionLire("GET", "page") . "' ";
	$LC_SQL .= "Where LOG_ID =0" . TOOL_SessionLire("SESSION", "USER_ID") . " ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	// selectionner
	$LC_SQL  = "SELECT SIM_ID, EMP_ID, SIM_Alias, SIM_Libelle, SIM_Date ";
	$LC_SQL .= "from sci_simulation ";
	$LC_SQL .= "where EMP_ID = " . $PC_ID . " ";
	$LC_SQL .= "and SIM_Libelle <> '' ";
	//ordres de tri
	if ($_GET["tri"]=="Libelle"){$LC_SQL .= "order by SIM_Libelle ";}	
	if ($_GET["tri"]=="Date"){$LC_SQL .= "order by SIM_Date ";}	
	if (TOOL_SessionLire("GET", "sens")=="A"){$LC_SQL .= "Asc ";}	
	if (TOOL_SessionLire("GET", "sens")=="D"){$LC_SQL .= "Desc ";}		
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	// bloc XML
	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\" ?> \n";
	$LC_XML .= "<data> \n";

	// boucle
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			$LC_XML .= "<element> \n";
			$LC_XML .= "<id>" . TOOL_CodeAjax($LT_Ligne["SIM_ID"]) . "</id> \n";
			$LC_XML .= "<libelle>" . TOOL_CodeAjax($LT_Ligne["SIM_Libelle"]) . "</libelle> \n";
			$LC_XML .= "<date>" . TOOL_CodeAjax($LT_Ligne["SIM_Date"]) . "</date> \n";
			$LC_XML .= "</element> \n";
		}	
		
	// queue	
	$LC_XML .= "</data> \n";		
	echo $LC_XML;
}
	
?>