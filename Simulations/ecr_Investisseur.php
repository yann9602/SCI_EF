<?PHP
	// ranger le paramétre en variable de session
	if (IsSet($_GET["id"])){$_SESSION["EMP_ID"]=$_GET["id"];}
	// charger les informations
	$LT_Ligne = INVESTISSEUR_Lit();
	INVESTISSEUR_Pointe();
	$LB_XML = False;
	$LB_XMLI= False;
	$LB_XMLS= False;
	// charger l'XML
	if ($LT_Ligne != "")
		{
			if ($LT_Ligne["EMP_Alias"] != "")
				{
					$LC_XML = $LT_Ligne["EMP_Alias"];	
					if (File_Exists("Data/XML/" . $LC_XML))
						{
							$LO_XML = simplexml_load_file("Data/XML/" . $LC_XML);
							$LB_XML = True;
							$LB_XMLI= True;
						}	
				}	
		}	
	if ($LB_XML == False)
		{
			INVESTISSEUR_CreeBlanc();
		}	
?>
<Script Language="JavaScript">
	var LC_URL="";
	function Global_Init()
		{
			INVESTISSEUR_AfficheGraphe();
		}
		
	function Global_Retour()
		{
			window.location="SCI_EF_index.php?page=liste";
		}

	function Global_Valider()
	{
		if (INVESTISSEUR_Ctrl()==false){return;}
		INVESTISSEUR_AfficheCalculs("ed_CapaciteEmprunt");
		document.getElementById("ed_Phase").value="ENREGISTREDOSSIER";
		document.forms[0].submit();
	}			
	
	function Global_Effacer()
		{
			var LC_Message = "En poursuivant toutes les informations concernant " + 
							 "cet investisseur et les simulations qui luis sont " +
							 "rattachées seront définitivement perdues. \n" +
							 "Voulez-vous poursuivre ?";
			if (confirm(LC_Message)==false){return;}
			document.getElementById("ed_Phase").value="EFFACEDOSSIER";
			document.forms[0].submit();
		}

		
	function TOOL_Ajax(PC_Fonction, PC_FonctionRetour, PC_Parametres, PB_Asynchrone)
		{
			var LC_URL = "";
			if (PC_Fonction.indexOf("graphe")>=0){LC_URL ="SCI_EF_AjaxGraphes.php";}
			if (PC_Fonction.indexOf("calcul")>=0){LC_URL ="SCI_EF_AjaxCalculs.php";}
			var LC_URL = LC_URL +
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
			//on dÃ©finit l'appel de la fonction au retour serveur
			xhr.onreadystatechange = function() {TRT_Ajax(PC_FonctionRetour, xhr); };

			//on appelle le fichier reponse.txt
			xhr.open("GET", LC_URL, true);
			xhr.send(null);
		}
		
	function TRT_Ajax(PC_FonctionRetour, xhr)
		{
			if (xhr.readyState==4) 
				{
					if (PC_FonctionRetour == "INVESTISSEUR_AfficheCalculs"){INVESTISSEUR_AfficheCalculsRetour(xhr);}
					if (PC_FonctionRetour == "INVESTISSEUR_AfficheGraphe"){INVESTISSEUR_AfficheGrapheRetour(xhr);}
				}				
		}

	function INVESTISSEUR_AfficheGraphe()
		{
			// calculer la part restante
			LN_Charges  = TOOL_DecodeInt(document.getElementById("ed_AutreMensualite").value) +
						  TOOL_DecodeInt(document.getElementById("ed_ChargeResPrincipale").value)+
						  TOOL_DecodeInt(document.getElementById("ed_ChargePension").value);
			LN_Recettes = TOOL_DecodeInt(document.getElementById("ed_Salaire").value) +
						  TOOL_DecodeInt(document.getElementById("ed_RevenusFonciers").value);
			// preparer l'URL
			var LC_Param = "";								 
			LC_Param += "1_";
			LC_Param += "Charges_";
			LC_Param += LN_Charges + ";";		
			LC_Param += "2_";
			LC_Param += "Recettes_";
			LC_Param += LN_Recettes;
			TOOL_Ajax("grapheEndettement", "INVESTISSEUR_AfficheGraphe", LC_Param, true);
			// n'afficher que si necessaire
			if (LC_URL!=LC_Param)
				{
					LC_URL=LC_Param;
					TOOL_Ajax("grapheEndettement", "INVESTISSEUR_AfficheGraphe", LC_Param, true);			
				}				
		}

	function INVESTISSEUR_AfficheCalculs(PC_Objet){
		var LC_Champs = "<?PHP Echo Def_CalculInvestisseur?>";
		var LT_Champs = LC_Champs.split(";");
		var LC_Param  = PC_Objet + "&paramvaleurs=";
		for (i=0;i<LT_Champs.length;i++)
			{
				LC_Param = LC_Param + document.getElementById(LT_Champs[i]).value;
				LC_Param = LC_Param + ";";
			}
		TOOL_Ajax("calculInvestisseur", "INVESTISSEUR_AfficheCalculs", LC_Param, true);
		}
		
	function INVESTISSEUR_AfficheCalculsRetour(xhr){
		var LO_XML= xhr.responseXML;
		var LC_Texte  = TOOL_RecupereXML(LO_XML, "texte", 0);
		if (LC_Texte != "OK")
			{
				alert(LC_Texte);
				return;
			}
		var LO_Champs = LO_XML.getElementsByTagName('nom');
		for (i=0;i<LO_Champs.length;i++)
			{			
				var LC_Champ  = TOOL_RecupereXML(LO_XML, "nom", i);
				var LC_Valeur = TOOL_RecupereXML(LO_XML, "valeur", i);
				document.getElementById(LC_Champ).value = LC_Valeur;
			}
	}
	
	function INVESTISSEUR_AfficheGrapheRetour(xhr){
		var LO_XML= xhr.responseXML;
		var LC_Image= TOOL_RecupereXML(LO_XML, "image", 0);
		var LC_Texte= TOOL_RecupereXML(LO_XML, "texte", 0);
		document.getElementById("img_GrapheEndettement").src = "Ajax/" + LC_Image + "?" +  Math.random();
		document.getElementById("div_GrapheEndettement").innerHTML = LC_Texte;
	}
	
	function INVESTISSEUR_Ctrl()
	{
		var LC_Message = "";
		LC_Message += TOOL_ControleChampObligatoire("ed_InvestisseurNom", "le nom de l'investisseur");
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
		
<input type='hidden' id="ed_ID" name="ed_ID" value='<?PHP echo $_SESSION["EMP_ID"]; ?>' />
<input type='hidden' name="ed_TIPPtranche" value='<?PHP echo $LT_Ligne["EMP_SeuilsTIPPtranche"]; ?>' />
<input type='hidden' name="ed_TIPPtaux" value='<?PHP echo $LT_Ligne["EMP_SeuilsTIPPtaux"]; ?>' />
<input type='hidden' name="ed_TISCItranche" value='<?PHP echo $LT_Ligne["EMP_SeuilsTISCItranche"]; ?>' />
<input type='hidden' name="ed_TISCItaux" value='<?PHP echo $LT_Ligne["EMP_SeuilsTISCItaux"]; ?>' />
<input type='hidden' name="ed_TIASStranche" value='<?PHP echo $LT_Ligne["EMP_SeuilsTIASStranche"]; ?>' />
<input type='hidden' name="ed_TIASStaux" value='<?PHP echo $LT_Ligne["EMP_SeuilsTIASStaux"]; ?>' />


<div class = 'td_LibelleTitre td_LibelleTitreInvestisseur'>
	Profil investisseur
</div>
				
<div class = 'DI6040_Gauche'>
	<table class='table_Cadre'>
		<tr>
			<td class='td_SaisieLibelle'>
				Nom : 
			</td>
			<td>
				<input type		= 'text'
						name	= 'ed_InvestisseurNom'
						id		= 'ed_InvestisseurNom'
						class	= 'input_Usuel'
						value	= "<?PHP Echo $LO_XML->investisseur->nom; ?>"/>
			</td>		
		</tr>
		<tr>
			<td class='td_SaisieLibelle'>
				Prénom : 
			</td>
			<td>
				<input type		= 'text'
						name	= 'ed_InvestisseurPrenom'
						id		= 'ed_InvestisseurPrenom'
						class	= 'input_Usuel'
						value	= "<?PHP Echo $LO_XML->investisseur->prenom; ?>"/>
			</td>		
		</tr>
	</table>
</div>
<div class = 'DI6040_Droite' style='text-align:center;'>
	<br/>
	<img id='img_GrapheEndettement' src='Images/Entete/im_Logo.png' style='width:50%;'/>
	<div id='div_GrapheEndettement' class='span_LibelleDetail'></div>
</div>


<div style='float:left;width:100%;vertical-align:top;'>
	<!-- bloc superieur de gauche -->
	<div class = 'ENDETTEMENT_Gauche'>
		<?PHP include("ecr_DetailCharges.php");?>
	</div>
	<!-- bloc superieur de gauche -->
	<div class = 'ENDETTEMENT_Droite'>
		<?PHP include("ecr_DetailRecettes.php");?>
	</div>
</div>	

<br/>
<div style='float:left;width:100%;'>
	<!-- bloc superieur de gauche -->
	<div class = 'ENDETTEMENT_Gauche'>
		<?PHP include("ecr_DetailPartsFiscales.php");?>
	</div>
</div>	

<?PHP
function INVESTISSEUR_Pointe()
{
Global $LO_IAD;
	$LC_SQL  = "Update sci_memoire Set ";
	$LC_SQL .= "MEM_Page = 'ecr_Liste.php', ";
	$LC_SQL .= "MEM_Option = null, ";
	$LC_SQL .= "LOG_ID   =0" . TOOL_SessionLire("SESSION", "USER_ID") . ", ";
	$LC_SQL .= "INV_ID   =0" . TOOL_SessionLire("SESSION", "EMP_ID") . ", ";
	$LC_SQL .= "SIM_ID   =null ";
    $LC_SQL .= "Where LOG_ID   =0" . TOOL_SessionLire("SESSION", "USER_ID") . " ";
	$LO_IAD->ExecuteSQL($LC_SQL);
}


function INVESTISSEUR_Lit()
{
Global $LO_IAD;
	$LC_SQL  = "Select EMP_Nom, EMP_Prenom, EMP_DateNaissance, EMP_Alias, ";
	$LC_SQL .= "EMP_SeuilsTIPPtranche,  EMP_SeuilsTIPPtaux, ";
	$LC_SQL .= "EMP_SeuilsTISCItranche, EMP_SeuilsTISCItaux, ";
	$LC_SQL .= "EMP_SeuilsTIASStranche, EMP_SeuilsTIASStaux ";
	$LC_SQL .= "from sci_emprunteurs ";
	$LC_SQL .= "Where EMP_ID = ";
	if (isset($_SESSION["EMP_ID"]))	{$LC_SQL .= $_SESSION["EMP_ID"];}
	$LO_IAD->ExecuteSQL($LC_SQL);
	if ($LO_IAD->NombreLignes() > 0)
		{
			$LT_Ligne = $LO_IAD->EclateSQL("Noms");
			return $LT_Ligne;
		}
	else
		{
			return "";
		}
}


function INVESTISSEUR_CreeBlanc()
{
Global $LO_XML, $LT_Ligne;

	$LO_XML = new SimpleXMLElement("<root></root>");
	$LO_NoeudClient = $LO_XML->addChild("client"); 
	$LO_NoeudClient = $LO_XML->addChild("investisseur"); 
	$LO_NoeudRessource = $LO_NoeudClient->addChild("ressources"); 
	$LO_NoeudCharges = $LO_NoeudClient->addChild("charges"); 
	$LO_NoeudFiscalite = $LO_NoeudClient->addChild("fiscalite"); 
	for ($i=1; $i<=5; $i++)
		{
			$LO_NoeudPeriode = $LO_NoeudFiscalite->addChild("periode");
		}
	$LT_Ligne = Array(	"EMP_SeuilsTIPPtranche" => "....;0;9700;26790;71825",
						"EMP_SeuilsTIPPtaux" => "....;0;14;30;41",
						"EMP_SeuilsTISCItranche" => "....;0;38120;0;0",
						"EMP_SeuilsTISCItaux" => "....;15;33.33;0;0;",
						"EMP_SeuilsTIASStranche" => "....;0;9700;26790;71825",
						"EMP_SeuilsTIASStaux" => "....;0;14;30;41;");
}

?>