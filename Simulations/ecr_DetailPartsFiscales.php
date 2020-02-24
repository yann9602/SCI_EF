<table class='table_Cadre'>
	<tr><td width='50%'><td width='50%'></tr>
	<tr>
		<td	colspan='2'
			class	= 'td_LibelleTitre td_LibelleTitreInvestisseur'>
			Fiscalité
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle' style='text-align:right;height:15px;'>
			<label>Nombre de parts :</label>
			<input type			= 'text'
					size		='3'
					maxlength	='3'
					class		= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'
					id			='ed_FiscNbPart1'
					name		='ed_FiscNbPart1'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[0]->parts;} ?>'/>
		</td>
		<td>
			<input type			= 'hidden'
					size		='2'
					maxlength	='2'
					class		= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'					
					name		='ed_FiscDelai1'
					id			='ed_FiscDelai1'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[0]->delai;} ?>'/>
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle' style='text-align:right;height:15px;'>
			<label>Nombre de parts :</label>
			<input type			= 'text'
					size		='3'
					maxlength	='3'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'					
					id			='ed_FiscNbPart2'
					name		='ed_FiscNbPart2'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[1]->parts;} ?>'/>
		</td>
		<td class='td_SaisieLibelle' style='text-align:left;height:15px;'>
			<label>après :</label>		
			<input type			= 'text'
					size		='2'
					maxlength	='2'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'					
					name		='ed_FiscDelai2'
					id			='ed_FiscDelai2'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[1]->delai;} ?>'/>
			<label> année(s)</label>										
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle' style='text-align:right;height:15px;'>
			<label>Nombre de parts :</label>
			<input type			= 'text'
					size		='3'
					maxlength	='3'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'
					id			='ed_FiscNbPart3'
					name		='ed_FiscNbPart3'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[2]->parts;} ?>'/>
		</td>
		<td class='td_SaisieLibelle' style='text-align:left;height:15px;'>
			<label>après :</label>		
			<input type			= 'text'
					size		='2'
					maxlength	='2'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'					
					name		='ed_FiscDelai3'
					id			='ed_FiscDelai3'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[2]->delai;} ?>'/>
			<label> année(s)</label>										
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle' style='text-align:right;height:15px;'>
			<label>Nombre de parts :</label>
			<input type			= 'text'
					size		='3'
					maxlength	='3'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'					
					id			='ed_FiscNbPart4'
					name		='ed_FiscNbPart4'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[3]->parts;} ?>'/>
		</td>
		<td class='td_SaisieLibelle' style='text-align:left;height:15px;'>
			<label>après :</label>		
			<input type			= 'text'
					size		='2'
					maxlength	='2'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'					
					name		='ed_FiscDelai4'
					id			='ed_FiscDelai4'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[3]->delai;} ?>'/>
			<label> année(s)</label>										
		</td>
	</tr>
	<tr>
		<td class='td_SaisieLibelle' style='text-align:right;height:15px;'>
			<label>Nombre de parts :</label>
			<input type			= 'text'
					size		='3'
					maxlength	='3'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'					
					id			='ed_FiscNbPart5'
					name		='ed_FiscNbPart5'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[4]->parts;} ?>'/>
		</td>
		<td class='td_SaisieLibelle' style='text-align:left;height:15px;'>
			<label>après :</label>		
			<input type			= 'text'
					size		='2'
					maxlength	='2'
					class	= 'input_MontantUsuel'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'					
					name		='ed_FiscDelai5'
					id			='ed_FiscDelai5'
					value		= '<?PHP  if ($LB_XML){echo $LO_XML->investisseur->fiscalite->partsfiscales->periode[4]->delai;} ?>'/>
			<label> année(s)</label>										
		</td>	
	</tr>
</table>	

