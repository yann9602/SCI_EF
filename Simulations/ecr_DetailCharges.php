<table class='table_Cadre'>
	<tr><td width='50%'><td width='50%'></tr>
	<tr>
		<td colspan	= '2' 
			class	= 'td_LibelleTitre td_LibelleTitreInvestisseur'>
			Charges prévisionnelles
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Capacité d'emprunt:
		</td>
		<td>
			<input type		= 'text'
					name	= 'ed_CapaciteEmprunt'
					id		= 'ed_CapaciteEmprunt'
					value	= "<?PHP if ($LB_XML){echo $LO_XML->investisseur->charges->capacite;} ?>"
					class	= 'input_MontantUsuel'					
					size	= '7'
					maxlength='7'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
			<img class	='img_Recalcul'
						 src	='Images/BoutonsFonctionnement/btn_Calculs.png'
						 onclick='INVESTISSEUR_AfficheCalculs("ed_CapaciteEmprunt")'/>
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
					value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->charges->autres;} ?>'
					class	= 'input_MontantUsuel'
					size	= '5'
					maxlength='5'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					onblur	= 'INVESTISSEUR_AfficheGraphe()'/>
			<label class='td_SaisieLibelle'>&nbsp;€/mois</label>										
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Loyer ou remboursement <br/>résidence principale :
		</td>
		<td>
			<input type		= 'text'
					name	= 'ed_ChargeResPrincipale'
					id		= 'ed_ChargeResPrincipale'
					value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->charges->residenceprincipale;} ?>'
					class	= 'input_MontantUsuel'					
					size	= '5'
					maxlength='5'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					onblur	= 'INVESTISSEUR_AfficheGraphe()'/>
			<label class='td_SaisieLibelle'>&nbsp;€/mois</label>										
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Pension(s) alimentaire(s) <br/>et assimilées :
		</td>
		<td>
			<input type		= 'text'
					name	= 'ed_ChargePension'
					id		= 'ed_ChargePension'
					value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->charges->pensions;} ?>'
					class	= 'input_MontantUsuel'					
					size	= '5'
					maxlength='5'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					onblur	= 'INVESTISSEUR_AfficheGraphe()'/>
			<label class='td_SaisieLibelle'>&nbsp;€/mois</label>										
		</td>
	</tr>
</table>
