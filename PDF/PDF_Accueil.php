<?PHP 
	// charger les informations à partir du fichier XML
	if (TOOL_SessionLire("SESSION", "SCI_CTX") == "DEMO")
	{
		$LC_XML="Data/Demonstrations/";
		$LC_Alias =TOOL_SessionLire("SESSION", "SCI_ALIAS");
		$LC_XML = $LC_XML . "SIM_" . $LC_Alias . ".xml";
	}
	if (TOOL_SessionLire("SESSION", "SCI_CTX") == "REEL")
	{
		$LC_XML="Data/XML/";
		$LC_Alias =TOOL_SessionLire("SESSION", "SCI_ALIAS");
		$LC_XML.= $LC_Alias;
	}

	$LO_XML  = simplexml_load_file($LC_XML);
	$LC_INV_Nom     = $LO_XML->investisseur->nom;
	$LC_INV_Prenom  = $LO_XML->investisseur->prenom;
	$LC_SIM_Libelle = $LO_XML->simulation->nom;
	
	// vérifier
	$LC_OK   = BLOC_Verification($LC_XML);
	$LC_SQL  = "Select SIM_Alias, SIM_Libelle From sci_simulation ";
	$LC_SQL .= "Where SIM_ID = 0" . TOOL_SessionLire("SESSION", "SIM_ID");
	$LC_Resultat = SubStr($LC_OK, 2, 2000);
	$LC_OK       = SubStr($LC_OK, 0, 2);
?>	

