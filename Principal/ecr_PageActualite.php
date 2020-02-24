<?PHP
	// selectionner les annonces en cours
	$LC_SQL = "Select ACTU_ID, ACTU_Titre, ACTU_Texte ";
	$LC_SQL = $LC_SQL . "From sci_actualite ";
	$LC_SQL = $LC_SQL . "Where ACTU_ID=" . $_GET["texte"];
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Champs = $LO_IAD->EclateSQL("Noms");
?>


<br/>	
<br/>
<div>
	<?PHP Echo $LT_Champs["ACTU_Titre"]; ?>
	<br/>	
	<br/>
	<?PHP Echo $LT_Champs["ACTU_Texte"]; ?>
</div>	
<?PHP if (TOOL_SessionLire("SESSION", "USER_STATUT") == 'SUPER'){ ?>
	<li><a 	href="SCI_EF_Index.php?page=saisieactualite&id=<?PHP echo $LT_Champs["ACTU_ID"];?>">
		Modifier la fiche
	</a>
<?PHP } ?>