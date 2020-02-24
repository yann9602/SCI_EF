<Script Language="JavaScript">
	var LT_Champs = new Array("ed_RangBien", "ed_DescriptifBien", "ed_Surface", "ed_PrixMetre",
							"ed_ValeurLocative", "ed_LoyerDecale", "ed_DispFiscalNom", 
							"ed_DispFiscalDuree", "ed_DispFiscalDeduc",
							"rd_DispositifFiscalXXX", "rd_DispositifFiscalELI", 
							"rd_DispositifFiscalLMNP",
							"rd_LoyersFixes", "rd_LoyersSaisonniers",
							"ed_LoyerSais01", "ed_LoyerSais02", "ed_LoyerSais03",
							"ed_LoyerSais04", "ed_LoyerSais05", "ed_LoyerSais06",
							"ed_LoyerSais07", "ed_LoyerSais08", "ed_LoyerSais09",
							"ed_LoyerSais10", "ed_LoyerSais11", "ed_LoyerSais12");
	var LC_Sep = "~";
	
	function INFOSBIENS_Init()
	{
		document.getElementById("rd_LoyersFixes").checked=true;
		document.getElementById("ta_LoyerSaison").style.display="none";
		INFOSBIENS_ConcateteDetail();
		INFOSBIENS_ReagirSaison();
		INFOSBIENS_ReagirDetail();
	}
	
	function INFOSBIENS_ReagirSaison()
	{
		if (document.getElementById("rd_LoyersFixes").checked==true)
		{
			document.getElementById("ta_LoyerSaison").style.display="none";
			INFOSBIENS_CalculPrixSurface("");
		}
		if (document.getElementById("rd_LoyersSaisonniers").checked==true)
		{
			document.getElementById("ta_LoyerSaison").style.display="block";
			INFOSBIENS_CalculPrixMoyen();
		}
	}
	
	function INFOSBIENS_CalculPrixMoyen()
	{
		var LN_TotalMt = 0;
		var LN_TotalNb = 0;
		for (i=1; i<=12; i++)
		{
			var LO_Objet = i;
			LO_Objet = "ed_LoyerSais" + TOOL_PadL(LO_Objet, 2, "0");		
			LN_Loyer  = Number(document.getElementById(LO_Objet).value);
			if (LN_Loyer != 0)
				{
					LN_TotalMt = LN_TotalMt + LN_Loyer;
					LN_TotalNb = LN_TotalNb + 1;
				}
		}
		document.getElementById("ed_ValeurLocative").value = Math.round(LN_TotalMt / 12);
		var LO_Surface=document.getElementById("ed_Surface");
		var LO_PrixMetre=document.getElementById("ed_PrixMetre");
		if (LO_Surface.value!="")
		{
			LO_PrixMetre.value = Math.round(LN_TotalMt / LO_Surface.value / 12);
		}			
	}
	
	function INFOSBIENS_CalculPrixSurface(PC_Membre)
	{
		var LO_Surface=document.getElementById("ed_Surface");
		var LO_PrixMetre=document.getElementById("ed_PrixMetre");
		var LO_Valeur=document.getElementById("ed_ValeurLocative");
		if ((PC_Membre=="SURFACE") || (PC_Membre=="PRIXMETRE") ) 
			{
				LO_Valeur.value = LO_Surface.value * LO_PrixMetre.value;
			}
		if ((PC_Membre=="SURFACE") || (PC_Membre=="VALEUR") ) 	
			{
				if (LO_Surface.value!="") 
					{
						LO_PrixMetre.value = Math.round(LO_Valeur.value / LO_Surface.value);
					}			
			}		
	}
	
	function INFOSBIENS_ReagirFiscal()
	{
		var LO_DF = document.getElementById("rd_DispositifFiscalELI");
		var LO_DFL= document.getElementById("rd_DispositifFiscalLMNP");
		var LO_DFX= document.getElementById("rd_DispositifFiscalXXX");
		var LO_DIV= document.getElementById("BIENS_DFisc");
		if ((LO_DF.checked==false) && (LO_DFL.checked==false)){LO_DFX.checked='true';}
		if (LO_DF.checked==true){LO_DIV.style.display="block";}
		if (LO_DFL.checked==true){LO_DIV.style.display="none";}
		if (LO_DFX.checked==true){LO_DIV.style.display="none";}
	}

	function INFOSBIENS_ReagirDetail()
	{
		if (document.getElementById("ed_PhaseBien").value == "X")
		{
			document.getElementById("btn_AjouteBiens").style.display= "block";
			document.getElementById("btn_ValideBiens").style.display= "none";
			document.getElementById("btn_EffaceBiens").style.display= "none";
			document.getElementById("ed_DescriptifBien").readOnly	= "true";
			document.getElementById("ed_Surface").readOnly			= "true";
			document.getElementById("ed_PrixMetre").readOnly		= "true";
			document.getElementById("ed_ValeurLocative").readOnly	= "true";
			document.getElementById("ed_LoyerDecale").readOnly		= "true";
			document.getElementById("di_fiscalbien").style.display  = "none";
		}
		if ((document.getElementById("ed_PhaseBien").value == "A") ||
			(document.getElementById("ed_PhaseBien").value == "M"))
		{
			document.getElementById("btn_AjouteBiens").style.display= "block";
			document.getElementById("btn_ValideBiens").style.display= "block";
			document.getElementById("btn_EffaceBiens").style.display= "block";
			document.getElementById("ed_DescriptifBien").removeAttribute('readonly');
			document.getElementById("ed_Surface").removeAttribute('readonly');
			document.getElementById("ed_PrixMetre").removeAttribute('readonly');
			document.getElementById("ed_ValeurLocative").removeAttribute('readonly');
			document.getElementById("ed_LoyerDecale").removeAttribute('readonly');
			document.getElementById("di_fiscalbien").style.display="block";
			
		}
		if (document.getElementById("ed_PhaseBien").value == "A") 
		{
			document.getElementById("btn_EffaceBiens").style.display = "none";
		}
	}
	
	function INFOSBIENS_ValideDetail(PC_CTRL)
	{
		// sortie anticipée
		if (document.getElementById("ed_DescriptifBien").value=="")
		{
			if (PC_CTRL == "true")
			{
				var LC_Message = "Attention si le descritif n'est pas défini la saisie ne peut pas être enregistrée.";   			
				alert(LC_Message);
				document.getElementById("ed_DescriptifBien").focus();
			}
			return;				
		}
	
		var LC_Option = "";
		var LO_Objet = document.getElementById("cb_ListeBiens");		
		var LN_Rang  = document.getElementById("ed_RangBien").value;
		if (LN_Rang==-1)
		{	
			var LO_NewOption = new Option ("Ajouter", "");
			LO_Objet.options.add(LO_NewOption);
			LN_Rang = LO_Objet.options.length-1;
			document.getElementById("ed_RangBien").value = LN_Rang;
		}
		for (i=0; i< LT_Champs.length; i++)
			{
				var LO_Champ = document.getElementById(LT_Champs[i]);
				if (LO_Champ.getAttribute('type')=="text")  {LC_Option=LC_Option+LO_Champ.value + LC_Sep;}
				if (LO_Champ.getAttribute('type')=="hidden"){LC_Option=LC_Option+LO_Champ.value + LC_Sep;}
				if (LO_Champ.getAttribute('type')=="radio") {LC_Option=LC_Option+LO_Champ.checked+LC_Sep;}
			}
		LO_Objet.options[LN_Rang].value = LC_Option;
		LO_Objet.options[LN_Rang].text  = document.getElementById("ed_DescriptifBien").value;
		document.getElementById("ed_PhaseBien").value = "X";
		INFOSBIENS_ReagirDetail();
		INFOSBIENS_ConcateteDetail();
	}

	function INFOSBIENS_AjouteDetail()
	{
		for (i=0; i< LT_Champs.length; i++)
		{
			var LO_Champ = document.getElementById(LT_Champs[i]);
			if (LO_Champ.getAttribute('type')=="text")  {LO_Champ.value="";}
			if (LO_Champ.getAttribute('type')=="radio") {LO_Champ.checked=false;}
			document.getElementById("rd_LoyersFixes").checked=true;
			document.getElementById("rd_DispositifFiscalXXX").checked=true;
		}
		document.getElementById("ed_RangBien").value  = "-1";
		document.getElementById("ed_PhaseBien").value = "A";
		INFOSBIENS_ReagirDetail();
		INFOSBIENS_ReagirFiscal();
		INFOSBIENS_ReagirSaison();
	}
	
	function INFOSBIENS_EffaceDetail()
	{
		var LC_Message = "Confirmez-vous l'effacement définitif de ce bien ?"
		if (confirm(LC_Message)==true)
			{
				var LO_Objet = document.getElementById("cb_ListeBiens");
				var LN_Rang  = document.getElementById("ed_RangBien").value;
				LO_Objet.options[LN_Rang]=null;
				for (i=0; i< LT_Champs.length; i++)
				{
					var LO_Champ = document.getElementById(LT_Champs[i]);
					if (LO_Champ.getAttribute('type')=="text")  {LO_Champ.value="";}
					if (LO_Champ.getAttribute('type')=="radio") {LO_Champ.checked=false;}
					document.getElementById("rd_LoyersFixes").checked=true;
					document.getElementById("rd_DispositifFiscalXXX").checked=true;
				}
				document.getElementById("ed_PhaseBien").value = "X";
				INFOSBIENS_ReagirDetail();
				INFOSBIENS_ReagirFiscal();
				INFOSBIENS_ReagirSaison();
			}
	}

	function INFOSBIENS_AfficheDetail()
	{
		var LO_Objet = document.getElementById("cb_ListeBiens");		
		LN_Rang = LO_Objet.selectedIndex;
		if (LN_Rang<0){return;}
		LT_Option = LO_Objet.options[LN_Rang].value.split("~");
		for (i=0; i< LT_Champs.length; i++)
			{
				var LO_Champ = document.getElementById(LT_Champs[i]);
				if (LO_Champ.getAttribute('type')=="text")  {LO_Champ.value = LT_Option[i];}
				if (LO_Champ.getAttribute('type')=="hidden"){LO_Champ.value = LT_Option[i];}
				if (LO_Champ.getAttribute('type')=="radio") {if (LT_Option[i]=='true'){LO_Champ.checked = LT_Option[i];}}
			}
		document.getElementById("ed_PhaseBien").value = "M";
		INFOSBIENS_ReagirDetail();
		INFOSBIENS_ReagirSaison();
		INFOSBIENS_ReagirFiscal();
	}
	
	function INFOSBIENS_ConcateteDetail(){
		document.getElementById("ed_BlocBiens").value = "";
		for (i=0; i<document.getElementById("cb_ListeBiens").options.length; i++)
		{
			document.getElementById("ed_BlocBiens").value =
			document.getElementById("ed_BlocBiens").value +
			document.getElementById("cb_ListeBiens").options[i].value + "°";
		}
	}
	
	function INFOSBIENS_TotaliseDetail(){
		var LN_Total = 0;
		var LN_Loyer = 0;
		var LC_Ligne = "";
		for (i=0; i<document.getElementById("cb_ListeBiens").options.length; i++)
		{
			LC_Ligne = document.getElementById("cb_ListeBiens").options[i].value;
			LT_Option = LC_Ligne.split("~");
			LO_Champ  = LT_Champs.indexOf("ed_ValeurLocative");
			LN_Loyer  = LT_Option[LO_Champ];
			LN_Total  = LN_Total + Number(LN_Loyer);
		}
		return LN_Total;
	}