<Script Language="JavaScript">
	function RESULTAT_RetourDossier(){
		var LC_URL = "SCI_EF_index.php?page=simulation";
		LC_URL=LC_URL + "&id=<?PHP Echo TOOL_SessionLire("SESSION", "SIM_ID"); ?>";
		LC_URL=LC_URL + "&invid=<?PHP Echo TOOL_SessionLire("SESSION", "EMP_ID"); ?>";
		window.location=LC_URL;	
	}

	function Global_Retour()
		{
			window.location="SCI_EF_index.php?page=liste";
		}
		
	function RESULTAT_AchatTickets()
		{
			window.location="SCI_EF_index.php?page=achattickets";
		}
		
	function RESULTAT_Ajax(PC_Fonction, PB_CTRL)
		{
			if ("<?PHP Echo $LC_OK;?>"!="OK")
			{
				if (PB_CTRL == true)
				{
					alert("Vous devez d'abord rectifier les éléments signalés en erreur");
					return;
				}
			}
			// attendre
			LO_BlocRetour = document.getElementById("id_Retour");
			LC_HTML = "<b><font color='red'>Votre état est en cours de formatage...</font></b>";
			LO_BlocRetour.innerHTML = LC_HTML;
			
			// URL Ajax
			var LC_URL = "";
			if (PC_Fonction == "PDF_Trace")       {LC_URL = "PDF/PDF_Moteur.php?cle=tracemoteur&";}
			if (PC_Fonction == "PDF_Synthese")    {LC_URL = "PDF/PDF_Synthese.php?option=PDFSYNTHESE";}
			if (PC_Fonction == "PDF_Rentabilite") {LC_URL = "PDF/PDF_GrapheRentabilite.php?option=PDF_Rentabilite";}
			if (PC_Fonction == "PDF_BilanAnnuel") {LC_URL = "PDF/PDF_BilanAnnuel.php?option=PDFBILANA";}
			if (PC_Fonction == "PDF_BilanMensuel"){LC_URL = "PDF/PDF_BilanMensuel.php?option=PDFBILANM";}
			LC_URL = LC_URL + "&id=<?PHP echo TOOL_SessionLire("SESSION", "SIM_ID");?>"
			LC_URL = LC_URL + "&logid=<?PHP echo TOOL_SessionLire("SESSION", "USER_ID");?>"
			LC_URL = LC_URL + "&xml=<?PHP echo $LC_XML; ?>"

			// implementer en fonction du navigateur
			var xhr=null;
			if (window.XMLHttpRequest) { 
				xhr = new XMLHttpRequest();
			}
			else if (window.ActiveXObject) 
				{
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
			//on dÃ©finit l'appel de la fonction au retour serveur
			xhr.onreadystatechange = function() {TRT_Ajax(PC_Fonction, xhr); };

			//on appelle le fichier reponse.txt
			xhr.open("GET", LC_URL, true);
			xhr.send(null);
		}
	
	function TRT_Ajax(PC_Fonction, xhr)
		{
			if (xhr.readyState==4) 
			{
				var LO_XML= xhr.responseXML;
				if (LO_XML != null)
				{
					var LO_Data   = LO_XML.getElementsByTagName("valeur")[0];
					if (LO_Data.childNodes[0].nodeValue == "KO")
					{
						LO_BlocRetour = document.getElementById("id_Retour");
						LC_HTML = "Votre solde de tickets de vous permet pas de demander ce document.";
						LO_BlocRetour.innerHTML = LC_HTML;
						return;
					}
				}
				if (PC_Fonction == "PDF_Trace")        {RESULTAT_AfficheTraceRetour(xhr);}
				if (PC_Fonction == "PDF_Synthese")     {RESULTAT_AffichePageSynthese(xhr);}
				if (PC_Fonction == "PDF_Rentabilite")  {RESULTAT_AffichePageRentabilite(xhr);}
				if (PC_Fonction == "PDF_BilanMensuel") {RESULTAT_AffichePageBilanMensuel(xhr);}
				if (PC_Fonction == "PDF_BilanAnnuel")  {RESULTAT_AffichePageBilanAnnuel(xhr);}
			}		
		}
		
	function RESULTAT_AfficheTraceRetour(xhr)
	{
		LO_BlocRetour = document.getElementById("id_Retour");
		LC_HTML = "Vous venez de demander une trace de contrôle. Votre résultat est <a href='";
		LC_HTML = LC_HTML + "Ajax/TraceMoteur_";
		LC_HTML = LC_HTML + "<?PHP echo TOOL_SessionLire("SESSION", "SIM_ID"); ?>";
		LC_HTML = LC_HTML + ".csv' target=_blank>ici</a>";
		LO_BlocRetour.innerHTML = LC_HTML;
	}
	
	function RESULTAT_AffichePageSynthese(xhr)
	{
		LO_BlocRetour = document.getElementById("id_Retour");
		LC_HTML = "Vous venez de demander une page de synthèse de votre étude. Votre résultat est <a href='";
		LC_HTML = LC_HTML + "Ajax/PDF_Synthese_";
		LC_HTML = LC_HTML + "<?PHP echo TOOL_SessionLire("SESSION", "SIM_ID"); ?>";
		LC_HTML = LC_HTML + ".pdf' target=_blank>ici</a>";
		LO_BlocRetour.innerHTML = LC_HTML;
	}
	
	function RESULTAT_AffichePageRentabilite(xhr)
	{
		LO_BlocRetour = document.getElementById("id_Retour");
		LC_HTML = "Vous venez de demander le graphique de rentabilité de votre étude. Votre résultat est <a href='";
		LC_HTML = LC_HTML + "Ajax/PDF_Rentabilite_";
		LC_HTML = LC_HTML + "<?PHP echo TOOL_SessionLire("SESSION", "SIM_ID"); ?>";
		LC_HTML = LC_HTML + ".pdf' target=_blank>ici</a>";
		LO_BlocRetour.innerHTML = LC_HTML;
	}

	function RESULTAT_AffichePageBilanAnnuel(xhr)
	{
		LO_BlocRetour = document.getElementById("id_Retour");
		LC_HTML = "Vous venez de demander un bilan annuel de votre étude. Votre résultat est <a href='";
		LC_HTML = LC_HTML + "Ajax/PDF_BilanAnnuel_";
		LC_HTML = LC_HTML + "<?PHP echo TOOL_SessionLire("SESSION", "SIM_ID"); ?>";
		LC_HTML = LC_HTML + ".pdf' target=_blank>ici</a>";
		LO_BlocRetour.innerHTML = LC_HTML;
	}
	
	function RESULTAT_AffichePageBilanMensuel(xhr)
	{
		LO_BlocRetour = document.getElementById("id_Retour");
		LC_HTML = "Vous venez de demander un bilan mensuel de votre étude. Votre résultat est <a href='";
		LC_HTML = LC_HTML + "Ajax/PDF_BilanMensuel_";
		LC_HTML = LC_HTML + "<?PHP echo TOOL_SessionLire("SESSION", "SIM_ID"); ?>";
		LC_HTML = LC_HTML + ".pdf' target=_blank>ici</a>";
		LO_BlocRetour.innerHTML = LC_HTML;
	}
	
	function RESULTAT_RLV()
	{
		window.location = "SCI_EF_Index.php?page=RLV";
	}

</script>	

&nbsp;<br/>
&nbsp;<br/>

<div class = 'td_LibelleTitre td_LibelleTitreTravaux'>
	Génération des synthèses de résultats
</div>
&nbsp;<br/>
&nbsp;<br/>

<div style='float:left;width:100%'>
	<div class = 'DI5050_Gauche' style='text-align:center'>
		<table class='table_Cadre'>
			<tr>
				<td class='td_SaisieLibelle'>
					Investisseur :
				</td>
				<td>
					<input  type	= 'text'
							readonly= 'true'
							class	= 'input_Usuel'
							value	= '<?PHP Echo $LC_INV_Prenom . ' ' . $LC_INV_Nom; ?>' />
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Simulation :
				</td>
				<td>
					<input  type	= 'text'
							readonly= 'true'
							class	= 'input_Usuel'
							value	= '<?PHP Echo $LC_SIM_Libelle;?>' />

				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Votre solde de tickets :
				</td>
				<td>
					<input  type	= 'text'
							readonly= 'true'
							class	= 'input_Usuel'
							value	= '<?PHP echo CalculSolde();?>' />
				</td>					
			</tr>
			<tr>
				<td colspan='2' style='text-align:center;'>
					<br/>
					<?PHP if ($_SESSION["USER_STATUT"] == 'SUPER'){ ?>
					<li><a 	href="javascript:RESULTAT_Ajax('PDF_Trace', false)">
							Export de données
						</a>
					<?PHP } ?>
					<li><a 	href="javascript:RESULTAT_Ajax('PDF_Synthese', true)">
							Fiche de Synthèse
						</a>	
					<li><a 	href="javascript:RESULTAT_Ajax('PDF_Rentabilite', true)">
							Graphe de rentabilité
						</a>	
					<li><a 	href="javascript:RESULTAT_Ajax('PDF_BilanMensuel', true)">
							Bilan mensuel
						</a>	
					<li><a 	href="javascript:RESULTAT_Ajax('PDF_BilanAnnuel', true)">
							Bilan annuel
						</a>
				</td>
			</tr>
		</table>
	</div>
	<div class = 'DI5050_Droite' 
		 style="height:200px;overflow-y: scroll;">
		<div id= 'id_Retour'>
			<p style="padding-left:20px;">
			<?PHP Echo $LC_Resultat; ?>
			</p>
		</div>
		&nbsp;<br/>
		<div>
			<?PHP if (TOOL_SessionLire("SESSION", "SCI_CTX") == "REEL"){?>
			<a 	href="javascript:RESULTAT_AchatTickets()">
				<img src='Images/BoutonsFonctionnement/btn_AcheterTickets.png'/>
			</a>
			<a 	href="javascript:RESULTAT_RLV()" >
				<br/>
				<img src='Images/BoutonsFonctionnement/btn_RLV.png' />
			</a>
			<?PHP }?>
		</div>
	</div>
</div>
&nbsp;<br/>
&nbsp;<br/>
<div class='CADRE_Contexte' >
	<?PHP //Echo $LO_IAD->AfficheTexte("FICHES");?>
</div>

<?PHP
function CalculSolde()
{
Global $LO_IAD;

	$LC_SQL  = "Select Sum(TI_Achat) - Sum(TI_Utilise) as Solde ";
	$LC_SQL .= "from sci_tickets ";
	$LC_SQL .= "where LOG_ID = 0" . TOOL_SessionLire("SESSION", "USER_ID");
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
	return $LT_Ligne["Solde"];
}
?>