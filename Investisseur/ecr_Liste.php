<?PHP
	$LC_SQL  = "Select MEM_InvTri, MEM_InvSens, MEM_InvFiltreNom, MEM_InvFiltreCapacite,";
	$LC_SQL .= "MEM_InvPage, INV_ID ";
	$LC_SQL .= "From sci_memoire ";
	$LC_SQL .= "Where LOG_ID =0" . TOOL_SessionLire("SESSION", "USER_ID") . " ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LT_Ligne = $LO_IAD->EclateSQL("Noms");
?>
<Script Language="JavaScript">
	var LC_INV_ID="0<?PHP Echo $LT_Ligne["INV_ID"]; ?>";
	var LC_SIM_ID="0";
	var LC_INV_SensTri="<?PHP Echo $LT_Ligne["MEM_InvSens"]; ?>";
	var LC_INV_ChampTri="<?PHP Echo $LT_Ligne["MEM_InvTri"]; ?>";
	var LN_INV_NombreTri=0;
	var LN_INV_Page=0<?PHP Echo $LT_Ligne["MEM_InvPage"]; ?>;
	var LN_INV_MaxPage=10;
	var LC_DOSS_SensTri="A";
	var LC_DOSS_ChampTri="Nom";
	var LN_DOSS_NombreTri=0;
	var LN_DOSS_Page=1;
	var LN_DOSS_MaxPage=10
	
	function Global_Init()
		{
			LISTE_InvestisseurAjax("Nom");
			document.getElementById("ed_InvPage").value=LN_INV_Page;
			document.getElementById("ed_DossPage").value=LN_DOSS_Page;
			LISTE_DossiersVide();
			if (parseInt(LC_INV_ID) != 0){
				document.getElementById("rd_DossierSelectionSIM").checked=true;
				LISTE_DossierAjax("Libelle");				
			}
		}
		
	function Global_Retour()
		{
		}

	function LISTE_InvestisseurChangeLigne(PO_Objet, PC_ID)
		{
			for (i=1; i<=LN_INV_NombreTri; i++)
				{
					LO_Objet=document.getElementById("linv"+i);
					LO_Objet.className="listes_DivCourante";
				}
			LN_INV_Page=1;
			PO_Objet.className="listes_DivSelection"
			LC_INV_ID = PC_ID;
			LISTE_DossiersVide();
		}
		
	function LISTE_InvestisseurPageAV()
		{
			LN_INV_Page = LN_INV_Page + 1;
			if (LN_INV_Page>LN_INV_MaxPage){LN_INV_Page = LN_INV_MaxPage;}
			document.getElementById("ed_InvPage").value=LN_INV_Page;
			LISTE_InvestisseurAjax(LC_INV_ChampTri);
		}
	
	function LISTE_InvestisseurPageAR()
		{
			LN_INV_Page = LN_INV_Page - 1;
			if (LN_INV_Page<1){LN_INV_Page = 1;}
			document.getElementById("ed_InvPage").value=LN_INV_Page;
			LISTE_InvestisseurAjax(LC_INV_ChampTri);
		}

	function LISTE_InvestisseurTri(PC_Tri)
		{
		var LO_Objet="";
		if (PC_Tri=="Capacite"){LO_Objet="btn_TriInvCapacite";}
		if (PC_Tri=="Nom"){LO_Objet="btn_TriInvNom";}
		document.getElementById("btn_TriInvCapacite").style.display='none';
		document.getElementById("btn_TriInvNom").style.display='none';
		if (LC_INV_SensTri=="A")
			{
			    document.getElementById(LO_Objet).style.display='inline-block';
				document.getElementById(LO_Objet).src='Images/BoutonsFonctionnement/btn_TriDesc.png';
				LC_INV_SensTri="D";
			}
		else	
			{
			    document.getElementById(LO_Objet).style.display='inline-block';
				document.getElementById(LO_Objet).src='Images/BoutonsFonctionnement/btn_TriAsc.png';
				LC_INV_SensTri="A";
			}
		LISTE_InvestisseurAjax(PC_Tri);
		}
		
	function LISTE_InvestisseurModif(PN_ID)
		{
			if (PN_ID == 0){PN_ID = LC_INV_ID;}
			var LC_URL="SCI_EF_index.php?page=investisseur";
			LC_URL=LC_URL + "&id=" + PN_ID;
			window.location=LC_URL;
		}
		
	function LISTE_InvestisseurCree()
		{
			var LC_URL="SCI_EF_index.php?page=investisseur&id=0";
			window.location=LC_URL;
		}		

	function LISTE_InvestisseurAjax(PC_Tri)
		{
			LC_INV_ChampTri=PC_Tri;
			var LC_URL = "SCI_EF_AjaxListes.php" +
						 "?cle=" + "listeinvestisseurs";	
			LC_URL=LC_URL+"&tri=" + PC_Tri;
			LC_URL=LC_URL+"&sens=" + LC_INV_SensTri;
			LC_URL=LC_URL+"&page=" + LN_INV_Page;

			// critères supplémentaires
			var LC_Nom=document.getElementById("ed_FiltreNom").value;	
			if (LC_Nom!=""){LC_URL=LC_URL+"&filtrenom=" + LC_Nom;}
			var LC_Capacite=document.getElementById("ed_FiltreCapacite").value;	
			if (LC_Capacite!=""){LC_URL=LC_URL+"&filtrecapacite=" + LC_Capacite;}

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
			xhr.onreadystatechange = function() {TRT_Ajax("LISTE_InvestisseurAffiche", xhr); };

			//on appelle le fichier reponse.txt
			xhr.open("GET", LC_URL, true);
			xhr.send(null);
		}

	function LISTE_InvestisseurAffiche(xhr)
		{
			var LO_XML= xhr.responseXML;
			var LO_Libelle = LO_XML.getElementsByTagName("libelle");
			var LO_ID      = LO_XML.getElementsByTagName("id");
			var LO_Capacite= LO_XML.getElementsByTagName("capacite");
			var LC_HTML="";
			var LN_Ligne=0;
			var LO_NombrePages = LO_XML.getElementsByTagName("nombrelignes");
			LN_INV_MaxPage = LO_NombrePages[0].firstChild.nodeValue;
			
			for (i=0;i<LO_ID.length;i++){
				LN_Ligne=i+1;
				LC_HTML=LC_HTML+"<div id='linv" + LN_Ligne + "' ";
				if (parseInt(LO_ID[i].firstChild.nodeValue)==parseInt(LC_INV_ID))
					{LC_HTML=LC_HTML+"class='listes_DivSelection' ";}
				else
					{LC_HTML=LC_HTML+"class='listes_DivCourante' ";}
				LC_HTML=LC_HTML+
						"ondblclick='LISTE_InvestisseurModif(" + 
						LO_ID[i].firstChild.nodeValue + ")' ";
				LC_HTML=LC_HTML+
						"onclick='LISTE_InvestisseurChangeLigne(this, " +
						LO_ID[i].firstChild.nodeValue + ")'>";
				LC_HTML=LC_HTML+
						"<div style='width:60%;float:left;'>" + 
						LO_Libelle[i].firstChild.nodeValue + "</div>";
				LC_HTML=LC_HTML+
						"<div style='width:30%;float:left;text-align:right;'>" + 
						LO_Capacite[i].firstChild.nodeValue + "</div>";
				LC_HTML=LC_HTML+"</div>";
			}
			var LO_DYN = document.getElementById("div_ListeInvestiseur");	
			LN_INV_NombreTri=LO_ID.length;
			LO_DYN.innerHTML=LC_HTML;
		}

	function LISTE_DossierAjax(PC_Tri)
		{
			// sortie anticipée
			if (LC_INV_ID=="0")
				{
					document.getElementById("rd_DossierSelectionSIM").checked=false;
					alert("Il faut d'abord sélectionner un investisseur");
					return;
				}
			LC_DOSS_ChampTri=PC_Tri;
			var LC_URL = "SCI_EF_AjaxListes.php" +
						 "?cle=" + "listedossiers";	
			LC_URL=LC_URL+"&id=" + LC_INV_ID;		 
			LC_URL=LC_URL+"&tri=" + PC_Tri;
			LC_URL=LC_URL+"&sens=" + LC_DOSS_SensTri;
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
			xhr.onreadystatechange = function() {TRT_Ajax("LISTE_DossierAffiche", xhr); };

			//on appelle le fichier reponse.txt
			xhr.open("GET", LC_URL, true);
			xhr.send(null);
		}

	function LISTE_DossierChangeLigne(PO_Objet, PC_ID)
		{
			for (i=1; i<=LN_DOSS_NombreTri; i++)
				{
					LO_Objet=document.getElementById("ldoss"+i);
					LO_Objet.className="listes_DivCourante";
				}
			PO_Objet.className="listes_DivSelection"
			LC_SIM_ID = PC_ID;
		}

	function LISTE_DossierTri(PC_Tri)
		{
			var LO_Objet="";
			if (PC_Tri=="Libelle"){LO_Objet="btn_TriDossLibelle";}
			if (PC_Tri=="Date"){LO_Objet="btn_TriDossDate";}
			document.getElementById("btn_TriDossLibelle").style.display='none';
			document.getElementById("btn_TriDossDate").style.display='none';
			if (LC_DOSS_SensTri=="A")
				{
					document.getElementById(LO_Objet).style.display='inline-block';
					document.getElementById(LO_Objet).src='Images/BoutonsFonctionnement/btn_TriDesc.png';
					LC_DOSS_SensTri="D";
				}
			else	
				{
					document.getElementById(LO_Objet).style.display='inline-block';
					document.getElementById(LO_Objet).src='Images/BoutonsFonctionnement/btn_TriAsc.png';
					LC_DOSS_SensTri="A";
				}
			LISTE_DossierAjax(PC_Tri);
		}
		
	function LISTE_DossierModif(PC_ID)
		{
			if (PC_ID == undefined){PC_ID = LC_SIM_ID}
			var LC_URL = "SCI_EF_index.php?page=simulation";
			LC_URL=LC_URL + "&id=" + PC_ID;
			LC_URL=LC_URL + "&invid=" + LC_INV_ID;
			window.location=LC_URL;			
		}

	function LISTE_DossierCree()
		{
			if (LC_INV_ID == "0")
				{
					alert("Aucun investisseur n'a été défini");
					return;
				}
			var LC_URL = "SCI_EF_index.php?page=simulation";
			LC_URL=LC_URL + "&id=0";
			LC_URL=LC_URL + "&invid=" + LC_INV_ID
			window.location=LC_URL;			
		}
		
	function TRT_Ajax(PC_FonctionRetour, xhr)
		{
			if (xhr.readyState==4) 
				{
					if (PC_FonctionRetour == "LISTE_InvestisseurAffiche") {LISTE_InvestisseurAffiche(xhr);}
					if (PC_FonctionRetour == "LISTE_DossierAffiche") {LISTE_DossierAffiche(xhr);}
				}		
		}

	function LISTE_DossiersVide()
		{
			document.getElementById("rd_DossierSelectionSIM").checked=false;
			var LO_DYN = document.getElementById("div_ListeDossier");	
			LO_DYN.innerHTML="";
		}
		
	function LISTE_DossierAffiche(xhr)
		{
			var LO_XML= xhr.responseXML;
			var LO_Libelle= LO_XML.getElementsByTagName("libelle");
			var LO_ID     = LO_XML.getElementsByTagName("id");
			var LO_Date   = LO_XML.getElementsByTagName("date");
			var LC_HTML="";
			var LN_Ligne=0;
			for (i=0;i<LO_ID.length;i++){
				LN_Ligne=i+1;
				LC_HTML=LC_HTML+"<div id='ldoss" + LN_Ligne + "' ";
				LC_HTML=LC_HTML+"class='listes_DivCourante' ";
				LC_HTML=LC_HTML+"ondblclick='LISTE_DossierModif(" + LO_ID[i].firstChild.nodeValue + ")' ";
				LC_HTML=LC_HTML+"onclick='LISTE_DossierChangeLigne(this, " + LO_ID[i].firstChild.nodeValue + ")'>";
				LC_HTML=LC_HTML+"<div style='width:60%;float:left;'>" + LO_Libelle[i].firstChild.nodeValue + "</div>";
				LC_HTML=LC_HTML+"<div style='width:30%;float:left;'>" + TOOL_DecodeDate(LO_Date[i].firstChild.nodeValue) + "</div>";
				LC_HTML=LC_HTML+"</div>";
			}
			var LO_DYN = document.getElementById("div_ListeDossier");	
			LN_DOSS_NombreTri=LO_ID.length;
			LO_DYN.innerHTML=LC_HTML;
		}
		
