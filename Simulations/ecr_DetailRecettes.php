<table class='table_Cadre'>
	<tr><td width='50%'><td width='50%'></tr>
	<tr>
		<td colspan	= '2' 
			class	= 'td_LibelleTitre td_LibelleTitreInvestisseur'>
			Ressources
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Salaire(s) et assimilés :
		</td>
		<td>
			<input	type	= 'text'
					name	= 'ed_Salaire'
					id		= 'ed_Salaire'
					value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->ressources->salaire;} ?>'
					class	= 'input_MontantUsuel'
					size	= '5'
					maxlength='5'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					onblur	= 'INVESTISSEUR_AfficheGraphe()' />
			<label class='td_SaisieLibelle'>&nbsp;€/an</label>
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
					value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->ressources->foncier;} ?>'
					class	= 'input_MontantUsuel'
					size	= '5'
					maxlength='5' 
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					onblur	= 'INVESTISSEUR_AfficheGraphe()'/>
			<label class='td_SaisieLibelle'>&nbsp;€/an</label>					
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle'>
			Evolution des revenus :
		</td>
		<td>
			<input type		= 'text'
					name	= 'ed_EvolRevenus'
					id		= 'ed_EvolRevenus'
					value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->ressources->evolrevenus;} ?>'
					class	= 'input_MontantUsuel'
					size	= '4'
					maxlength='4' 
					onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'/>
			<label class='td_SaisieLibelle'>&nbsp;% par an</label>					
		</td>
	</tr>	
</table>	