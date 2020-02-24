<Script Language="JavaScript">
	var LC_URL="";
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

</script>

<div class = 'DI6040_Gauche'>
	<table class='table_Cadre'>
		<tr>
			<td colspan	= '3' 
				class	= 'td_LibelleTitre  td_LibelleTitreInvestisseur'>
				Profil investisseur
			</td>
		</tr>
		<tr>
			<td class='td_SaisieLibelle'>
				Nom
			</td>
			<td>
				<input type		= 'text'
						name	= 'ed_InvestisseurNom'
						id		= 'ed_InvestisseurNom'
						class	= 'input_Usuel'
						value	= "<?PHP if ($LB_XML){Echo $LO_XML->investisseur->nom;} ?>"/>
			</td>		
		</tr>
		<tr>
			<td class='td_SaisieLibelle'>
				Prénom
			</td>
			<td>
				<input type		= 'text'
						name	= 'ed_InvestisseurPrenom'
						id		= 'ed_InvestisseurPrenom'
						class	= 'input_Usuel'
						value	= "<?PHP if ($LB_XML){Echo $LO_XML->investisseur->prenom;} ?>"/>
			</td>		
		</tr>
	</table>
</div>
<div class = 'DI6040_Droite' style='text-align:center;'>
	<br/>
	<img id='img_GrapheEndettement' src='Images/Entete/im_Logo.png' style='width:50%;'/>
	<div id='div_GrapheEndettement' class='span_LibelleDetail'></div>
</div>
			
<div style='float:left;width:100%;'>
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

