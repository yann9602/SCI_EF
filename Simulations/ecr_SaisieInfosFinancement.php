		<Script Language="JavaScript">
			var LC_URL = "";
			function FINANCEMENT_AfficheGraphe(){
				// recuperation des valeurs
				var LN_Notaire = TOOL_DecodeInt(document.getElementById("ed_FraisNotaire").value) + 
								 TOOL_DecodeInt(document.getElementById("ed_FraisDivers").value);
				var LN_Travaux = TOOL_DecodeInt(document.getElementById("ed_PrixTravauxNDeduc").value) + 
								 TOOL_DecodeInt(document.getElementById("ed_PrixTravauxDeduc").value);
				var LN_Pret	   = TOOL_DecodeInt(document.getElementById("ed_Emprunt").value) +  
								 TOOL_DecodeInt(document.getElementById("ed_PTZMontant").value);
				var LN_Cout	   = TOOL_DecodeInt(document.getElementById("ed_PrixAchat").value);
				var LN_Paiement= TOOL_DecodeInt(document.getElementById("ed_Apport").value) +
								 TOOL_DecodeInt(document.getElementById("ed_Emprunt").value) +
								 TOOL_DecodeInt(document.getElementById("ed_PTZMontant").value);
				// sortie anticipee				
				if ((LN_Paiement==0) || (LN_Cout==0)){return;}
				// preparer l'URL
				var LC_Param = "";								 
				LC_Param += "1_";
				LC_Param += "Prix_";
				LC_Param += document.getElementById("ed_PrixAchat").value + ";";
				LC_Param += "1_";
				LC_Param += "Travaux_";
				LC_Param += LN_Travaux + ";";		
				LC_Param += "1_";
				LC_Param += "Notaire_";
				LC_Param += LN_Notaire + ";";				
				LC_Param += "2_";
				LC_Param += "Credits_";
				LC_Param += LN_Pret + ";";				
				LC_Param += "2_";
				LC_Param += "Apport_";
				LC_Param += document.getElementById("ed_Apport").value + ";";		

				// n'afficher que si necessaire
				if (LC_URL!=LC_Param)
					{
						LC_URL=LC_Param;	
						TOOL_Ajax("grapheFinancement", "FINANCEMENT_AfficheGraphe", LC_Param, true);			
					}
			}
			
			function FINANCEMENT_AfficheGrapheRetour(xhr){
				var LO_XML= xhr.responseXML;
				var LC_Image= TOOL_RecupereXML(LO_XML, "image", 0);
				var LC_Texte= TOOL_RecupereXML(LO_XML, "texte", 0);
				document.getElementById("img_GrapheFinancement").src = "Ajax/" + LC_Image + "?" +  Math.random();
				document.getElementById("div_GrapheFinancement").innerHTML = LC_Texte;
			}
			
			function FINANCEMENT_AfficheCalculs(PC_Objet){
				// gestion spécifique des boutons radio
				document.getElementById("rd_Pret").value = "";
				if (document.getElementById("rd_PretClassique").checked){document.getElementById("rd_Pret").value = "PretClassique";}
				if (document.getElementById("rd_PretInfine").checked)   {document.getElementById("rd_Pret").value = "PretInFine";}
				// recenser les objets concerner les construire l'URL
				var LC_Champs = "<?PHP Echo Def_CalculFinancement;?>" + ";" + 
								"<?PHP Echo Def_CalculInvestisseur;?>" ;
				var LT_Champs = LC_Champs.split(";");
				var LC_Param  = PC_Objet + "&paramvaleurs=";
				for (i=0;i<LT_Champs.length;i++)
					{
						LC_Param = LC_Param + document.getElementById(LT_Champs[i]).value;
						LC_Param = LC_Param + ";";
					}
				TOOL_Ajax("calculFinancement", "FINANCEMENT_AfficheCalculs", LC_Param, false);
				FINANCEMENT_AfficheGraphe();
			}
			
			function FINANCEMENT_AfficheCalculsRetour(xhr){
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
			
			function FINANCEMENT_Reagir(){
				if (document.getElementById("cb_PTZ").checked==true)
					{
						document.getElementById("ed_PTZ").value				   = "PTZ";
						document.getElementById("ed_PTZMontant").style.display = "block";
						document.getElementById("ed_PTZDuree").style.display   = "block";
						document.getElementById("lb_PTZMontant").style.display = "block";
						document.getElementById("lb_PTZDuree").style.display   = "block";
					}
				else
					{
						document.getElementById("ed_PTZ").value				   = "";
						document.getElementById("ed_PTZMontant").value		   = "";
						document.getElementById("ed_PTZDuree").value		   = "";
						document.getElementById("ed_PTZMontant").style.display = "none";
						document.getElementById("ed_PTZDuree").style.display   = "none";
						document.getElementById("lb_PTZMontant").style.display = "none";
						document.getElementById("lb_PTZDuree").style.display   = "none";
					}
			}
			
			function FINANCEMENT_CalculTravaux(){
				var LN_Total  = 0;
				LN_Total = LN_Total + TOOL_DecodeInt(document.getElementById("ed_PrixTravauxNDeduc").value);
				LN_Total = LN_Total + TOOL_DecodeInt(document.getElementById("ed_PrixTravauxDeduc").value);
				LN_Total = LN_Total + TOOL_DecodeInt(document.getElementById("ed_PrixMobilier").value);
				document.getElementById("ed_Travaux").value = LN_Total;
			}
		</Script>

<div style='float:left;width:100%;'>
	<!-- bloc superieur de gauche -->
	<div class = 'DI6040_Gauche'>
		<table class='table_Cadre'>
			<tr>
				<td colspan	= '2' 
					class	= 'td_LibelleTitre td_LibelleTitreFinancement'>
					Frais engagés
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Prix d'acquisition :
				</td>
				<td>
					<input	type	= 'text'
							name	= 'ed_PrixAchat'
							id		= 'ed_PrixAchat'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->prixachat;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'FINANCEMENT_AfficheGraphe()' />
					<label class='td_SaisieLibelle'>&nbsp;€</label>
					<img class	='img_Recalcul'
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'
						 onclick='FINANCEMENT_AfficheCalculs("ed_PrixAchat")'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Frais de notaire :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_FraisNotaire'
							id		= 'ed_FraisNotaire'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->fraisnotaire;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='5' 
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'FINANCEMENT_AfficheGraphe()'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
					<img class	='img_Recalcul'
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'					
						 onclick='FINANCEMENT_AfficheCalculs("ed_FraisNotaire")'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Frais divers : <br/>
					(cautionnement, hypothèque, dossier)
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_FraisDivers'
							id		= 'ed_FraisDivers'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->fraisdivers;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='5' 
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'FINANCEMENT_AfficheGraphe()'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Rappel du montant des travaux (renseigné dans l'onglet travaux):
				</td>
				<td>
					<input type		= 'hidden'
							name	= 'ed_PrixTravauxNDeduc'
							id		= 'ed_PrixTravauxNDeduc'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->travauxndeduc;} ?>'
							readonly= 'true'
							class	= 'input_MontantUsuel'
							size	= '10' />
					<input type		= 'hidden'
							name	= 'ed_PrixTravauxDeduc'
							id		= 'ed_PrixTravauxDeduc'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->travauxdeduc;} ?>'
							readonly= 'true'
							class	= 'input_MontantUsuel'					
							size	= '10' />
					<input type		= 'hidden'
							name	= 'ed_PrixMobilier'
							id		= 'ed_PrixMobilier'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->mobilier;} ?>'
							readonly= 'true'
							class	= 'input_MontantUsuel'					
							size	= '10'/>
					<input type		= 'text'
							name	= 'ed_Travaux'
							id		= 'ed_Travaux'
							readonly= 'true'
							class	= 'input_MontantUsuel'					
							size	= '10'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
				</td>
			</tr>	
			<tr>
				<td class='td_SaisieLibelle'>
					Apport :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_Apport'
							id		= 'ed_Apport'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->apport;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'FINANCEMENT_AfficheGraphe()'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
					<img class	='img_Recalcul'
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'
						 onclick='FINANCEMENT_AfficheCalculs("ed_Apport")'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Montant emprunté :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_Emprunt'
							id		= 'ed_Emprunt'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->emprunt;} ?>'
							class	= 'input_MontantUsuel'					
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'FINANCEMENT_AfficheGraphe()'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
					<img class	='img_Recalcul' 
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'
						 onclick='FINANCEMENT_AfficheCalculs("ed_Emprunt")'/>
				</td>
			</tr>
		</table>
	</div>

	<!-- bloc supérieur de droite -->
	<div class = 'DI6040_Droite'
		 style = 'text-align :center';>
			<br/>
			<img id='img_GrapheFinancement' src='Images/Entete/im_Logo.png' style='width:70%;'/>
			<div id='div_GrapheFinancement' class='span_LibelleDetail'></div>
	</div>