</Script>
		
<table class='table_Cadre'>
	<tr>
		<td colspan	= '3' 
			class	= 'td_LibelleTitre'>
				Liste des investisseurs
		</td>
	</tr>	
	<tr>
		<td>
			<div style='float:left;width:100%;'>
				<!-- bloc superieur de gauche -->
				<div class = 'FINANCEMENT_Gauche'>
					<?PHP 
					if ($_SESSION["USER_TYPE"] == "PRO")
					{
						LISTE_Filtre();
						echo "<br/>";
						echo "<br/>";
						LISTE_ListeInvestisseurs(); 
					}
					if ($_SESSION["USER_TYPE"] == "IND")
					{
						LISTE_InvestisseurUnique();
					}
					?>
					<br/>
					<div style='text-align:center;'>
						<input type='hidden' name='ed_InvID' id='ed_InvID' />
						<img src='Images/BoutonsFonctionnement/btn_AjouteInvestisseur.png' 
							 onclick='LISTE_InvestisseurCree()'/>
						<img src='Images/BoutonsFonctionnement/btn_ModifInvestisseur.png' 
							 onclick='LISTE_InvestisseurModif(0)'/>
					</div>
				</div>
				<div class = 'FINANCEMENT_Droite'>	
					<?PHP LISTE_ChoixDossiers();?>
					<br/>
					<br/>
					<?PHP 
					LISTE_ListeDossiers();
					?>
				</div>
			</div>	
		</td>
	</tr>	
