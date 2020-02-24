<?PHP 
	$LT_Ligne="";
	LOGIN_Charge();
?>

<Script Language="JavaScript">
	function LOGINMODIF_CTRL()
	{
		var LC_Message = "";
		if (document.getElementById("ed_ModifLogin").value == "")
		{
			LC_Message = LC_Message + "Le mail de connexion n'a pas été indiqué \n";
		}
		if (document.getElementById("ed_ModifPW").value == "")
		{
			LC_Message = LC_Message + "Le mot de passe n'a pas été indiqué \n";
		}
		if (document.getElementById("ed_ModifPW").value != document.getElementById("ed_ConfirmPW2").value)
		{
			LC_Message = LC_Message + "Le mot de passe n'a pas été confirmé correctement \n";
		}
		if ((document.getElementById("rd_ModifTypeLogin1").checked == false) && 
		    (document.getElementById("rd_ModifTypeLogin2").checked == false)) 
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
	
	function LOGINMODIF_RLV()
	{
		window.location = "SCI_EF_Index.php?page=RLV";
	}
	
	function LOGINMODIF_Efface()
	{
		if (confirm("Attention toutes les informations concernant ce profil vont être effacées." + 
					"Voulez-vous continuer ?"))
		{	
			document.getElementById("ed_Phase").value = "EffaceLOGIN";
			document.fo_SCIEF.submit();
		};
	}
	
	function LOGINMODIF_Valide()
	{
		if (LOGINMODIF_CTRL())
		{
			if (document.getElementById("rd_ModifTypeLogin1").checked){document.getElementById("ed_ModifTypeLogin").value="IND";}
			if (document.getElementById("rd_ModifTypeLogin2").checked){document.getElementById("ed_ModifTypeLogin").value="PRO";}
			document.getElementById("ed_Phase").value = "ModifLOGIN";
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
			Modification de votre profil
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
					name	= 'ed_ModifLogin'
					id		= 'ed_ModifLogin'
					value	= '<?PHP Echo $LT_Ligne['LOG_Mail'];?>'
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
					name	= 'ed_ModifPW'
					id		= 'ed_ModifPW'
					class	= 'input_Usuel'/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Confirmer le mot de passe
		</td>
		<td>
			<input type		= 'password'
					name	= 'ed_ConfirmPW2'
					id		= 'ed_ConfirmPW2'
					class	= 'input_Usuel'/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'
			colspan = '2'
			style='text-align:center;'>
			<input type		= 'hidden'
					name	= 'ed_ModifTypeLogin'
					id		= 'ed_ModifTypeLogin'
					value	= '<?PHP Echo $LT_Ligne['LOG_Type'];?>'/>
			<input type		= 'radio'
					name	= 'rd_ModifTypeLogin'
					id		= 'rd_ModifTypeLogin1'
					<?PHP echo TOOL_DecodeCheck($LT_Ligne['LOG_Type'], "IND");?>/>
			utilisateur individuel
			<input type		= 'radio'
					name	= 'rd_ModifTypeLogin'
					id		= 'rd_ModifTypeLogin2'
					<?PHP echo TOOL_DecodeCheck($LT_Ligne['LOG_Type'], "PRO");?>/>
			utilisateur professionnel
		</td>
	</tr>
	<tr>
		<td colspan = '2' align='center'>
			<br/><br/>
			<a 	href="javascript:LOGINMODIF_Valide()" >
				<img src='Images/BoutonsFonctionnement/btn_Enregistrer2.png'	/>
			</a>
			<a 	href="javascript:LOGINMODIF_Efface()" >
				<img src='Images/BoutonsFonctionnement/btn_SupprimeCompte.png' />
			</a>
			<a 	href="javascript:LOGINMODIF_RLV()" >
				<img src='Images/BoutonsFonctionnement/btn_RLV.png' />
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

<?PHP
Function LOGIN_Charge()
{
Global $LT_Ligne, $LO_IAD;

	$LC_SQL  = "Select LOG_PW, LOG_Mail, LOG_Statut, LOG_Type ";
	$LC_SQL .= "From sci_login ";
	$LC_SQL .= "where LOG_ID =0" . TOOL_SessionLire("SESSION", "USER_ID");
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
}