<Script Language="JavaScript">
	function LOGIN_Traite(){
		document.getElementById("ed_Phase").value = "LOGIN";
		document.fo_SCIEF.submit();
	}

</script>	

<br/>
<br/>
<table class='table_Cadre'>
	<tr>
		<td colspan	= '2' 
			class	= 'td_LibelleTitre'>
			Ecran d'identification
		</td>
	</tr>

	<tr>
		<td>
			<br/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Identifiant (email) :
		</td>
		<td>
			<input type		= 'text'
					name	= 'ed_Mail'
					id		= 'ed_Mail'
					class	= 'input_Usuel'
					value	= '<?PHP if (IsSet($_COOKIE["SCIEF_Login"])==true){Echo $_COOKIE["SCIEF_Login"];} ?>'/>
		</td>
	</tr>
	<tr>
		<td>
			<br/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Mot de passe :
		</td>
		<td>
			<input type		= 'password'
					name	= 'ed_PW'
					id		= 'ed_PW'
					class	= 'input_Usuel'
					value	= '<?PHP if (IsSet($_COOKIE["SCIEF_PW"])==true){Echo $_COOKIE["SCIEF_PW"];} ?>'/>
		</td>
	</tr>
	<tr>
		<td>
			<br/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Connexion automatique :
		</td>
		<td>
			<input type		= 'checkbox'
					name	= 'cb_Login'
					id		= 'cb_Login'
					class	= 'input_Usuel'
					<?PHP if (IsSet($_COOKIE["SCIEF_PW"])==true){Echo "checked='true'";} ?> />
		</td>
	</tr>
	<tr>
		<td>
			<br/>
		</td>
	</tr>	
	<tr>
		<td colspan = '2' align='center'>
			<a 	href="javascript:LOGIN_Traite()" >
				<img src='Images/BoutonsVerticaux/btn_Connexion.png' />
			</a>
		</td>
	</tr>
</table>
<br/>
<br/>
<br/>
<div class='CADRE_Contexte' >
	<?PHP Echo $LO_IAD->AfficheTexte("COOKIES");?>
</div>

