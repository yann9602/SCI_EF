<?PHP
Session_Start();
header('Content-Type: text/html; charset=ISO-8859-1'); 
ini_set( 'default_charset', 'ISO-8859-1' );
require('SCI_EF.inc');
?>
<html>
	<head>
		<title>Simulation Investissement locatif</title>
		<meta http-equiv="Expires" CONTENT="0"> 
		<meta http-equiv="Cache-Control" CONTENT="no-cache"> 
		<meta http-equiv="Pragma" CONTENT="no-cache">

		<LINK	REL	= StyleSheet 
				Type	= Text/css
				HREF	= "SCI_EF.css">

		<Script Language="JavaScript">	
			function TOOL_FiltreSaisie(PC_Type, PO_Evenement){
				var LC_Filtre = chr(9);
				if (PC_Type == "ENTIER"){LC_Filtre = "1234567890";}
				if (PC_Type == "DECIMAL"){LC_Filtre = "1234567890.";}
				if (LC_Filtre.indexOf(PO_Evenement.key) >= 0) 
					{
						return true;
					}
				else
					{
						return false;
					} 
			}
			
			function TOOL_Ajax(PC_Fonction, PC_Parametres)
				{
					// objet à gérer dynamiquement
					var LC_URL =" <?PHP Echo Def_URL;?>" + 
								"SCI_EF.php" +
								"?cle=" + PC_Fonction +
								"&param=" + PC_Parametres;
					// implementer en fonction du navigateur
					var xhr=null;
					if (window.XMLHttpRequest) { 
						xhr = new XMLHttpRequest();
					}
					else if (window.ActiveXObject) 
						{
							xhr = new ActiveXObject("Microsoft.XMLHTTP");
						}
					//on définit l'appel de la fonction au retour serveur
					xhr.onreadystatechange = function() {TRT_Ajax(xhr); };

					//on appelle le fichier reponse.txt
					xhr.open("GET", LC_URL, true);
					xhr.send(null);
				}

				
			function TRT_Ajax(xhr)
				{
				    if (xhr.readyState==4) 
						{
						var LO_XML= xhr.responseXML;
						var LO_Data = LO_XML.getElementsByTagName("image")
						var LC_Image= LO_Data[0].firstChild.nodeValue
						document.getElementById("IMG_Voir").src = "Ajax/" + LC_Image;
						}		
				}
		</Script>
	</head>
	
	<body onload='ONGLETS_Select("SAISIE_Financement")'>
	<form	name	= "fo_SCIEF"
			method	= "POST"
			action	= "SCI_EF.php">
		<input type='hidden' id='ed_Phase' name='ed_Phase' />  
		<table class='table_Cadre'>
			<tr>
				<td colspan="2">
					<?PHP Include "Principal/ecr_Entete.php"; ?>
				</td>	
			</tr>
			<tr>
				<td>
					<table class='table_Cadre'>
						<tr>
							<td class='td_BarreMenu'>
								<input type = 'button'
									class	= 'button_Usuel'
									name	= 'btn_Liste'
									id	= 'btn_Liste'
									value	= 'Classeur' />
							</td>
							<td class='td_BarreMenu'>
								<input type = 'button'
									class	= 'button_Usuel'
									name	= 'btn_RLV'
									id	= 'btn_RLV'
									value	= 'Relevé utilisations' />
							</td>
							<td class='td_BarreMenu'>
								<input type = 'button'
									class	= 'button_Usuel'
									name	= 'btn_Profil'
									id	= 'btn_Profil'
									value	= 'Profil utilisateur' />
							</td>
						</tr>
					</table>
				</td>
			<tr>
				<td width="85%">
					<?PHP 
						if (isSet($_SESSION["USER_ID"]))
							{Include "Simulations/ecr_Simulation.php";}	
						else
							{Include "Login/ecr_Droits.php";}	
					?>	
				</td>	
				<td width="15%"
					valign="top">
					<?PHP Include "Principal/ecr_Actualites.php"; ?>
				</td>
			</tr>
		</table>
	</form>	
	</body>
	
	<footer>
		<?PHP Include "Principal/ecr_Pied.php"; ?>
	</footer>	
<html>