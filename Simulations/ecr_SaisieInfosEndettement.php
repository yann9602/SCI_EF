		<Script Language="JavaScript">
			var LC_URL = "";
			function ENDETTEMENT_AfficheGraphe(){
				// calculer la part restante
				LN_ChargesA = TOOL_DecodeInt(document.getElementById("ed_AutreMensualite").value) +
							  TOOL_DecodeInt(document.getElementById("ed_ChargeResPrincipale").value)+
							  TOOL_DecodeInt(document.getElementById("ed_ChargePension").value);
				LN_Capacite = TOOL_DecodeInt(document.getElementById("ed_Revenus").value) +
							  TOOL_DecodeInt(document.getElementById("ed_RevenusFonciers").value) -
							  LN_ChargesA;
				// preparer l'URL
				var LC_Param = "";								 
				LC_Param += "1_";
				LC_Param += "Residence principale_";
				LC_Param += document.getElementById("ed_ChargeResPrincipale").value + ";";		
				LC_Param += "1_";
				LC_Param += "Autres charges_";
				LC_Param += LN_ChargesA + ";";
				LC_Param += "1_";
				LC_Param += "Capacite_";
				LC_Param += LN_Capacite;
				// n'afficher que si necessaire
				if (LC_URL!=LC_Param)
					{
						LC_URL=LC_Param;	
						TOOL_Ajax("grapheEndettement", "ENDETTEMENT_AfficheGraphe", LC_Param, true);			
					}				
			}
			
			function ENDETTEMENT_AfficheGrapheRetour(xhr){
				var LO_XML= xhr.responseXML;
				var LC_Image= TOOL_RecupereXML(LO_XML, "image", 0);
				var LC_Texte= TOOL_RecupereXML(LO_XML, "texte", 0);
				document.getElementById("img_GrapheEndettement").src = "Ajax/" + LC_Image + "?" +  Math.random();
				document.getElementById("div_GrapheEndettement").innerHTML = LC_Texte;
			}
			
			function ENDETTEMENT_AfficheCalculs(PC_Objet){
				var LC_Champs = "<?PHP Echo Def_CalculEndettement;?>";
				var LT_Champs = LC_Champs.split(";");
				var LC_Param  = PC_Objet + "&paramvaleurs=";
				for (i=0;i<LT_Champs.length;i++)
					{
						LC_Param = LC_Param + document.getElementById(LT_Champs[i]).value;
						LC_Param = LC_Param + ";";
					}
				TOOL_Ajax("calculEndettement", "ENDETTEMENT_AfficheCalculs", LC_Param);
			}
			
			function ENDETTEMENT_AfficheCalculsRetour(xhr){
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
			
			function ENDETTEMENT_Reagir(){
			}
		</Script>

<div style='float:left;width:100%;'>
	<!-- bloc superieur de gauche -->
	<div class = 'DI7030_Gauche'>
		<table class='table_Cadre'>
			<tr>
				<td colspan	= '2' 
					class	= 'td_LibelleTitre'>
					Ressources
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Salaire et assimilés :
				</td>
				<td>
					<input	type	= 'text'
							name	= 'ed_Revenus'
							id		= 'ed_Revenus'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->prixachat;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()' />
					<img class	='img_Recalcul'
						 onclick='ENDETTEMENT_AfficheCalculs("ed_Salaire")'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Revenus fonciers :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_RevenusFonciers'
							id		= 'ed_RevenusFonciers'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->fraisnotaire;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='5' 
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()'/>
					<img class	='img_Recalcul' 
						 onclick='ENDETTEMENT_AfficheCalculs("ed_FraisNotaire")'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Part de revenus fonciers pris en compte:
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_PartFoncier'
							id		= 'ed_PartFoncier'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->travauxndeduc;} ?>'
							class	= 'input_MontantUsuel'
							size	= '3'
							maxlength='3'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()'/>
				</td>
			</tr>
			<tr>
				<td colspan	= '2' 
					class	= 'td_LibelleTitre'>
					Charges prévisionnelles
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Montant de l'emprunt:
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_CapaciteEmprunt'
							id		= 'ed_CapaciteEmprunt'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->travauxdeduc;} ?>'
							class	= 'input_MontantUsuel'					
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()'/>&nbsp;
				</td>
			</tr>	
			<tr>
				<td class='td_SaisieLibelle'>
					Mensualités autres emprunts :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_AutreMensualite'
							id		= 'ed_AutreMensualite'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->apport;} ?>'
							class	= 'input_MontantUsuel'
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Loyer ou remboursement rÃ©sidence principale :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_ChargeResPrincipale'
							id		= 'ed_ChargeResPrincipale'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->credit->emprunt;} ?>'
							class	= 'input_MontantUsuel'					
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()'/>
				</td>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Pension alimentaire et assimilées :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_ChargePension'
							id		= 'ed_ChargePension'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->frais->credit->emprunt;} ?>'
							class	= 'input_MontantUsuel'					
							size	= '10'
							maxlength='7'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							onblur	= 'ENDETTEMENT_AfficheGraphe()'/>
				</td>
			</tr>
		</table>
	</div>

	<!-- bloc supÃ©rieur de droite -->
	<div class = 'DI7030_Droite'
		 style = 'text-align :center;';>
			<br/>
			<img id='img_GrapheEndettement' src='Images/Entete/im_Logo.png' style='width:60%;'/>
			<div id='div_GrapheEndettement' class='span_LibelleDetail'></div>
	</div>
</div>
