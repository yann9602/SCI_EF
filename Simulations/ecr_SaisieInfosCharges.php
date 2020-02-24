<Script Language="JavaScript">
	function CHARGES_Reagir()
	{
		document.getElementById("ed_RappelLoyers").value=INFOSBIENS_TotaliseDetail();
	}
	
	function CHARGES_ReagirFixe()
	{
		var LN_NbLoyer=document.getElementById("ed_NbLoyers").value;
		if (LN_NbLoyer>12)
		{
			alert("Il ne peut y avoir que 12 loyers mensuels par an");
			document.getElementById("ed_NbLoyers").focus();
			LN_NbLoyer=0;
		}
	}
</Script>

<div style='float:left;width:100%;'>
	<div class = 'FINANCEMENT_Gauche'>
		<table class='table_Cadre'>
			<tr>
				<td colspan	= '3' 
					class	= 'td_LibelleTitre td_LibelleTitreCharges'>
					Charges
				</td>
			</tr>
			<tr>
				<td></td>
				<th align='center'>
					<label class='td_SaisieLibelle'>€/an</label>
				</th>
				<th align='center'>
					<label class='td_SaisieLibelle'>% Evol</label>
				</th>
			</tr>
			<tr>
				<td class='td_SaisieLibelle'>
					Assurance propriétaire non occupant :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_AssurancePNO'
							id		= 'ed_AssurancePNO'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->assurancepno;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolAssurancePNO'
							id		= 'ed_EvolAssurancePNO'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->evolassurancepno;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Assurance garantie risques locatifs :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_AssuranceGRL'
							id		= 'ed_AssuranceGRL'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->assurancegrl;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolAssuranceGRL'
							id		= 'ed_EvolAssuranceGRL'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->evolassurancegrl;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
							
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Assurance copropriété :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_AssuranceCOPRO'
							id		= 'ed_AssuranceCOPRO'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->simulation->charges->assurancecopro;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolAssuranceCOPRO'
							id		= 'ed_EvolAssuranceCOPRO'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->evolassurancecopro;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Charges copropriété :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_ChargesCOPRO'
							id		= 'ed_ChargesCOPRO'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->chargescopro;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolChargesCOPRO'
							id		= 'ed_EvolChargesCOPRO'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->evolchargescopro;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Autres charges :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_AutresCharges'
							id		= 'ed_AutresCharges'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->autrescharges;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolAutresCharges'
							id		= 'ed_EvolAutresCharges'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->simulation->charges->evolautrescharges;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Frais de gestion :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_FraisGestion'
							id		= 'ed_FraisGestion'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->fraisgestion;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolFraisGestion'
							id		= 'ed_EvolFraisGestion'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->evolfraisgestion;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
				</td>
			</tr>

			<tr>
				<td class='td_SaisieLibelle'>
					Travaux d'entretien annuels :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_TravauxAnnuels'
							id		= 'ed_TravauxAnnuels'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->travauxannuels;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolTravauxAnnuels'
							id		= 'ed_EvolTravauxAnnuels'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->simulation->charges->evoltravauxannuels;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)' />
				</td>
			</tr>
		</table>
	</div>
	
	<div class = 'FINANCEMENT_Droite'>
		<table class='table_Cadre'>
			<tr>
				<td colspan	= '2' 
					class	= 'td_LibelleTitre  td_LibelleTitreCharges'>
					Ressources
				</td>
			</tr>
			
			<tr>
				<td class='td_SaisieLibelle'>
					Nombre de loyers considérés par an :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_NbLoyers'
							id		= 'ed_NbLoyers'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->nbloyers;} ?>'
							size	= '3'
							maxlength='3'
							onblur	= 'CHARGES_ReagirFixe()'/>
				</td>
			</tr>
			
			<tr>
				<td class='td_SaisieLibelle'>
					Evolution annuelle des loyers :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EvolLoyers'
							id		= 'ed_EvolLoyers'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->simulation->charges->evolloyers;} ?>'
							size	= '5'
							maxlength='5'
							onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'	/>
					<label class='td_SaisieLibelle'>&nbsp;%</label>
				</td>
			</tr>
			
			<tr>
				<td class='td_SaisieLibelle'>
					Montant des loyers (rappel):
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_RappelLoyers'
							id		= 'ed_RappelLoyers'
							class	= 'input_MontantUsuel'
							readonly= 'true'
							size	= '5'
							maxlength='5' />
					<label class='td_SaisieLibelle'>&nbsp;€</label>
				</td>
			</tr>
		
		</table>
	</div>
</div>
