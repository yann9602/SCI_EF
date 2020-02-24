<Script Language="JavaScript">
	function Global_Init()
		{
			var LC_Onglet="<?PHP Echo TOOL_SessionLire("SESSION", "EMP_ID"); ?>";
			LISTE_AfficheOnglets(LC_Onglet);
		}
		
	function Global_Retour()
		{
		}
		
	function LISTE_AfficheOnglets(PC_Onglet)
		{
			// cacher ls investisseurs et 'afficher que celui sélectionné
			<?PHP LISTE_ID(); ?>
			document.getElementById("diINV_" + PC_Onglet).style.display='block' 
		}
		
	function LISTE_AfficheDetail(PC_Onglet)
		{
			// cacher ls investisseurs et 'afficher que celui sélectionné
			if (document.getElementById(PC_Onglet).style.display=='none')
				{
					document.getElementById(PC_Onglet).style.display='block' 
				}
				else
				{
					document.getElementById(PC_Onglet).style.display='none' 
				}				
		}

		function LISTE_CreeInvestisseur()
		{
			window.location="index.php?page=investisseur&id=0";
		}

</Script>
		
<input type='text' id='ed_Phase' name='ed_Phase' value=''/> 		
<table class='table_Cadre'>
	<tr>
		<td align='center'
			valign='middle'>
			<br/><br/><br/><br/><br/><br/>
			<a href="javascript:LISTE_CreeInvestisseur()" >
				<img src='Images/BoutonsFonctionnement/im_Investisseur.png' />
			</a>
			<input 	type='button' 
					style='width:100px' 
					value='Nouvel investisseur' 
					onclick='LISTE_CreeInvestisseur()' />

		</td>
		<td class='td_separation' 
			rowspan='20'>
		</td>
		<td class='td_BlocSimulation'>
			<table class='table_Cadre'>
				<tr>
					<td colspan	= '3' 
						class	= 'td_LibelleTitre'>
							Liste des investisseurs
							<img src='Images/BoutonsFonctionnement/im_Investisseur.png' />
					</td>
				</tr>	
				<tr><td><br/></td></tr>		
			<?PHP LISTE_Portefeuille(); ?>
			</table>
		</td>
	</tr>
</table>

<?PHP

function LISTE_ID()
{
Global $LO_IAD;
	// construire un code Javascript pour effacer tout les détails d'investisseurs
	$LC_SQL  = "SELECT EMP_ID, ";
	$LC_SQL .= "(select count(*) from sci_simulation ";
	$LC_SQL .= "        where sci_simulation.EMP_ID = sci_emprunteurs.EMP_ID) as NbSim ";
	$LC_SQL .= "from sci_emprunteurs ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			if ($LT_Ligne['NbSim'] > 0)
				{
					Echo "document.getElementById('diINV_" . $LT_Ligne['EMP_ID'] . "').style.display='none' \n";
				}	
		}	
}

	
function LISTE_Portefeuille()
{
Global $LO_IAD;

	// charger un tableau avec les emprunteurs à afficher
	$LT_Liste = Array();
	$LC_SQL  = "SELECT EMP_ID, Concat(EMP_Nom, ' ', EMP_Prenom) as EMP_Nom, EMP_Type, ";
	$LC_SQL .= "(select count(*) from sci_simulation ";
	$LC_SQL .= "        where sci_simulation.EMP_ID = sci_emprunteurs.EMP_ID) as NbSim ";
	$LC_SQL .= "from sci_emprunteurs ";
	$LC_SQL .= "order by EMP_Nom ";	
	$LO_IAD->ExecuteSQL($LC_SQL);
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			if ($LT_Ligne['NbSim'] > 0)
				{
					$LT_Liste[] = $LT_Ligne;
				}	
		}	

	// pour le tableau chargé afficher 
	for ($i=0; $i<Count($LT_Liste);$i++)
		{
			// image à afficher
			$LC_Image = "";
			if ( $LT_Liste[$i]['EMP_Type']=="PHY"){$LC_Image = "Images/BoutonsFonctionnement/im_Personne.png";}
			if ( $LT_Liste[$i]['EMP_Type']=="SCI"){$LC_Image = "Images/BoutonsFonctionnement/im_SCI.png";}
			// ligne investisseur
			echo "<tr> \n";
			echo "	<td align='center' width='10px'> \n";
			echo "		<a href='javascript:LISTE_AfficheOnglets(" . $LT_Liste[$i]['EMP_ID'] . ")'";
			echo "			class='liste_Detail'>+</a> \n";
			echo "	</td> \n";
			echo "	<td width='20px'> \n";
			echo "		<img src='" . $LC_Image . "' \> \n";;
			echo "	</td> \n";				
			echo "	<td width='*'> \n";	
			echo "		<a class='liste_Rupture'href='index.php?page=investisseur&id=" . $LT_Liste[$i]['EMP_ID'] . "'>";
			echo 			$LT_Liste[$i]['EMP_Nom'];
			echo "		</a> \n";
			echo "	</td> \n";
			echo "</tr> \n";
			// blocs de détail
			echo "<tr> \n";
			echo "	<td></td> \n";
			echo "	<td colspan='2'> \n";
					echo "<div id='diINV_" . $LT_Liste[$i]['EMP_ID'] . "' style='display:none;width:100%;'> ";
					LISTE_BlocModifInvestisseur($LT_Liste[$i]['EMP_ID']);
					LISTE_DetailSimulations('SIM', $LT_Liste[$i]['EMP_ID']);
					//LISTE_DetailSimulations('ANA', $LT_Liste[$i]['EMP_ID']);
					echo "</div> \n";
			echo "	</td> \n";
			echo "</tr> \n";
		}
}


