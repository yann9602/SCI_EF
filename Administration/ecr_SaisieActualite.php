<?PHP
	// selectionner les annonces en cours
	$LC_SQL = "Select ACTU_Titre, ACTU_Datedeb ACTU_Texte, ";
	$LC_SQL = $LC_SQL . "ACTU_DateDeb, ACTU_DateFin, ";
	$LC_SQL = $LC_SQL . "ACTU_CouleurFond, ACTU_CouleurTexte, ";
	$LC_SQL = $LC_SQL . "ACTU_Debut, ACTU_Texte ";
	$LC_SQL = $LC_SQL . "From sci_actualite ";
	$LC_SQL = $LC_SQL . "Where ACTU_ID=0" . TOOL_SessionLire("SESSION", "ACT_ID");
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Champs = $LO_IAD->EclateSQL("Noms");
?>

<Script Language="JavaScript">
	function ACTU_Valide()
	{
		document.getElementById("ed_Phase").value = "ModifACTU";
		document.forms[0].submit();
	}
</script>


<br/>
<br/>
<table class='table_Cadre'
		style='text-align:center;'>
		
	<tr>
		<td class	= 'td_LibelleTitre td_LibelleTitreFiscalite'
			colspan='2'>
			Mise à jour fiche actualité
		</td>
	</tr>

	<tr>
		<td class='td_SaisieLibelle'>
			Titre de l'article
		</td>
		<td>
			<input	type	= "hidden"
					name	= "ed_Phase"
					id		= "ed_Phase" />
			<input	type	= "hidden"
					name	= "ed_ID"
					value	= "<?PHP echo TOOL_SessionLire("SESSION", "ACT_ID");?>" />
			<input	type	= "text"
					name	= "ed_Titre"
					id		= "ed_Titre"
					value	= "<?PHP echo $LT_Champs['ACTU_Titre'];?>"
					class	= "" />
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Date de début
		</td>
		<td>
			<input	type	= "text"
					name	= "ed_DateDeb"
					id		= "ed_DataDeb"
					value	= "<?PHP echo $LT_Champs["ACTU_DateDeb"];?>"
					class	= "" />
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Date de fin
		</td>
		<td>
			<input	type	= "text"
					name	= "ed_DateFin"
					id		= "ed_DataFin"
					value	= "<?PHP echo $LT_Champs["ACTU_DateFin"];?>"
					class	= "" />
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Couleur de fond
		</td>
		<td>
			<input	type	= "text"
					name	= "ed_CouleurFond"
					id		= "ed_CouleurFond"
					value	= "<?PHP echo $LT_Champs["ACTU_CouleurFond"];?>"
					class	= "" />
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Couleur de texte
		</td>
		<td>
			<input	type	= "text"
					name	= "ed_CouleurTexte"
					id		= "ed_CouleurTexte"
					value	= "<?PHP echo $LT_Champs["ACTU_CouleurTexte"];?>"
					class	= "" />
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<textarea name	= "ed_Debut"
					  id	= "ed_Debut"
					  rows	= "2"
					  cols	= "80"><?PHP echo $LT_Champs["ACTU_Debut"];?>"</textarea>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<textarea name	= "ed_Texte"
					  id	= "ed_Texte"
					  rows	= "12"
					  cols	= "80"><?PHP echo $LT_Champs["ACTU_Texte"];?>"</textarea>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<input type='button'
					name = 'btn_Valide'
					id	 = 'btn_Valide'
					value= 'Enregistre'
					onclick='ACTU_Valide()'	/>
		</td>
	</tr>	
	</table>	