</table>


<?PHP
function LISTE_Filtre()
{
Global $LT_Ligne;
?>
	<table width='100%'>
		<tr>
			<td> 
				Nom (ou partie du nom) :
			</td>	
			<td>
				<input type		= 'text'
						name	= 'ed_FiltreNom'
						id		= 'ed_FiltreNom'
						class	= 'input_Usuel'
						value	= '<?PHP Echo $LT_Ligne["MEM_InvFiltreNom"]; ?>' />
			</td>
		</tr>
		<tr>
			<td> 
				Seuil de revenus :
			</td>	
			<td>
				<input type		= 'text'
						name	= 'ed_FiltreCapacite'
						id		= 'ed_FiltreCapacite'
						class	= 'input_Usuel'
						value	= '<?PHP Echo $LT_Ligne["MEM_InvFiltreCapacite"]; ?>' />
			</td>
		</tr>
		<tr>
			<td colspan='2'> 
				<input type		= 'button'
						name	= 'btn_ChargeInvestisseur'
						id		= 'btn_ChargeInvestisseur'
						value	= 'Voir la selection'
						onclick	= 'LISTE_InvestisseurAjax("Nom")'/>
			</td>
		</tr>
	</table>
<?PHP
}


function LISTE_ListeInvestisseurs()
{
?>
	<div class='div_ListesBandes'>
		<b>Liste des investisseurs</b> <br/>
		<div style='width:60%;float:left;'
			 onclick='LISTE_InvestisseurTri("Nom")'>
			 Investisseurs
			 <img src='Images/BoutonsFonctionnement/btn_TriDesc.png'
			 	  style='vertical-align:middle;border:0px;display:none;width:10px;'
				  id='btn_TriInvNom'/>
		</div>
		<div style='width:40%;float:left;'
		     onclick='LISTE_InvestisseurTri("Capacite")'>
			 Cap emprunt
			 &nbsp;
			 <img src='Images/BoutonsFonctionnement/btn_TriDesc.png'
			 	  style='vertical-align:middle;border:0px;display:none;width:10px;'
				  id='btn_TriInvCapacite'/>
		</div>
	</div>
	<div id='div_ListeInvestiseur' 
		class='div_ListesBloc'>
	</div>
	<div class='div_ListesBandes'>
		<input 	type='button'
				value='<<' 
				onclick='LISTE_InvestisseurPageAR()'/>			
		<input type='text'
				name='ed_InvPage'
				id='ed_InvPage'
				readonly='true'
				value=''
				size='3'/>
		<input 	type='button'
				value='>>'
				onclick='LISTE_InvestisseurPageAV()'/>			
	</div>
<?PHP
}


