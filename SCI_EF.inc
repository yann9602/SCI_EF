<?PHP

$LC_Champs = "ed_PrixAchat;ed_FraisNotaire;ed_FraisDivers;ed_PrixTravauxNDeduc;ed_PrixTravauxDeduc;" .
             "ed_Apport;ed_Emprunt;rd_Pret;ed_TauxNominal;ed_TauxAssurance;ed_Duree;ed_Mensualite;" .
			 "ed_PTZMontant;ed_PTZDuree;ed_PTZ";
Define ("Def_CalculFinancement", $LC_Champs);

$LC_Champs = "ed_AutreMensualite;ed_ChargeResPrincipale;ed_ChargePension;" . 
             "ed_Salaire;ed_RevenusFonciers";
Define ("Def_CalculInvestisseur", $LC_Champs);

$LC_Champs = "xx";
Define ("Def_CalculEndettement", $LC_Champs);

$LC_Champs = "ed_Salaire;ed_RevenusFonciers;ed_EvolRevenus;ed_FiscNbPart1;ed_FiscDelai1;" .
			 "ed_FiscNbPart2;ed_FiscDelai2;ed_FiscNbPart3;ed_FiscDelai3;ed_FiscNbPart4;ed_FiscDelai4;" .
			 "ed_FiscNbPart5;ed_FiscDelai5;ed_EtudeDuree;" . 
			 "ed_PrixAchat;ed_FraisDivers;ed_Apport;ed_TauxNominal;ed_TauxAssurance;ed_EvolAssurancePNO;" .
			 "ed_ChargesCOPRO;ed_EvolChargesCOPRO";
Define ("Def_DemoBlocage", $LC_Champs);	
			 
Define ("Def_MailEmetteur", "michel.driant@orange.fr");
Define ("Def_URL", "http://application-eril");
Define ("Def_ValeurTauxCotSociale", "17.2");
Define ("Def_PlafondRMF", "15000");
Define ("Def_AbattementRMF", "30");
Define ("Def_PlafondMicroBIC", "70000");
Define ("Def_AbattementMicroBIC", "50");

Define ("Def_PaypalCompte", "doubleg-08@outlook.com");
//Define ("Def_PaypalCompte", "ch.driant@orange.fr");
Define ("Def_PaypalURL", "http://application-eril.fr");

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

function TOOL_EncodeRadio($PC_Objet){
	if (IsSet($_POST[$PC_Objet]))
		{
			return $_POST[$PC_Objet];;
		}
	return "";	
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

function TOOL_Brouillage()
{
	$LC_Retour = "";
	for ($i=1;$i<=3;$i++)
	{
		$LN_RND = rand(0,9);
		$LC_RND = (String)$LN_RND;
		$LC_Retour = $LC_Retour . $LC_RND;
	}
	return $LC_Retour;
}

function TOOL_FichierCle($PC_FileName)
{
	$LO_Fh      = fOpen($PC_FileName, "r");
	$LC_Fichier = "";
    while(!fEof($LO_Fh))
    {
        $LC_Fichier .= fRead($LO_Fh, round(20.5 * 1024));
    }
    fClose($LO_Fh);
	$LN_Cumul=0;
	for ($i=0; $i<strLen($LC_Fichier); $i++)
	{
		$LC_Caractere=(Substr($LC_Fichier, $i, 1));
		$LN_Cumul += (Ord($LC_Caractere)*$i);
	}
	return 97-($LN_Cumul%97);
}

function TOOL_DateAAMMJJ_JJMMAA($PC_Date)
{
	$LC_Result = "";
	if (strLen($PC_Date) != 6)
	{
		$LC_Result = $PC_Date;
	}
	else
	{
		$LC_Annee = Substr($PC_Date, 0, 2);
		$LC_Mois  = Substr($PC_Date, 2, 2); 
		$LC_Jour  = Substr($PC_Date, 4, 2);
		$LC_Result = $LC_Jour . "/" . $LC_Mois . "/" . $LC_Annee;
	}
	return $LC_Result;
}

function TOOL_Chainage($PC_URL)
{
	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location='" . $PC_URL . "';";
	echo "</script>";
}
?>