<Script Language="JavaScript">
	function LOGINOUBLI_CTRL()
	{
		var LC_Message = "";
		if (document.getElementById("ed_OubliLogin").value == "")
		{
			LC_Message = LC_Message + "Le mail de connexion n'a pas été indiqué \n";
		}
		if (LC_Message == "")
		{
			return true;
		}
		else
		{
			alert(LC_Message);
			return false;
		}
	}
	
	function LOGINOUBLI_Valide()
	{
		if (LOGINOUBLI_CTRL())
		{
			document.getElementById("ed_Phase").value = "OubliLOGIN";
			document.fo_SCIEF.submit();
		}

	}

</script>	

<br/>
<br/>
<table class='table_Cadre'>
	<tr>
		<td colspan	= '2' 
			class	= 'td_LibelleTitre'>
			Mot de passe oublié
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			<br/>
			<br/>
			Adresse email
		</td>
		<td>
			<br/>
			<br/>
			<input type		= 'text'
					name	= 'ed_OubliLogin'
					id		= 'ed_OubliLogin'
					size	= '50'
					maxlength='50'
					class	= 'input_Usuel'/>
		</td>
	</tr>
	<tr>
		<td colspan = '2' align='center'>
			<br/>
			<br/>
			<br/>
			<a 	href="javascript:LOGINOUBLI_Valide()" >
				<img src='Images/BoutonsFonctionnement/btn_OubliLogin.png' />
			</a>
		</td>
	</tr>
</table>
<br/>
<br/>
<br/>
<div class='CADRE_Contexte' >
	<?PHP Echo $LO_IAD->AfficheTexte("INFOOUBLI");?>
</div>