function LISTE_ChoixDossiers()
{
?>
	<div>
		<input 	type	='radio'
				name	='rd_DossierSelection'
				id		='rd_DossierSelectionSIM' 
				value	='SIM'
				onclick ='LISTE_DossierAjax("Libelle")'/>
				Simulations
		<br/>
		<br/>		
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;disponible ultérieurement
		<br/>
		<input 	type	='radio'
				name	='rd_DossierSelection'
				id		='rd_DossierSelectionDOSS' 
				value	='DOSS'
				disabled='true'
				onclick =''/>				
				Dossiers d'investissement
		<br/>		
		<input 	type	='radio'
				name	='rd_DossierSelection'
				id		='rd_DossierSelectionGEST' 
				value	='GEST'
				disabled='true'
				onclick =''/>				
				Gestion locative
		<br/>		
	</div>
<?PHP
}


function LISTE_ListeDossiers()
{
?>
	<div class='div_ListesBandes'>
		<b>Liste des simulations</b> <br/>
		<div style='width:60%;float:left;'
			  onclick='LISTE_DossierTri("Libelle")'>
			Simulation
			&nbsp;
			<img src='Images/BoutonsFonctionnement/btn_TriAsc.png'
			 	 style='vertical-align:middle;border:0px;display:none;width:10px;'
			     id='btn_TriDossLibelle'/>
		</div>
		<div style='width:30%;float:left;'
			 onclick='LISTE_DossierTri("Date")'>
			Date
			&nbsp;
			<img src=''
			 	 style='vertical-align:middle;border:0px;display:none;width:10px;'
			     id='btn_TriDossDate'/>
		</div>
	</div>
	<div id='div_ListeDossier' 
		class='div_ListesBloc'>
	</div>
	<div class='div_ListesBandes'>
		<input 	type='button'
				value='<<' />
		<input type='text'
				name='ed_DossPage'
				id='ed_DossPage'
				readonly='true'
				value=''
				size='3'/>
		<input 	type='button'
				value='>>' />			
	</div>
	<br/>
	<br/>
	<br/>
	<input type='hidden' name='ed_SimID' id='ed_SimID' />
	<img src='Images/BoutonsFonctionnement/btn_AjouteSimulation.png' 
		onclick='LISTE_DossierCree()'/>
	<img src='Images/BoutonsFonctionnement/btn_ModifSimulation.png' 
		onclick='LISTE_DossierModif()'/>
<?PHP
}


