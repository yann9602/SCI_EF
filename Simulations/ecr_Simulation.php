<?PHP
	$LO_XML  = "";
	$LB_XML  = False;
	$LB_XMLS = False;
	$LB_XMLI = False;
	$LC_SimulationXML   = ""; 
	$LC_InvestisseurXML = "";
	$LC_Action = "";
		
	if (TOOL_SessionLire("SESSION", "SCI_CTX") == "REEL")
	{
		SIMULATION_ChargeReel();
		$LC_Action = "ENREGISTREXML";
	}
	if (TOOL_SessionLire("SESSION", "SCI_CTX") == "DEMO")
	{
		SIMULATION_ChargeDemo();
		$LC_Action = "ENREGISTREDEMO";
	}

?>

<Script Language="JavaScript">
	LC_Onglet = "<?PHP Echo TOOL_SessionLire("SESSION", "SCI_Onglet");?>";
	LC_Action = "<?PHP Echo TOOL_SessionLire("SESSION", "SCI_CTX");?>";
	function Global_Init()
		{
			if (LC_Onglet == ""){LC_Onglet = "SAISIE_Financement"}
			ONGLETS_Select(LC_Onglet);
			INFOSBIENS_Init();
			if (LC_Action == "DEMO"){SIMULATION_DEMO();}
		}
		
	function Global_Retour()
		{
			window.location="SCI_EF_index.php?page=liste";
		}

	function Global_Valider()
		{
			if (SIMULATION_Ctrl() == false){return;}
			document.getElementById("ed_Phase").value="<?PHP echo $LC_Action; ?>";
			document.forms[0].submit();
		}
		
	function Global_PDF()
		{
			Global_Valider();
			alert("vos informations ont bien été enregistrées");
			window.location="SCI_EF_index.php?page=PDF_Accueil&onglet=" + LC_Onglet;
		}

	function Global_Effacer()
		{
			var LC_Message = "En poursuivant toutes les informations concernant " + 
							 "cette simulation seront définitivement perdues. \n"
							 "Voulez-vous poursuivre ?";
			if (confirm(LC_Message)==false){return;}
			document.getElementById("ed_Phase").value="EFFACEXML";
			document.forms[0].submit();
		}

	function Global_Dupliquer()
		{
			document.getElementById("ed_Phase").value="ENREGISTREXML";
			document.getElementById("ed_ID").value="0";
			document.getElementById("ed_Alias").value="";			
			document.getElementById("ed_EtudeNom").value="";			
		}
		
	function TOOL_PadL(n, width, z) {
		z = z || '0';
		n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
	}
	
	function TOOL_Ajax(PC_Fonction, PC_FonctionRetour, PC_Parametres, PB_Asynchrone)
		{
			var LC_URL = "";
			if (PC_Fonction.indexOf("graphe")>=0){LC_URL ="SCI_EF_AjaxGraphes.php";}
			if (PC_Fonction.indexOf("calcul")>=0){LC_URL ="SCI_EF_AjaxCalculs.php";}
			LC_URL = LC_URL + "?cle=" + PC_Fonction +
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
			xhr.onreadystatechange = function() {TRT_Ajax(PC_FonctionRetour, xhr); };

			//on appelle le fichier reponse.txt
			xhr.open("GET", LC_URL, PB_Asynchrone);
			xhr.send(null);
		}

		
	function TRT_Ajax(PC_FonctionRetour, xhr)
		{
			if (xhr.readyState==4) 
				{
					if (PC_FonctionRetour == "FINANCEMENT_AfficheGraphe") {
						FINANCEMENT_AfficheGrapheRetour(xhr);
					}
					if (PC_FonctionRetour == "FINANCEMENT_AfficheCalculs"){
						FINANCEMENT_AfficheCalculsRetour(xhr);
					}
					if (PC_FonctionRetour == "ENDETTEMENT_AfficheGraphe") {
						ENDETTEMENT_AfficheGrapheRetour(xhr);
					}
					if (PC_FonctionRetour == "INVESTISSEUR_AfficheCalculs"){
						INVESTISSEUR_AfficheCalculsRetour(xhr);
					}
					if (PC_FonctionRetour == "INVESTISSEUR_AfficheGraphe"){
						INVESTISSEUR_AfficheGrapheRetour(xhr);
					}
				}		
		}

	function ONGLETS_Select(PC_Onglet)
	{
		// memoriser
		LC_Onglet = PC_Onglet;
		// cacher
		document.getElementById("SAISIE_Investisseur").style.display="none";
		document.getElementById("SAISIE_Financement").style.display="none";
		document.getElementById("SAISIE_Travaux").style.display="none";
		document.getElementById("SAISIE_Charges").style.display="none";
		document.getElementById("SAISIE_Descriptif").style.display="none";
		document.getElementById("SAISIE_Fiscalite").style.display="none";
		// afficher
		document.getElementById(PC_Onglet).style.display="block";
		// par onglet
		if (PC_Onglet == "SAISIE_Financement") {FINANCEMENT_AfficheGraphe();FINANCEMENT_CalculTravaux(); FINANCEMENT_Reagir();}
		if (PC_Onglet == "SAISIE_Endettement") {ENDETTEMENT_AfficheGraphe();ENDETTEMENT_Reagir();}
		if (PC_Onglet == "SAISIE_Charges")     {CHARGES_Reagir();}
		if (PC_Onglet == "SAISIE_Descriptif")  {INFOSBIENS_Init();}
		if (PC_Onglet == "SAISIE_Fiscalite")   {FISCALITE_Init();}
	}

	function SIMULATION_DEMO()
	{
		var LC_Champs = "<?PHP Echo Def_DemoBlocage;?>";
		var LT_Champs = LC_Champs.split(";");
		for (i=0;i<LT_Champs.length;i++)
		{
			document.getElementById(LT_Champs[i]).readOnly = true;
		}
		for (i=1;i<15;i++)
		{
			document.getElementById("ed_TRAVAUX_Deductible"+i).readOnly = true;
			document.getElementById("ed_TRAVAUX_NonDeductible"+i).readOnly = true;
			document.getElementById("ed_TRAVAUX_Mobilier"+i).readOnly = true;
		}
	}
	
	function SIMULATION_Ctrl()
	{
		var LC_Message = "";
		LC_Message += TOOL_ControleChampObligatoire("ed_BlocBiens", "la liste des biens");
		LC_Message += TOOL_ControleChampObligatoire("ed_EtudeNom", "le nom de l'étude");
		if (LC_Message == "")
			{
				return true;
			}
		else
			{
				LC_Message += " est obligatoire";
				alert(LC_Message);
				return false;
			}	
	}
