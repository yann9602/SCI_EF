<Script Language="JavaScript">
	function LOGINCREE_CTRL()
	{
		var LC_Message = "";
		if (document.getElementById("ed_MailLogin").value == "")
		{
			LC_Message = LC_Message + "Le mail de connexion n'a pas été indiqué \n";
		}
		if (document.getElementById("ed_CreePW").value == "")
		{
			LC_Message = LC_Message + "Le mot de passe n'a pas été indiqué \n";
		}
		if (document.getElementById("ed_CreePW").value != document.getElementById("ed_ConfirmPW").value)
		{
			LC_Message = LC_Message + "Le mot de passe n'a pas été confirmé correctement \n";
		}
		if ((document.getElementById("rd_TypeLogin1").checked == false) && 
		    (document.getElementById("rd_TypeLogin2").checked == false)) 
		{
			LC_Message = LC_Message + "Le type d'utilisateur n'est pas défini \n";
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
	
	function LOGINCREE_Valide()
	{
		if (LOGINCREE_CTRL())
		{
			if (document.getElementById("rd_TypeLogin1").checked){document.getElementById("ed_TypeLogin").value="IND";}
			if (document.getElementById("rd_TypeLogin2").checked){document.getElementById("ed_TypeLogin").value="PRO";}
			document.getElementById("ed_Phase").value = "CreeLOGIN";
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
			Création d'un compte
		</td>
	</tr>

	<tr>
		<td>
			<br/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Adresse email
		</td>
		<td>
			<input type		= 'text'
					name	= 'ed_MailLogin'
					id		= 'ed_MailLogin'
					class	= 'input_Usuel'/>
		</td>
	</tr>
	<tr>
		<td>
			<br/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Mot de passe 
		</td>
		<td>
			<input type		= 'password'
					name	= 'ed_CreePW'
					id		= 'ed_CreePW'
					class	= 'input_Usuel'/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Confirmer le mot de passe
		</td>
		<td>
			<input type		= 'password'
					name	= 'ed_ConfirmPW'
					id		= 'ed_ConfirmPW'
					class	= 'input_Usuel'/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'
			colspan = '2'
			style='text-align:center;'>
			<input type		= 'hidden'
					name	= 'ed_TypeLogin'
					id		= 'ed_TypeLogin'/>
			<input type		= 'radio'
					name	= 'rd_TypeLogin'
					id		= 'rd_TypeLogin1'/>
			utilisateur individuel
			<input type		= 'radio'
					name	= 'rd_TypeLogin'
					id		= 'rd_TypeLogin2'/>
			utilisateur professionnel
		</td>
	</tr>
	<tr>
		<td colspan = '2' align='center'>
			<br/><br/>
			<a 	href="javascript:LOGINCREE_Valide()" >
				<img src='Images/BoutonsFonctionnement/btn_CreerLogin.png' />
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