</Script>

<div style='float:left;width:100%;' 
	 class	= 'td_LibelleTitre td_LibelleTitreDescriptif'>
		Descriptif du bien	
</div>

<div style='float:left;width:100%;'>
	<!-- bloc superieur de gauche -->
	<div class = 'FINANCEMENT_Gauche'
		 align = 'center'>
		<!-- selection -->
		Sélectionner un lot :
		<br/>
		<input type		= 'hidden'
				name	= "ed_TotalLoyers"
				id		= "ed_TotalLoyers" />						
		<input type		= 'hidden'
				name	= "ed_BlocBiens"
				id		= "ed_BlocBiens" />				
		<input type		= 'hidden'
				name	= "ed_RangBien"
				id		= "ed_RangBien" value="-1" />
		<input type		= 'hidden'
				name	= "ed_PhaseBien"
				id		= "ed_PhaseBien" value="X" />
		<select name	= 'cb_ListeBiens'
				id		= 'cb_ListeBiens'
				style	= 'width:80%;'
				size	= '8'
				onchange= 'INFOSBIENS_ValideDetail(false);INFOSBIENS_AfficheDetail()'>
				<?PHP if ($LB_XMLS){ echo BIENS_ChargeListe();} ?>
		</select>	
		<br />
		<img src	= "Images/BoutonsFonctionnement/btn_Ajouter.png"
			 id		= "btn_AjouteBiens"
			 onclick= "INFOSBIENS_AjouteDetail()"/>
	</div>
	
	<!-- bloc superieur de droite -->
	<div class = 'FINANCEMENT_Droite'>
		<table class='table_Cadre'>
			<tr>
				<td class='td_SaisieLibelle'>
					Descriptif :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_DescriptifBien'
							id		= 'ed_DescriptifBien'
							class	= 'input_Usuel'
							size	= '25'
							maxlength='25'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Surface :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_Surface'
							id		= 'ed_Surface'
							class	= 'input_MontantUsuel'					
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'INFOSBIENS_CalculPrixSurface("SURFACE")'/>
					<label class='td_SaisieLibelle'>&nbsp;m²</label>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Loyer au m² :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_PrixMetre'
							id		= 'ed_PrixMetre'
							class	= 'input_MontantUsuel'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'INFOSBIENS_CalculPrixSurface("PRIXMETRE")'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>							
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Montant du loyer (hors-charges) :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_ValeurLocative'
							id		= 'ed_ValeurLocative'
							class	= 'input_MontantUsuel'					
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'INFOSBIENS_CalculPrixSurface("VALEUR")'/>
					<label class='td_SaisieLibelle'>&nbsp;€/mois</label>							
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Premier loyer décalé :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_LoyerDecale'
							id		= 'ed_LoyerDecale'
							class	= 'input_MontantUsuel'
							size	= '2'
							maxlength='2'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;mois</label>							
				</td>
			</tr>
			<tr>
				<td align='center'>
					<img src	= "Images/BoutonsFonctionnement/btn_EffacerLigne.png"
						id		= "btn_EffaceBiens"
						onclick	= "INFOSBIENS_EffaceDetail()"/>
				</td>
				<td align='center'>
					<img src	= "Images/BoutonsFonctionnement/btn_Valider.png"
						id		= "btn_ValideBiens"
						onclick	= "INFOSBIENS_ValideDetail(true)"/>
				</td>
			</tr>
		</table>
	</div>