</script>
		
<input type='hidden' id='ed_ID' name='ed_ID' value='<?PHP Echo TOOL_SessionLire("GET", "id"); ?>'/> 
<input type='hidden' id='ed_InvestisseurID' name='ed_InvestisseurID' value='<?PHP Echo TOOL_SessionLire("SESSION", "EMP_ID"); ?>'/> 
<input type='hidden' id='ed_Alias' name='ed_Alias' value='<?PHP echo $LC_SimulationXML; ?>' />

<?PHP include "ecr_SaisieInfosChapeau.php";?>

&nbsp;<br/>
&nbsp;<br/>
<table class='table_Cadre'>
	<tr>
		<td align='center'
			valign='top'>
			<a 	href="javascript:ONGLETS_Select('SAISIE_Investisseur')">
				<img src='Images/BoutonsVerticaux/btn_Investisseur.png' width='90%'/>
			</a>
			<br/><br/>
			<a 	href="javascript:ONGLETS_Select('SAISIE_Financement')">
				<img src='Images/BoutonsVerticaux/btn_Financement.png' width='90%'/>
			</a>
			<br/><br/>
			<a 	href="javascript:ONGLETS_Select('SAISIE_Travaux')"> 
				<img src='Images/BoutonsVerticaux/btn_Travaux.png' width='90%'/>
			</a>
			<br/><br/>
			<a 	href="javascript:ONGLETS_Select('SAISIE_Descriptif')" >
				<img src='Images/BoutonsVerticaux/btn_Descriptif.png' width='90%'/>
			</a>			
			<br/><br/>
			<a 	href="javascript:ONGLETS_Select('SAISIE_Charges')" >
				<img src='Images/BoutonsVerticaux/btn_Charges.png' width='90%' />
			</a>			
			<br/><br/>			
			<a 	href="javascript:ONGLETS_Select('SAISIE_Fiscalite')" >
				<img src='Images/BoutonsVerticaux/btn_Fiscalite.png' width='90%' />
			</a>			
			<br/><br/>
		</td>
		<td class='td_separation' 
			rowspan='20'>
		</td>
		<td class='td_BlocSimulation'>
			<table class='table_Cadre'>
				<tr>
					<td id='PANEL_Saisir' width='70%'>
						<div id='SAISIE_Investisseur'>
							<?PHP include "ecr_SaisieInfosInvestisseur.php";?>
						</div>
						<div id='SAISIE_Financement'>
							<?PHP include "ecr_SaisieInfosFinancement.php";?>
						</div>
						<div id='SAISIE_Travaux'>
							<?PHP include "ecr_SaisieInfosTravaux.php";?>
						</div>
						<div id='SAISIE_Charges'>
							<?PHP include "ecr_SaisieInfosCharges.php";?>
						</div>
						<div id='SAISIE_Descriptif'>
							<?PHP include "ecr_SaisieInfosBien.php";?>
						</div>
						<div id='SAISIE_Fiscalite'>
							<?PHP include "ecr_SaisieFiscalite.php";?>
						</div>
					</td>				
				</tr>
			</table>	
		</td>
	</tr>
