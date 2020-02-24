<?PHP

Function DecompteTicket($PC_CodeOpe, $PC_ID, $PC_LogID, $PC_XML){
Global $LO_IAD;

	// déterminer les valeurs à partir du code
	$LC_Libelle  = "";
	$LC_NbTicket = "0";
	Switch ($PC_CodeOpe)
	{
		Case "PDFSYNTHESE" :
				$LC_Libelle = "Page de synthèse";
				$LC_NbTicket= "1";
				Break;
		Case "PDFBILANM" :
				$LC_Libelle = "Bilan mensuel";
				$LC_NbTicket= "1";
				Break;
		Case "PDFBILANA" :
				$LC_Libelle = "Bilan annuel";
				$LC_NbTicket= "1";
				Break;
		Case "PDFRENTABILITE" :
				$LC_Libelle = "Graphe de rentabilité";
				$LC_NbTicket= "1";
				Break;
		default:
				$LC_Libelle = "";
				$LC_NbTicket= "0";     
	}
	if ($LC_NbTicket == "0")
	{
		return "OK";
		Exit;
	}
	
	// vérifier si cette simulation a été modifiée
	$LC_Cle  = TOOL_FichierCle("../" . $PC_XML);
	$LC_SQL  = "Select TI_CleMD5 from sci_tickets ";
	$LC_SQL .= "where SIM_ID = " .  $PC_ID;
	$LC_SQL .= "  and TI_Libelle = '" . str_replace("'", "''", $LC_Libelle) . "' ";
	$LC_SQL .= "  and TI_ID = (Select Max(TI_ID) from sci_tickets ";
	$LC_SQL .= "where SIM_ID = " .  $PC_ID;
	$LC_SQL .= "  and TI_Libelle = '" . str_replace("'", "''", $LC_Libelle) . "') ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	if ($LO_IAD->NombreLignes() != 0)
	{
		$LT_Ligne = $LO_IAD->EclateSQL("Noms");
		if ($LC_Cle == $LT_Ligne["TI_CleMD5"])
		{
			// elle n'a pas été changée
			$LC_NbTicket = "0";
			return "OK";
		}
	}

	// vérifier si le solde permet de passer l'opération
	$LC_SQL  = "Select SUM(TI_Achat) - SUM(TI_Utilise) as solde " ;
	$LC_SQL .= "from sci_tickets ";
	$LC_SQL .= "where LOG_ID = " . $PC_LogID;
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
	if (IntVal($LT_Ligne["solde"]) - Intval($LC_NbTicket) < 0)
	{
		// plus assez de crédits
		return "KO";
	}
	// insérer le ticket d'achat$lines = file("fichier.txt");
	$LC_SQL  = "Insert into sci_tickets ";
	$LC_SQL .= "(LOG_ID, SIM_ID, TI_Date, TI_IP, TI_Libelle, TI_Achat, TI_Utilise, TI_CleMD5) ";
	$LC_SQL .= "values (";
	$LC_SQL .= $PC_LogID . ", ";
	$LC_SQL .= $PC_ID . ", ";
	$LC_SQL .= "'" . Date("ymd") . "', ";
	$LC_SQL .= "'" . $_SERVER["REMOTE_ADDR"] . "', ";
	$LC_SQL .= "'" . $LC_Libelle . "', ";
	$LC_SQL .= "0, ";
	$LC_SQL .= $LC_NbTicket . ", ";
	$LC_SQL .= "'" . $LC_Cle . "') ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	return "OK";
	
}
?>