</div>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>

<div style='float:left;width:100%;' id='di_fiscalbien'>
	<!-- bloc inférieur de gauche -->
	<div class = 'FINANCEMENT_Gauche'>
		<table class='table_Cadre'>
			<tr>
				<td class='td_SaisieLibelle'
					colspan='2'>
					<br/>
					Aucun avantage fiscal :
					<input type 	= 'radio'
							id		= 'rd_DispositifFiscalXXX'
							name	= 'rd_DispositifFiscal'
							value	= 'XXX'
							onclick	='INFOSBIENS_ReagirFiscal()'/>
					<br/>
					Eligible dispositif fiscal :					
					<input type 	= 'radio'
							id		= 'rd_DispositifFiscalELI'
							name	= 'rd_DispositifFiscal'
							value	= 'ELI'
							onclick	='INFOSBIENS_ReagirFiscal()'/>
					<br/>
					Loueur meublé non professionnel : 
					<input type 	= 'radio'
							id		= 'rd_DispositifFiscalLMNP'
							name	= 'rd_DispositifFiscal'
							value	= 'LMNP'
							onclick	='INFOSBIENS_ReagirFiscal()'/>
				</td>
			</tr>	
			<tr>
				<td class='td_SaisieLibelle'
					colspan='2'>
					<div id='BIENS_DFisc'>
					<table class='table_Cadre'>
						<tr>
							<td class='td_SaisieLibelle'>Nom du dispositif : </td>
							<td>
								<input type		= 'text'
										name	= 'ed_DispFiscalNom'
										id		= 'ed_DispFiscalNom'
										class	= 'input_Usuel'/>
							</td>
						</tr>
						<tr>
							<td class='td_SaisieLibelle'>Durée du dispositif : </td>
							<td>
								<input type		= 'text'
										name	= 'ed_DispFiscalDuree'
										id		= 'ed_DispFiscalDuree'
										size	= '3'
										class	= 'input_MontantUsuel'	/>	
								<label> ans</label>
							</td>
						</tr>
						<tr>
							<td class='td_SaisieLibelle'>pourcentage de déduction : </td>
							<td>
								<input type		= 'text'
										name	= 'ed_DispFiscalDeduc'
										id		= 'ed_DispFiscalDeduc'
										size	= '3'
										class	= 'input_MontantUsuel'	/>
								<label> %</label>
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>	
		</table>
	</div>
	<div class = 'FINANCEMENT_Droite'
		 style = 'text-align:center;'>
		 <input type='radio'
				value='FIXE'
				name='rd_LoyerSaison'
				id='rd_LoyersFixes'
				onclick='INFOSBIENS_ReagirSaison()'>
		<label class='td_SaisieLibelle'>loyers fixes</label>		
		<input type='radio'
				value='SAISON'
				name='rd_LoyerSaison'
				id='rd_LoyersSaisonniers'
				onclick='INFOSBIENS_ReagirSaison()'>
		<label class='td_SaisieLibelle'>loyers saisonniers</label>		
		<br/>
		<table	id='ta_LoyerSaison'
				style='text-align:right;'>
			<tr>
				<td width='50%'
					class='td_SaisieLibelle'>
					<label>Janvier</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais01'
							id		= 'ed_LoyerSais01'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>		
					<label>Février</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais02'
							id		= 'ed_LoyerSais02'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>
					<label>Mars</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais03'
							id		= 'ed_LoyerSais03'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Avril</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais04'
							id		= 'ed_LoyerSais04'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Mai</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais05'
							id		= 'ed_LoyerSais05'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Juin</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais06'
							id		= 'ed_LoyerSais06'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
				</td>
				<td width='50%'
					class='td_SaisieLibelle'>						
					<label>Juillet</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais07'
							id		= 'ed_LoyerSais07'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Août</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais08'
							id		= 'ed_LoyerSais08'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Septembre</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais09'
							id		= 'ed_LoyerSais09'
							class	= 'input_MontantUsuel'
							onblur  = 'CHARGES_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>			
					<label>Octobre</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais10'
							id		= 'ed_LoyerSais10'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Novembre</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais11'
							id		= 'ed_LoyerSais11'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
					<br/>							
					<label>Décembre</label>
					<input type		= 'text'
							name	= 'ed_LoyerSais12'
							id		= 'ed_LoyerSais12'
							class	= 'input_MontantUsuel'
							onblur  = 'INFOSBIENS_ReagirSaison("block");INFOSBIENS_CalculPrixMoyen()'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							size	= '4'
							maxlength='4' />
				</td>
			</tr>
		</table>		

	</div>