function LISTE_DetailSimulations($PC_Categorie, $PC_ID)
{
Global $LO_IAD;
	// s�lectionner les lignes � afficher
	$LC_SQL  = "SELECT SIM_ID, EMP_ID, SIM_Alias, SIM_Libelle ";
	$LC_SQL .= "from sci_simulation ";
	$LC_SQL .= "where EMP_ID = " . $PC_ID . " ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	
	// tete HTML de tete de bloc de détail général
	$LC_Lien = '"taDET_' . $PC_Categorie . "_" . $PC_ID . '"';
	echo "<table class='table_Cadre'> \n";
	echo "	<tr> \n";
	echo "		<td width='3px'>&nbsp;</td> \n";
	echo "		<td width='3px'> \n";
	echo "			<a href='javascript:LISTE_AfficheDetail(" . $LC_Lien . ")'>+</a> \n";
	echo "		</td> \n"; 	
	echo "		<td colspan='2'> \n";	
	if ($PC_Categorie == "SIM"){Echo "Simulations";}	
	//   ($PC_Categorie == "ANA"){Echo "Analyse de projet";}	
	echo "		</td> \n";
	echo "	</tr> \n";
		
	// 	
	echo "	<tr> \n"; 	
	echo "		<td width='3px'></td> \n";					
	echo "		<td width='3px'></td> \n";
	echo "		<td> \n"; 		
	
	// tete HTML de tete de bloc	
	echo "<div id=" . $LC_Lien . "style='display:none;'>";	
	echo "<table class='table_Cadre'> \n";
	
	// boucle de lecture sur les simulations de cet investisseur
	if ($PC_Categorie == "SIM"){LISTE_BlocCreeSimulation($PC_ID);}
	for ($recno = 0; $recno < $LO_IAD->NombreLignes(); $recno++) 
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			echo "<tr> \n";
			echo "<td> \n";					
			echo "<a class='liste_Detail'
					 href='index.php?page=simulation" . 
							"&id="    . $LT_Ligne['SIM_ID'] . 
							"&invid=" . $LT_Ligne['EMP_ID'] .							
							"&alias=" . $LT_Ligne['SIM_Alias'] .
							"'>";
			echo $LT_Ligne['SIM_Libelle'];
			echo "</a>";
			echo "</td>";
			echo "</tr> \n";
		}
	echo "</table> \n";	
	echo "</div> \n";
	
	// fermer le tbleau de détail général
	echo "	</td> \n";		
	echo "		</tr> \n";			
	echo "</table> \n";	
}

function LISTE_BlocModifInvestisseur($PC_ID)
{
	echo "<table width='100%'> \n";
	echo "	<tr> \n";
	echo "		<td width='3px'>&nbsp;</td> \n";					
	echo "		<td colspan='2'> \n"; 
	echo "			<a class='liste_Rupture' href='index.php?page=investisseur&id=" . $PC_ID . "'>";
	echo "			Modifier l'investisseur";
	echo "			</a> \n";
	echo "		</td> \n"; 
	echo "	</tr> \n"; 
	echo "</table> \n";
}

function LISTE_BlocCreeSimulation($PC_ID)
{
	echo "	<tr> \n";
	echo "		<td colspan='2'> \n"; 
	echo "			<a class='liste_Rupture' href='index.php?page=simulation&alias=&invid=" . $PC_ID . "'>";
	echo "			Ajouter une simulation";
	echo "			</a> \n";
	echo "		</td> \n"; 
	echo "	</tr> \n"; 
}
?>