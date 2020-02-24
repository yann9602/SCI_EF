<Script Language="JavaScript">
	function REQ_Valide()
	{
		document.getElementById("ed_Phase").value = "TestREQ";
		document.forms[0].submit();
	}
</script>


<br/>
<br/>
<table class='table_Cadre'
		style='text-align:center;'>
		
	<tr>
		<td class	= 'td_LibelleTitre td_LibelleTitreFiscalite'
			colspan = '2'>
			Gestion de requetes directes
		</td>
	</tr>

	<tr>
		<td class='td_SaisieLibelle'>
			Mot de passe
		</td>
		<td>
			<input	type	= "text"
					name	= "ed_PW"
					id		= "ed_PW" />
		</td>
	</tr>
	<tr>
		<td colspan = '2'>
			<textarea name	= "ed_SQL"
					  id	= "ed_SQL"
					  rows	= "6"
					  cols	= "80"><?PHP echo TOOL_SessionLire("SESSION", "SQL"); ?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<br/>
			<input type='button'
					name = 'btn_Valide'
					id	 = 'btn_Valide'
					value= 'Test'
					onclick='REQ_Valide()'	/>
		</td>
	</tr>	
</table>	

<table border = '1' width='100%' >
<?PHP
	$LC_HTML = "";
	if (TOOL_SessionLire("SESSION", "SQL") != "") 
	{
		$LO_IAD->ExecuteSQL(TOOL_SessionLire("SESSION", "SQL"));
		$LT_Ligne = $LO_IAD->EclateSQL("StructureNom");
		$LC_HTML = $LC_HTML . "<tr>";
		foreach ($LT_Ligne as $LO_Cell)
		{
			$LC_HTML = $LC_HTML . "<th>";
			$LC_HTML = $LC_HTML . $LO_Cell; // $LT_Ligne[$j];
			$LC_HTML = $LC_HTML . "</th>";
		}
		$LC_HTML = $LC_HTML . "</tr>";

		for ($i=0; $i<$LO_IAD->NombreLignes(); $i++)
			{
				$LC_HTML = $LC_HTML . "<tr>";
				$LT_Ligne = $LO_IAD->EclateSQL("Num");
				for ($j=0; $j<$LO_IAD->NombreChamps(); $j++)
					{
						$LC_HTML = $LC_HTML . "<td>";
						$LC_HTML = $LC_HTML . $LT_Ligne[$j];
						$LC_HTML = $LC_HTML . "</td>";
					}
				$LC_HTML = $LC_HTML . "</tr>";
			}
	}
	echo $LC_HTML;
?>