<?PHP
?>

<div class = 'DI6040_Gauche'>
	<table class='table_Cadre'>
		<tr>
			<td colspan	= '2' 
				class	= 'td_LibelleTitre'>
				Liste des visites
			</td>
		</tr>
		<tr>
			<th>Date</th>
			<th>Nombre de visites</th>
		</tr>		
		<?PHP VISITES_Charge(); ?>
	</table>
</div>

<?PHP
function VISITES_Charge()
{
global $LO_IAD;

	$LC_SQL = "SELECT left(VIS_Date, 8) as Date, VIS_IP, count(*) as nb ";
	$LC_SQL .= "FROM sci_visites ";
	$LC_SQL .= "group by left(VIS_Date, 8) ";
	$LC_SQL .= "order by vis_date ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	for ($i=0; $i <$LO_IAD->NombreLignes(); $i++)
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			echo "<tr> \n";
			echo "<td>" . $LT_Ligne["Date"] . "</td> \n";
			echo "<td>" . $LT_Ligne["nb"] . "</td> \n";						
			echo "</tr> \n";
		}

}

?>