</table>

<?PHP
function SIMULATION_ChargeInvestisseur()
{
Global $LO_IAD;
	$LC_SQL  = "Select EMP_Nom, EMP_Prenom, SIM_Alias, EMP_Alias, SIM_Photo ";
	$LC_SQL .= "from sci_emprunteurs ";
	$LC_SQL .= "left join sci_simulation ";
	$LC_SQL .= "on sci_emprunteurs.EMP_ID=sci_simulation.EMP_ID ";
	$LC_SQL .= "and sci_simulation.SIM_ID=" . TOOL_SessionLire("GET", "id") . " ";
	$LC_SQL .= "Where sci_emprunteurs.EMP_ID = 0" . TOOL_SessionLire("SESSION", "EMP_ID");
	$LO_IAD->ExecuteSQL($LC_SQL);
	if ($LO_IAD->NombreLignes()>0){
		return $LO_IAD->EclateSQL("Noms");
	}	
}


function SIMULATION_Pointe()
{
Global $LO_IAD;
	$LC_SQL  = "Update sci_memoire Set ";
	$LC_SQL .= "MEM_Page = 'ecr_Liste.php', ";
	$LC_SQL .= "MEM_Option = 'SIM', ";
	$LC_SQL .= "LOG_ID   =0" . TOOL_SessionLire("SESSION", "USER_ID") . ", ";
	$LC_SQL .= "INV_ID   =0" . TOOL_SessionLire("SESSION", "EMP_ID") . ", ";
	$LC_SQL .= "SIM_ID   =0" . TOOL_SessionLire("GET", "id") . " ";
    $LC_SQL .= "Where LOG_ID   =0" . TOOL_SessionLire("SESSION", "USER_ID") . " ";
	$LO_IAD->ExecuteSQL($LC_SQL);
}


function SIMULATION_ChargeReel()
{
Global $LO_XML, $LB_XML, $LB_XMLS, $LB_XMLI, $LC_SimulationXML; 

	// gerer les variables de session
	$_SESSION["SCI_ALIAS"] = "";
	$_SESSION["EMP_ID"] = TOOL_SessionLire("GET", "invid");
	$_SESSION["SIM_ID"] = TOOL_SessionLire("GET", "id");
	SIMULATION_Pointe();
		
	// charger les informations de la base de données
	$LT_BDD = SIMULATION_ChargeInvestisseur();

	// reçoit en paramétre :  id correspondant à l'identifiant de la simulation
	$LC_SimulationXML   = $LT_BDD["SIM_Alias"]; 
	$LC_InvestisseurXML = $LT_BDD["EMP_Alias"];
	$LB_XML  = False;
	$LB_XMLS = False;
	$LB_XMLI = False;
	if ($LC_SimulationXML != "")
	{
		if (File_Exists("Data/XML/" . $LC_SimulationXML))
		{
			// la simulation existe : on charge toutes les informations
			$LO_XML  = simplexml_load_file("Data/XML/" . $LC_SimulationXML);
			$LB_XML  = True;
			$LB_XMLS = True;
			$LB_XMLI = True;
			$_SESSION["SCI_ALIAS"] = $LC_SimulationXML;
		}
	}
	if ($LB_XML == False)
	{
		if ($LC_InvestisseurXML != "")
		{
			if (File_Exists("Data/XML/" . $LC_InvestisseurXML))
			{
				// seul l'investisseur existe
				$LO_XML  = simplexml_load_file("Data/XML/" . $LC_InvestisseurXML);
				$LB_XML  = True;
				$LB_XMLI = True;
				$_SESSION["SCI_ALIAS"] = $LC_InvestisseurXML;
			}	
		}	
	}
}

function SIMULATION_ChargeDEMO()
{
Global $LO_XML, $LB_XML, $LB_XMLS, $LB_XMLI, $LC_SimulationXML; 

	$_SESSION["EMP_ID"] = "";
	$_SESSION["SIM_ID"] = TOOL_SessionLire("SESSION", "SCI_ALIAS");
	$LC_SimulationXML = "SIM_" . TOOL_SessionLire("SESSION", "SCI_ALIAS") . ".xml";
	$LO_XML  = simplexml_load_file("Data/Demonstrations/" . $LC_SimulationXML);
	$LB_XML  = True;
	$LB_XMLS = True;
	$LB_XMLI = True;
}
?>
