<?PHP

$LC_Champs = "ed_PrixAchat;ed_FraisNotaire;ed_FraisDivers;ed_PrixTravauxNDeduc;ed_PrixTravauxDeduc;" .
             "ed_Apport;ed_Emprunt;rd_Pret;ed_TauxNominal;ed_TauxAssurance;ed_Duree;ed_Mensualite;" .
			 "ed_PTZMontant;ed_PTZDuree;ed_PTZ;rd_Pret";
Define ("Def_CalculFinancement", $LC_Champs);
$LC_Champs = "ed_AutreMensualite;ed_ChargeResPrincipale;ed_ChargePension;" . 
             "ed_Salaire;ed_RevenusFonciers";
Define ("Def_CalculInvestisseur", $LC_Champs);
$LC_Champs = "xx";
Define ("Def_CalculEndettement", $LC_Champs);


function TOOL_DecodeNum($PC_Valeur){
	$LC_Result = Str_Replace(",", ".", $PC_Valeur);
	return $LC_Result;
}

function TOOL_DecodeCombo($PC_Valeur, $PC_Comparaison){
	$LC_Result = "";
	if ($PC_Valeur==$PC_Comparaison)
		{
			$LC_Result = "selected=true";
		}
	return $LC_Result;	
}
		
function TOOL_DecodeCheck($PC_Valeur, $PC_Comparaison){
	$LC_Result = "";
	if ($PC_Valeur==$PC_Comparaison)
		{
			$LC_Result = "checked=true";
		}
	return $LC_Result;	
}

function TOOL_DecodeInt($PC_Valeur){
	$LC_Result = "0";
	if ($PC_Valeur != ""){$LC_Result=$PC_Valeur;}
	return $LC_Result;	
}

function TOOL_SessionLire($PC_Type, $PC_Variable){
	if ($PC_Type == "SESSION")
		{
			if (IsSet($_SESSION[$PC_Variable]))
				{
					return $_SESSION[$PC_Variable];
				}
		}
	if ($PC_Type == "GET")
		{
			if (IsSet($_GET[$PC_Variable]))
				{
					return $_GET[$PC_Variable];
				}
		}	
	return "";	
}

function TOOL_EncodeSQL($PC_Type, $PC_Valeur){
	$LC_Valeur = str_replace("'", "''", $PC_Valeur);
	if ($PC_Type="C"){
		$LC_Valeur = "'" . $LC_Valeur . "'";
	}
	//$LC_Valeur = utf8_encode($LC_Valeur);

	return $LC_Valeur;
}

function TOOL_CodeAjax($PC_Valeur){
	$LC_Result = $PC_Valeur;
	if ($LC_Result==""){$LC_Result = "-";}
	return $LC_Result;
}


?>