<Script Language="JavaScript">
	
	function Global_Init(){
	}

	function LOGIN_Onglets(PC_Onglet){
		if (PC_Onglet == "DROITS_CreeLogin")
		{
			alert("cette fonctionnalité est provisoirement neutralisée");
			return;
		}

		document.getElementById("DROITS_CreeLogin").style.display = 'none';
		document.getElementById("DROITS_ModifLogin").style.display = 'none';
		document.getElementById("DROITS_OubliLogin").style.display = 'none';
		document.getElementById("DROITS_Login").style.display    = 'none';
		document.getElementById(PC_Onglet).style.display    = 'block';
	}
	
	function LOGIN_Demonstration(){
		window.location = "Simulations/ecr_Demonstration.php";
	}
</script>	

<table class='table_Cadre'>
	<tr>
		<td align='center'
			valign='top'>
			<br/>
			<a 	href="javascript:LOGIN_Onglets('DROITS_CreeLogin')">
				<img src='Images/BoutonsVerticaux/btn_Inscription.png' width='90%'/>
			</a>
			<br/>
			<a 	href="javascript:LOGIN_Onglets('DROITS_OubliLogin')">
				<img src='Images/BoutonsVerticaux/btn_OubliMDP.png' width='90%'/>
			</a>
			<br/>
			<a 	href="javascript:LOGIN_Demonstration()">
				<img src='Images/BoutonsVerticaux/btn_Demo.png' width='90%'/>
			</a>
		</td>
			<td class='td_separation' 
			rowspan='20'>
		</td>
		<td class='td_BlocSimulation'>
			<table class='table_Cadre'>
				<tr>
					<td id='PANEL_Saisir' width='70%'>
						<div id='DROITS_Login' style='display:<?PHP echo LOGIN_Affiche("Login");?>'>
							<?PHP if (IsSet($_SESSION["LOG_Option"]) == false)
							{
								include "ecr_Login.php";
							}
							?>
						</div>	
						<div id='DROITS_CreeLogin' style='display:<?PHP echo LOGIN_Affiche("CreeLogin");?>'>
							<?PHP include "ecr_CreeLogin.php";?>
						</div>
						<div id='DROITS_ModifLogin' style='display:<?PHP echo LOGIN_Affiche("ModifLogin");?>'>
							<?PHP if (TOOL_SessionLire("SESSION", "USER_ID") <> "" )
							{
								include "ecr_ModifLogin.php";
							}
							?>
						</div>
						<div id='DROITS_OubliLogin' style='display:<?PHP echo LOGIN_Affiche("OubliLogin");?>'>
							<?PHP include "ecr_OubliPW.php";?>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?PHP
function LOGIN_Affiche($PC_Onglet)
{
	if (IsSet($_GET["page"])==false) 
	{
		if ($PC_Onglet=="Login")
		{
			return "block";
		}
		else
		{
			return "none";
		}
	}
	if ($_GET["page"]==$PC_Onglet)
		{
			return "block";
		}
		else
		{
			return "none";
		}
}