</div>

<div style='display : none'>
	<br/><br/>
	<img src='<?PHP echo "Data/Photos/" . $LT_BDD["SIM_Photo"];?>' width='150px' />
	<br/>
	<label class='td_SaisieLibelle'>Télécharger une photo</label>									
	<br/>
	<input type		= 'file'
			class	= 'input_MontantUsuel'
			name	= 'ed_PhotoBien' />
	<br/>
	<br/>
</div>

<?PHP
function BIENS_ChargeListe(){
Global $LO_XML;
	$LC_Option  = "";
	for ($i=0; $i<count ($LO_XML->simulation->descriptifs->children());$i++)
	{
		$LC_Option .= "<option value='";
		$LC_Option .= $i . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->libelle . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->surface . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->prixmetre . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->valeurlocative . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerdecale . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscalnom . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscalduree . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscaldeduc . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscalxxx . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscaleligible . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscallmnp. "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyersfixes . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaisonniers . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison01 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison02 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison03 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison04 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison05 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison06 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison07 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison08 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison09 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison10 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison11 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison12 . "~";
		$LC_Option .= "'>";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->libelle;
		$LC_Option .= "</option> ";		
	}
	return $LC_Option;
}

function BIENS_ChargeListe2(){
Global $LO_XML;
	echo "<table>";
	$LC_Option  = "";
	for ($i=0; $i<count ($LO_XML->simulation->descriptifs->children());$i++)
	{
		$LC_Option .= $i . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->libelle . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->surface . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->prixmetre . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->valeurlocative . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerdecale . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscalnom . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscalduree . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscaldeduc . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscalxxx . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscaleligible . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->dispfiscallmnp. "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyersfixes . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaisonniers . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison01 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison02 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison03 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison04 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison05 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison06 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison07 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison08 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison09 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison10 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison11 . "~";
		$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->loyerssaison12 . "~";
		
		echo "<tr>";
		echo "<td>";
		echo "<a href='" . $LC_Option . "'>";
		echo $LO_XML->simulation->descriptifs->descriptif[$i]->libelle;
		echo "</a>";
		echo "</td>";
		echo "</tr>";
		//$LC_Option .= "'>";
		//$LC_Option .= $LO_XML->simulation->descriptifs->descriptif[$i]->libelle;
		//$LC_Option .= "</option> ";		
	}
	
	echo "</table>";
	return $LC_Option;
}
?>