function LISTE_InvestisseurUnique()
{
Global $LO_IAD;

	$LC_SQL  = "SELECT EMP_ID, Concat(EMP_Nom, ' ') as EMP_Nom, EMP_CapaciteEmprunt ";
	$LC_SQL .= "from sci_emprunteurs ";
	$LC_SQL .= "where LOG_ID in (" . TOOL_SessionLire("SESSION", "USER_ID") . ",0) ";
	$LO_IAD->ExecuteSQL($LC_SQL);
	$LC_INV_ID = "";
	if ($LO_IAD->NombreLignes() <> 0)
	{
		$LT_Ligne = $LO_IAD->EclateSQL("Noms");
		$LC_INV_ID = $LT_Ligne["EMP_ID"];
	}
?>
	<div class='div_ListesBandes'>
		<b>Mon profil d'investisseur</b>
	</div>
	 <br/>
	<?PHP
	if ($LC_INV_ID == "")
	{
		Echo "Votre profil d'investisseur n'existe pas encore. <br/>";
		Echo "Créez le en cliquant ";
		Echo "<a href=javascript:LISTE_InvestisseurCree()>ici</a>";
	}
	if ($LC_INV_ID != "")
	{
		Echo "Vous pouvez modifier votre profil d'investisseur <br/>";
		Echo "en cliquant ";
		Echo "<a href=javascript:LISTE_InvestisseurModif(" . $LC_INV_ID . ")>ici</a>";
	}
}
?>