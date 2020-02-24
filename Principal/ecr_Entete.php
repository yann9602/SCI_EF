<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-78180274-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-78180274-3');
</script>

<Script Language="JavaScript">	
	function MENU_Affiche() {
		var x = document.getElementById("li_BarreMenu");
		if (x.className === "topMenu") {
			x.className += " responsive";
		} else {
			x.className = "topMenu";
		}
	}
</Script>

<div style = "	background-image	: url('Images/Entete/im_Ciel.png');
				background-attachment: fixed;
				background-repeat:no-repeat;
				background-size: cover;
				float:left; width:100%;">
				
	<!-- bandeau d'entte -->				
	<table class='table_Cadre'>
		<tr>
			<td width="3%"></td>
			<td>
				<span style	= "	font-weight	: bold;
								font-size	: xx-large;
								color 		: #294763;">
					Etude de rentabilité
				</span>
			</td>
			<td	rowspan="2"
				align  ="right">
				<img src="Images/Entete/im_Logo.png" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td class='td_EnteteTitre'>
				<span style	= "	font-weight	: bold;
								font-size	: xx-large;
								color 		: #E6FFFF;">		
					d'un investissement locatif
				</span>
			</td>
		</tr>
	</table>

	<!-- Menu général -->
	<div class="topMenu" id="li_BarreMenu">
		<?PHP if (TOOL_SessionLire("SESSION", "USER_MODE")=="ADMIN")
				{
					echo"<a href='SCI_EF_Index.php?page=admin'>Administrateur</a> \n"; 
				}
		?>
		<a href="SCI_EF_Index.php?page=NewLogin">Déconnecter</a>
		<a href="SCI_EF_Index.php?page=ModifLogin">Profil</a>
		<a href="SCI_EF_Index.php?page=liste">Investisseurs</a>
		<a href="SCI_EF_Index.php?page=PresentationAbout">About</a>
		<a href="javascript:void(0);" 
					style="font-size:15px;" 
					class="icon" 
					onclick="MENU_Affiche()">&#9776;</a>
	</div>
	<br/><br/>
	<div class="td_separation" 
		 style="width:100%;">
		&nbsp;
	</div>
</div>