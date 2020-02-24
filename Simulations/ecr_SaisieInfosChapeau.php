<Script Language="JavaScript">
	
</Script>

<div class = 'td_LibelleTitre td_LibelleTitreInvestisseur'>
	Simulation financière
</div>
		
<div style='float:left;width:100%;'>
	<!-- bloc inferieur de gacuhe -->
	<div class = 'FINANCEMENT_Gauche'>	
		<table class='table_Cadre'>
			<tr>
				<td  class='td_SaisieLibelle'>
					Investisseur :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_InvestisseurNom'
							id		= 'ed_InvestisseurNom'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->nom;} ?>'
							class	= 'input_Usuel'
							size	= '30'
							maxlength='30' />
				</td>		
			</tr>	
			
			<tr>
				<td  class='td_SaisieLibelle'>
					Nom de l'étude :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EtudeNom'
							id		= 'ed_EtudeNom'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->simulation->nom;} ?>'
							class	= 'input_Usuel'
							size	= '30'
							maxlength='30' />
				</td>		
			</tr>
		</table>
	</div>	
	<div class = 'FINANCEMENT_Droite'>	
		<table class='table_Cadre'>
			<tr>
				<td  class='td_SaisieLibelle'>
					Durée de l'étude :
				</td>
				<td>
				<!--<select name	= 'cb_Objet'
							id		= 'cb_Objet'
							class	= 'input_Usuel'>
							<option value='APP'  <?PHP if ($LB_XML){echo TOOL_DecodeCombo($LO_XML->simulation->objet, "APP");} ?>> Appartement</option>
							<option value='STU'  <?PHP if ($LB_XML){echo TOOL_DecodeCombo($LO_XML->simulation->objet, "STU");} ?>> Studio</option>
							<option value='MAI'  <?PHP if ($LB_XML){echo TOOL_DecodeCombo($LO_XML->simulation->objet, "MAI");} ?>> Maison</option>
							<option value='PARK' <?PHP if ($LB_XML){echo TOOL_DecodeCombo($LO_XML->simulation->objet, "PARK");} ?>>Parking</option>
					</select>-->
					<input type		= 'text'
							name	= 'ed_EtudeDuree'
							id		= 'ed_EtudeDuree'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->simulation->duree;} ?>'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
							class	= 'input_Usuel'
							size	= '3'
							maxlength='3' />
					<label class='td_SaisieLibelle'>&nbsp;années</label>
				</td>		
			</tr>	
			<tr>
				<td  class='td_SaisieLibelle'>
					Date de début du projet :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_EtudeDebut'
							id		= 'ed_EtudeDebut'
							value	= '<?PHP if ($LB_XML){echo $LO_XML->simulation->datedebut;} ?>'
							class	= 'input_Usuel'
							size	= '10'
							maxlength='10' />
				</td>		
			</tr>
			<?PHP if (TOOL_SessionLire("SESSION", "USER_STATUT") == 'SUPER'){ ?>
			<tr>
				<td  class='td_SaisieLibelle'>
					<a href='Data/XML/<?PHP echo $LC_SimulationXML;?>'>Télécharger XML</a>
				</td>
			</tr>
			<?PHP } ?>
		</table>
	</div>
</div>
<br/>
