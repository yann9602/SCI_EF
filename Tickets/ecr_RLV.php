<Script Language="JavaScript">
	function RESULTAT_AchatTickets()
		{
			window.location="SCI_EF_index.php?page=achattickets";
		}
</Script>
<br/>
<div style='float:left;width:100%;' 
	 class	= 'td_LibelleTitre'>
		Relevé des utilisations de ticket
</div>
<br/>
<br/>
<div style ="height: 400px;overflow: scroll;">
<table align='center'
		class='table_Page'
		style='	border-color :#DD0000;
				border-width	:2px;
				border-collapse :collapse;
				border-color	:#FF0000;
				border-style	:solid;'
		>
	<tr>
		<th class='td_Grille' style='width:10%'>Date</th>
		<th class='td_Grille' style='width:30%'>Simulation</th>
		<th class='td_Grille' style='width:30%'>Opération effectuée</th>
		<th class='td_Grille' style='width:10%;'>Achat </th>
		<th class='td_Grille' style='width:10%;'>Utilisation</th>
		<th class='td_Grille' style='width:10%;'>Solde</th>
	</tr>
	<?PHP $LN_Solde = LISTE_RLV(); ?>
</table>
</div>
<br/>
Le solde actuel est de : <?PHP echo $LN_Solde; ?> tickets.
<br/>
<?PHP if (TOOL_SessionLire("SESSION", "SCI_CTX") == "REEL"){?>
	<a 	href="javascript:RESULTAT_AchatTickets()">
		<img src='Images/BoutonsFonctionnement/btn_AcheterTickets.png'/>
	</a>
<?PHP }?>


<?PHP
function LISTE_RLV()
{
Global $LO_IAD;
	// s�lectionner les lignes � afficher
	$LC_SQL  = "SELECT TI_ID, LOG_ID, TI_Date, TI_IP, TI_Libelle, TI_Achat, TI_Utilise, ";
	$LC_SQL .= "SIM_Libelle ";
	$LC_SQL .= "from sci_tickets ";
	$LC_SQL .= "left join sci_simulation on sci_simulation.SIM_ID = sci_tickets.SIM_ID ";
	$LC_SQL .= "where LOG_ID = " . TOOL_SessionLire("SESSION", "USER_ID") . " ";
	$LC_SQL .= "order by TI_Date asc ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	// tete HTML de tete de bloc de détail général
	$LN_Solde = 0;
	for ($i=0;$i<$LO_IAD->NombreLignes();$i++)
	{
		$LT_Ligne = $LO_IAD->EclateSQL("Noms");
		$LN_Solde = $LN_Solde + Intval($LT_Ligne["TI_Achat"]);
		$LN_Solde = $LN_Solde - Intval($LT_Ligne["TI_Utilise"]);
		echo "	<tr> \n";
		echo "		<td class='td_Grille'>" . TOOL_DateAAMMJJ_JJMMAA($LT_Ligne["TI_Date"]) . "</td> \n";
		echo "		<td class='td_Grille'>" . $LT_Ligne["SIM_Libelle"] . "</td> \n";
		echo "		<td class='td_Grille'>" . $LT_Ligne["TI_Libelle"] . "</td> \n";
		echo "		<td class='td_Grille' style='text-align:right'>" . $LT_Ligne["TI_Achat"] . "</td> \n";
		echo "		<td class='td_Grille' style='text-align:right'>" . $LT_Ligne["TI_Utilise"] . "</td> \n";
		echo "		<td class='td_Grille' style='text-align:right'>" . $LN_Solde . "</td> \n";
		echo "	</tr> \n";
	}
	return $LN_Solde;
}