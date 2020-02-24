<?PHP
	// definir le point actuel
	$LC_Date = Date("Ymd");
	$LC_Date = $LC_Date . "_000000";

	// selectionner les annonces en cours
	$LC_SQL = "Select ACTU_ID, ACTU_Titre, ACTU_Debut, ACTU_CouleurFond, ACTU_CouleurTexte ";
	$LC_SQL = $LC_SQL . "From sci_actualite ";
	$LC_SQL = $LC_SQL . "Where ACTU_DateDeb <='" . $LC_Date . "' "; 
	$LC_SQL = $LC_SQL . "  And ACTU_DateFin >='" . $LC_Date . "' ";
	$LC_SQL = $LC_SQL . "Limit 0, 4 ";
	$LO_IAD->ExecuteSQL($LC_SQL);

	// générer
	Echo "<table  border='4' width='100%' bordercolor='#808080' frame='hsides' rules='rows'> \n";
	For ($i=0;$i<$LO_IAD->NombreLignes();$i++)
		{
			$LT_Champs = $LO_IAD->EclateSQL("Noms");
			$Lien = "SCI_EF_Index.php?page=actualite" . "&texte=" . $LT_Champs["ACTU_ID"];
			Echo "<tr> \n";
			Echo "	<td> \n";
			Echo "		<div style='vertical-align:top;";
			Echo	"color:" . $LT_Champs["ACTU_CouleurTexte"] . ";";
			Echo	"background-color:" . $LT_Champs["ACTU_CouleurFond"] . ";";
			Echo	"font-size:small;'> \n";
			Echo			"<b>" . $LT_Champs["ACTU_Titre"] . "</b> \n"; 
			Echo 			"<br/>";
			Echo 			$LT_Champs["ACTU_Debut"]; 
			Echo 			"<a href='" . $Lien . "'>Lire la suite...</a>"; 
			Echo 			"<br/><br/> \n";
			Echo 			"</div> \n";
			Echo 			"<br/> \n";
			Echo 	"</td> \n";
			Echo "</tr> \n";
		}
	Echo "</table> \n";	
?>

