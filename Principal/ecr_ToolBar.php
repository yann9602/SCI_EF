<div style="background-color: #BBBBBB; float:left; width:100%;">
<table>
	<tr>
		<td>&nbsp;</td>
		<?PHP 
		if (stripos("-investisseur-simulation-PDF_Accueil", TOOL_SessionLire("SESSION", "SCI_Page"))){
			// bouton retour
			echo "<td>";
			echo "<a href=\"javascript:Global_Retour()\"> ";
			echo "<img src='Images/BoutonsFonctionnement/btn_RetourListe.png' width='80%'/>";
			echo "</a> \n";
			echo "</td>";
		}	
		if (stripos("-PDF_Accueil", TOOL_SessionLire("SESSION", "SCI_Page"))){
			// bouton retour
			echo "<td>";
			echo "<a href=\"javascript:RESULTAT_RetourDossier()\"> ";
			echo "<img src='Images/BoutonsFonctionnement/btn_RetourSimu.png' width='80%'/>";
			echo "</a> \n";
			echo "</td>";
		}	
		if (StriPos("-simulation-", TOOL_SessionLire("SESSION", "SCI_Page"))){		
			// bouton dupliquer	
			echo "<td>";
			echo "<a href=\"javascript:Global_Dupliquer()\"> ";
			echo "<img src='Images/BoutonsFonctionnement/btn_Dupliquer.png' width='80%'/>";
			echo "</a> \n";
			echo "</td>";
		}	
		if (StriPos("-simulation-investisseur-login-", TOOL_SessionLire("SESSION", "SCI_Page"))){		
			// bouton enregistrer
			echo "<td>";
			echo "<a href=\"javascript:Global_Valider()\"> ";
			echo "<img src='Images/BoutonsFonctionnement/btn_Enregistrer.png' width='80%'/>";
			echo "</a> \n";
			echo "</td>";
		}	
		if (StriPos("-simulation-investisseur-", TOOL_SessionLire("SESSION", "SCI_Page"))){				
			// bouton effacer
			echo "<td>";
			echo "<a href=\"javascript:Global_Effacer()\"> ";
			echo "<img src='Images/BoutonsFonctionnement/btn_Effacer.png' width='80%'/>";
			echo "</a> \n";
			echo "</td>";
		}
		if (StriPos("-simulation-", TOOL_SessionLire("SESSION", "SCI_Page"))){				
			// bouton PDF
			echo "<td>";
			echo "<a href=\"javascript:Global_PDF()\"> ";
			echo "<img src='Images/BoutonsFonctionnement/btn_Resultats.png' width='80%'/>";
			echo "</a> \n";
			echo "</td>";
		}
		?>
		<td>
			<div id='di_Message'>
			<?PHP 
				if (IsSet($_SESSION["SCI_Message"])) {
					echo $_SESSION["SCI_Message"];
					$_SESSION["SCI_Message"]="";
				}
			?>
			</div>
		</td>				
	<tr>
</table>
</div>