</div>
&nbsp;<br/>
&nbsp;<br/>
<div style='float:left;width:100%;'>
	<!-- bloc inferieur de gacuhe -->
	<div class = 'FINANCEMENT_Gauche'>
		<table class='table_Cadre'>
			<tr>
				<td colspan	= '2'
					class	= 'td_LibelleTitre td_LibelleTitreFinancement'>
					Détail du crédit
				</td>
			</tr>
			<tr>
				<td class	='td_SaisieLibelle'
					colspan	='2'
					align='center'>
					<input 	type		= 'hidden'
							name		= 'rd_Pret'
							id			= 'rd_Pret' />
					<input 	type		= 'radio'
							name		= 'rd_Pret'
							id			= 'rd_PretClassique'
							<?PHP if ($LB_XMLS)
									{echo TOOL_DecodeCheck($LO_XML->simulation->credit->typepret, "PRET_EchClassique");}
								  else 
									{echo "checked=true";}
							?>
							value		= 'PRET_EchClassique'/>
					Prêt classique		
					<input 	type		= 'radio'
							name		= 'rd_Pret'
							id			= 'rd_PretInfine'
							<?PHP if ($LB_XMLS){echo TOOL_DecodeCheck($LO_XML->simulation->credit->typepret, "PRET_EchInfine");}?>
							value		= 'PRET_EchInfine'/>
					Prêt in fine	
				</td>
			</tr>				
			<tr>
				<td class='td_SaisieLibelle'>
					Taux nominal :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_TauxNominal'
							id		= 'ed_TauxNominal'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->tauxnominal;} ?>'
							class	= 'input_MontantUsuel'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;%</label>
				</td>
			</tr>			
			<tr>
				<td class='td_SaisieLibelle'>
					Taux d'assurance :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_TauxAssurance'
							id		= 'ed_TauxAssurance'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->tauxassurance;} ?>'
							class	= 'input_MontantUsuel'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;%</label>
				</td>
			</tr>			
			<tr>
				<td class='td_SaisieLibelle'>
					Durée :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_Duree'
							id		= 'ed_Duree'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->duree;} ?>'
							class	= 'input_MontantUsuel'
							size	= '5'
							maxlength='4'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;mois</label>
					<img class	='img_Recalcul'
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'
						 onclick='FINANCEMENT_AfficheCalculs("ed_Duree")'/>							
				</td>
			</tr>			
			<tr>
				<td class='td_SaisieLibelle'>
					Montant des mensualité:
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_Mensualite'
							id		= 'ed_Mensualite'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->mensualite;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
					<img class	='img_Recalcul' 
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'
						 onclick='FINANCEMENT_AfficheCalculs("ed_Mensualite")'/>		
				</td>
			</tr>			
		</table>
	</div>

	<div class = 'FINANCEMENT_Droite'>
	<!-- bloc inferieur de droite -->
		<table class='table_Cadre'>		
			<tr>
				<td colspan	= '2' 
					class	= 'td_LibelleTitre td_LibelleTitreFinancement'>
					<input type 	= 'checkbox'
							id		= 'cb_PTZ'
							name	= 'cb_PTZ'
							<?PHP if ($LB_XMLS){echo TOOL_DecodeCheck($LO_XML->simulation->credit->ptz, "PTZ");}?>
							onchange= 'FINANCEMENT_Reagir()'/>
					<input type		= 'hidden'
							id		= 'ed_PTZ'
							name	= 'ed_PTZ' />
					Eco prêt à  taux zéro
				</td>
			</tr>		
			<tr id='PTZ_Montant'>
				<td class='td_SaisieLibelle'>
					<span id='lb_PTZMontant'>Montant du prêt :</span>
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_PTZMontant'
							id		= 'ed_PTZMontant'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->ptzmontant;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'FINANCEMENT_AfficheGraphe()'/>
				</td>
			</tr>				
			<tr id='PTZ_Duree'>
				<td class='td_SaisieLibelle'>
					 <span id='lb_PTZDuree'>Durée du prêt :</span>
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_PTZDuree'
							id		= 'ed_PTZDuree'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->credit->ptzduree;} ?>'
							class	= 'input_MontantUsuel'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
				</td>
			</tr>				
		</table>
	</div>
</div>
