<Script Language="JavaScript">
	function FISCALITE_Init()
	{
		var LC_Type = "<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->typefiscalite;} ?>";
		if (LC_Type==""){LC_Type="indiv";}
		if (LC_Type=="indiv")
		{
			document.getElementById("ed_TypeFiscalite").value = "indiv";
			document.getElementById("rd_FiscalitePHY").checked = true;
			FISCALITE_ReagirType("PHY")
		}
		if (LC_Type=="sci")
		{
			document.getElementById("ed_TypeFiscalite").value = "sci";
			document.getElementById("rd_FiscaliteSCI").checked = true;
			FISCALITE_ReagirType("SCI")
		}
	}

	function FISCALITE_ReagirType(PC_Type)
	{
		if (PC_Type == "SCI"){document.getElementById("ed_TypeFiscalite").value = "sci";}
		if (PC_Type == "PHY"){document.getElementById("ed_TypeFiscalite").value = "indiv";}
		document.getElementById("di_FiscalitePHY").style.display = "none";
		document.getElementById("di_FiscaliteSCI").style.display = "none";
		document.getElementById("di_Fiscalite" + PC_Type).style.display = "block";
	}
</Script>

<div style='float:left;width:100%;'>
	<!-- bloc superieur de gauche -->
	<table class='table_Cadre'>
		<tr>
			<td class	= 'td_LibelleTitre td_LibelleTitreFiscalite'>
				Fiscalité
			</td>
		</tr>
		<tr>
			<td	class = 'td_SaisieLibelle'
				style = 'text-align:left;'>
				<input type		= 'hidden'
						id		= 'ed_TypeFiscalite'
						name	= 'ed_TypeFiscalite'
						value	= '<?PHP Echo $LO_XML->investisseur->fiscalite->typefiscalite;?>' />
				<br/>
				<input type		= 'radio'
						id		= 'rd_FiscaliteSCI'
						name	= 'rd_TypeFiscalite'
						value	= 'sci'
						onclick = 'FISCALITE_ReagirType("SCI")'/>
				<label class='td_SaisieLibelle'>Investissement en SCI</label>
				<br/>
				<input type		= 'radio'
						id		= 'rd_FiscalitePHY'
						name	= 'rd_TypeFiscalite'
						onclick = 'FISCALITE_ReagirType("PHY")'
						value	= 'indiv' />
				<label class='td_SaisieLibelle'>Investissement Personne physique</label>
			</td>
		</tr>		
	</table>

	<div id='di_FiscalitePHY'>	
		<table class='table_Cadre'>		
			<tr>
				<td class='td_SaisieLibelle'>
					Taxe foncière :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_PhyMontantTF'
							id		= 'ed_PhyMontantTF'
							size	= '7'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->taxefonciere;} ?>'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;€</label>
				</td>
				<td class='td_SaisieLibelle'>
					Evolution (% par an) :
				</td>
				<td>
					<input type		= 'text'
							name	= 'ed_PhyEvolTF'
							id		= 'ed_PhyEvolTF'
							size	= '3'
							class	= 'input_MontantUsuel'
							value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->evoltaxefonciere;} ?>'
							onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
				</td>				
			</tr>
		</table>
		<br/>
		<div id='di_PHY_Gauche' class='FINANCEMENT_Gauche'>
			<table class='table_Cadre'>
				<tr>
					<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
						colspan='2'>
						Tranches d'imposition
					</td>
				</tr>	
				<tr>
					<td	class = 'td_SaisieLibelle'
						style = 'text-align:center;'>
						<label class='td_SaisieLibelle'>Tranche 1 :</label>
						<input type			= 'text'
								size		= '5'
								maxlength	= '5'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Tranche1'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche1;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;€</label>
						<label class='td_SaisieLibelle'>Taux 1 :</label>		
						<input type			= 'text'
								size		= '3'
								maxlength	= '3'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Taux1'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux1;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)' />
						<label class='td_SaisieLibelle'>&nbsp;%</label>
						<br/>
						<label class='td_SaisieLibelle'>Tranche 2 :</label>
						<input type			= 'text'
								size		= '5'
								maxlength	= '5'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Tranche2'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche2;} ?>' 
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;€</label>
						<label class='td_SaisieLibelle'>Taux 2 :</label>		
						<input type			= 'text'
								size		= '3'
								maxlength	= '3'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Taux2'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux2;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;%</label>
						<br/>
						<label class='td_SaisieLibelle'>Tranche 3 :</label>
						<input type			= 'text'
								size		= '5'
								maxlength	= '5'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Tranche3'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche3;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;€</label>
						<label class='td_SaisieLibelle'>Taux 3 :</label>		
						<input type			= 'text'
								size		= '3'
								maxlength	= '3'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Taux3'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux3;} ?>'/>
						<label class='td_SaisieLibelle'>&nbsp;%</label>
						<br/>
						<label class='td_SaisieLibelle'>Tranche 4 :</label>
						<input type			= 'text'
								size		= '5'
								maxlength	= '5'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Tranche4'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtranche4;} ?>' 
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;€</label>
						<label class='td_SaisieLibelle'>Taux 4 :</label>		
						<input type			= 'text'
								size		= '3'
								maxlength	= '3'
								class		= 'input_MontantUsuel'
								name		= 'ed_TIPP_Taux4'
								value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->TIPPtaux4;} ?>' />
						<label class='td_SaisieLibelle'>&nbsp;%</label>
						<br/>
						
					</td>
				</tr>
			</table>
		</div>
		<div id='di_PHY_Droite' class='FINANCEMENT_Droite'>
			<table class='table_Cadre'>
				<tr>
					<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
						colspan='2'>
						Régime Micro-Fonciers
					</td>
				</tr>
				<tr>				
					<td class='td_SaisieLibelle'>
						Plafond :
					</td>
					<td>
						<input type			= 'text'
								name		= 'ed_MfPlafond'
								id			= 'ed_MfPlafond'
								class		= 'input_MontantUsuel'
								size		= '7'
								maxlength	= '7'
								value		= '<?PHP if ($LB_XMLS)
														  {echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->mfplafond;}
													 else {echo	Def_PlafondRMF;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;€</label>
					</td>
				</tr>		
				<tr>	
					<td class='td_SaisieLibelle'>
						Abattement :
					</td>
					<td>
						<input type			= 'text'
								name		= 'ed_MfAbattement'
								id			= 'ed_MfAbattement'
								class		= 'input_MontantUsuel'
								size		= '7'
								maxlength	= '7'								
								value		= '<?PHP if ($LB_XMLS)
														  {echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->mfabattement;}
													 else {echo Def_AbattementRMF;} ?>'
								onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
					<label class='td_SaisieLibelle'>&nbsp;%</label>
					</td>		
				</tr>
			</table>	
		</div>
		<div style='float:left;width:100%;'>
			<div class = 'FINANCEMENT_Gauche'>
				<table class='table_Cadre'>		
					<tr>
						<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
							colspan='2'>
							Cotisations sociales
						</td>
					</tr>	
					<tr>
						<td class='td_SaisieLibelle'>
							CSG + CRDS :
						</td>
						<td>
							<input type		= 'text'
									name	= 'ed_PhyCotisations'
									id		= 'ed_PhyCotisations'
									class	= 'input_MontantUsuel'
									value	= '<?PHP if ($LB_XMLS)
													  {echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->cotisations;} 
											     else {echo Def_ValeurTauxCotSociale;} ?>'
									onkeypress='return TOOL_FiltreSaisie("DECIMAL", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
						</td>		
					</tr>
				</table>
			</div>
			<div class = 'FINANCEMENT_Droite'>
				<table class='table_Cadre'>				
					<tr>
						<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
							colspan='2'>
							Régime LMNP
						</td>
					</tr>
					<tr>				
						<td class='td_SaisieLibelle'>
							Plafond micro BIC :
						</td>
						<td>
							<input type			= 'text'
									name		= 'ed_LMNPPlafond'
									id			= 'ed_LMNPPlafond'
									class		= 'input_MontantUsuel'
									size		= '7'
									maxlength	= '7'
									value		= '<?PHP if ($LB_XMLS)
															  {echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->lmnpplafond;} 
														 else {echo Def_PlafondMicroBIC;}?>'
									onkeypress	='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;€</label>
						</td>
					</tr>		
					<tr>	
						<td class='td_SaisieLibelle'>
							Abattement micro BIC :
						</td>
						<td>
							<input type			= 'text'
									name		= 'ed_LMNPAbattement'
									id			= 'ed_LMNPAbattement'
									class		= 'input_MontantUsuel'
									size		= '7'
									maxlength	= '7'
									value		= '<?PHP if ($LB_XMLS)
															  {echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->lmnpabattement;} 
														 else {echo Def_AbattementMicroBIC;}
													?>'
									onkeypress	='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
						</td>		
					</tr>
					<tr>	
						<td class='td_SaisieLibelle'>
							Durée amortissement <br/>immeubles et travaux :
						</td>
						<td>
							<input type			= 'text'
									name		= 'ed_LMNPAmortimm'
									id			= 'ed_LMNPAmortimm'
									class		= 'input_MontantUsuel'
									size		= '3'
									maxlength	= '3'
									value		= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->amortimm;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>années</label>
						</td>		
					</tr>
					<tr>	
						<td class='td_SaisieLibelle'>
							Durée amortissement mobilier :
						</td>
						<td>
							<input type			= 'text'
									name		= 'ed_LMNPAmortmob'
									id			= 'ed_LMNPAmortmob'
									class		= 'input_MontantUsuel'
									size		= '3'
									maxlength	= '3'								
									value		= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->PPHYSIQUE->amortmob;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>années</label>
						</td>		
					</tr>
				</table>	
			</div>
		</div>
	</div>
	
	<!-- SCI -->
	<div id='di_FiscaliteSCI' style='display:none;'>
		<div style='float:left;width:100%;'>
			<table class='table_Cadre'>		
				<tr>
					<td class='td_SaisieLibelle'>
						Taxe foncière :
					</td>
					<td>
						<input type		= 'text'
								name	= 'ed_SciMontantTF'
								id		= 'ed_SciMontantTF'
								size	= '7'
								class	= 'input_MontantUsuel'
								value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->taxefonciere;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;€</label>
					</td>
					<td class='td_SaisieLibelle'>
						Evolution (% par an) :
					</td>
					<td>
						<input type		= 'text'
								name	= 'ed_SciEvolTF'
								id		= 'ed_SciEvolTF'
								size	= '3'
								class	= 'input_MontantUsuel'
								value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->evoltaxefonciere;} ?>'
								onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
						<label class='td_SaisieLibelle'>&nbsp;%</label>
					</td>				
				</tr>
			</table>
		</div>	
		<br/>		
		&nbsp;<br/>
		<div style='float:left;width:100%;'>
			<div id='di_SCI_Gauche' class='FINANCEMENT_Gauche' style='background-color:'#FF0000'>		
				<table class='table_Cadre'>		
					<tr>
						<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
							colspan='2'>
							Impôts sur les Sociétés
						</td>
					</tr>	
					<tr>
						<td	class = 'td_SaisieLibelle'
							style = 'text-align:center;'>
							<label class='td_SaisieLibelle'>Tranche 1 :</label>
							<input type			= 'text'
									size		= '5'
									maxlength	= '5'
									class		= 'input_MontantUsuel'
									name		= 'ed_TISCI_Tranche1'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TISCItranche1;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;€</label>
							<label class='td_SaisieLibelle'>Taux 1 :</label>		
							<input type			= 'text'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'
									name		= 'ed_TISCI_Taux1'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TISCItaux1;}?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
							<br/>
							<label class='td_SaisieLibelle'>Tranche 2 :</label>
							<input type			= 'text'
									size		= '5'
									maxlength	= '5'
									class		= 'input_MontantUsuel'
									name		= 'ed_TISCI_Tranche2'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TISCItranche2;} ?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;€</label>
							<label class='td_SaisieLibelle'>Taux 2 :</label>		
							<input type			= 'text'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'
									name		= 'ed_TISCI_Taux2'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TISCItaux2;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)' />
							<label class='td_SaisieLibelle'>&nbsp;%</label>
						</td>
					</tr>
				</table>
			</div>
			<div id='di_SCI_Droite' class='FINANCEMENT_Droite' style='background-color:'#FF0000'>				
				<table class='table_Cadre'>		
					<tr>
						<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
							colspan='2'>
							Dividendes
						</td>
					</tr>	
					<tr>
						<td class='td_SaisieLibelle'>
							Versés à l'associé :
						</td>
						<td>
							<input type		= 'text'
									name	= 'ed_SciDividendesVerses'
									id		= 'ed_SciDividendesVerses'
									size	= '7'
									class	= 'input_MontantUsuel'
									value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->dividendesverses;} ?>'
									onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;% trésorerie disp</label>
						</td>
					</tr>
					<tr>
						<td class='td_SaisieLibelle'>
							Imposés après abattement :
						</td>
						<td>
							<input type		= 'text'
									name	= 'ed_SciDividendesImposes'
									id		= 'ed_SciDividendesImposes'
									size	= '7'
									class	= 'input_Usuel'
									value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->dividendesimposes;} ?>'
									onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
						</td>
					</tr>
					<tr>
						<td class='td_SaisieLibelle'>
							Puis :
						</td>
						<td>
							<input type		= 'text'
									name	= 'ed_SciDividendesSuite'
									id		= 'ed_SciDividendesSuite'
									size	= '7'
									class	= 'input_MontantUsuel'
									value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->dividendessuite;} ?>'
									onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;€</label>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<br>
		<br>
		<div style='float:left;width:100%;'>
			<div id='di_SCI_Gauche2' class='FINANCEMENT_Gauche' style='background-color:'#FF0000'>		
				<table class='table_Cadre'>		
					<tr>
						<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
							colspan='2'>
							Durée d'amortissement
						</td>
					</tr>	
					<tr>
						<td class='td_SaisieLibelle'>
							Immeubles
						</td>
						<td>
							<input type		= 'text'
									name	= 'ed_SciAmortImm'
									id		= 'ed_SciAmortImm'
									size	= '7'
									class	= 'input_MontantUsuel'
									value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->amortimm;} ?>'
									onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;années</label>
						</td>
					</tr>
					<tr>
						<td class='td_SaisieLibelle'>
							Mobiliers
						</td>
						<td>
							<input type		= 'text'
									name	= 'ed_SciAmortMob'
									id		= 'ed_SciAmortMob'
									size	= '7'
									class	= 'input_MontantUsuel'
									value	= '<?PHP if ($LB_XMLS){echo $LO_XML->investisseur->fiscalite->SCI->amortmob;} ?>'
									onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;années</label>
						</td>
					</tr>
				</table>
			</div>
			<div id='di_SCI_Droite3' class='FINANCEMENT_Droite' style='background-color:'#FF0000'>				
				<table class='table_Cadre'>		
					<tr>
						<td class='td_LibelleTitre td_LibelleTitreFiscalite' 
							colspan='2'>
							Revenus de l'associé<br>
						</td>
					</tr>	
					<tr>
						<td	class = 'td_SaisieLibelle'
							style = 'text-align:center;'>
							Tranches d'imposition :
						</td>
					</tr>
					<tr>
						<td	class = 'td_SaisieLibelle'
							style = 'text-align:center;'>
							<label>Tranche 1 :</label>
							<input type			= 'text'
									size		= '5'
									maxlength	= '5'
									class		= 'input_MontantUsuel'
									name		='ed_TIASS_Tranche1'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStranche1;} ?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;€</label>
							<label>Taux 1 :</label>		
							<input type			= 'text'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Taux1'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStaux1;} ?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
							<br/>
							<label>Tranche 2 :</label>
							<input type			= 'text'
									size		= '5'
									maxlength	= '5'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Tranche2'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStranche2;} ?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)' />
							<label class='td_SaisieLibelle'>&nbsp;€</label>
							<label>Taux 2 :</label>		
							<input type			= 'text'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Taux2'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStaux2;} ?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)' />
							<label class='td_SaisieLibelle'>&nbsp;%</label>
							<br/>
							<label>Tranche 3 :</label>
							<input type			= 'text'
									size		= '5'
									maxlength	= '5'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Tranche3'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStranche3;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)' />
							<label class='td_SaisieLibelle'>&nbsp;€</label>
							<label>Taux 3 :</label>		
							<input type			= 'text'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Taux3'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStaux3;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)' />
							<label class='td_SaisieLibelle'>&nbsp;%</label>
							<br/>
							<label>Tranche 4 :</label>
							<input type			= 'text'
									size		= '5'
									maxlength	= '5'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Tranche4'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStranche4;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)' />
							<label class='td_SaisieLibelle'>&nbsp;€</label>
							<label>Taux 4 :</label>		
							<input type			= 'text'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'
									name		= 'ed_TIASS_Taux4'
									value		= '<?PHP if ($LB_XML){echo $LO_XML->investisseur->fiscalite->SCI->TIASStaux4;} ?>' 
									onkeypress	= 'return TOOL_FiltreSaisie("ENTIER", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
							<br/>					
						</td>
					</tr>
				</table>
				<br/>
				<table class='table_Cadre'>					
					<tr>				
						<td class='td_SaisieLibelle'>
							Taux de cotisation sociale :
						</td>
						<td>
							<input type			= 'text'
									name		= 'ed_TauxCotSociale'
									id			= 'ed_TauxCotSociale'
									size		= '3'
									maxlength	= '3'
									class		= 'input_MontantUsuel'	
									value		= '<?PHP if ($LB_XMLS)
														{echo $LO_XML->investisseur->fiscalite->SCI->tauxcotsociale;}
														 else 
														{echo Def_ValeurTauxCotSociale;} ?>'
									onkeypress	= 'return TOOL_FiltreSaisie("DECIMAL", event)'/>
							<label class='td_SaisieLibelle'>&nbsp;%</label>
						</td>
					</tr>		
				</table>
			</div>
		</div>	
	</div>
</div>
