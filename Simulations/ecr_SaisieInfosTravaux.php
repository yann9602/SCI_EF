<Script Language="JavaScript">
	function TRAVAUX_Totalise(){
		var LN_TotalMob = 0;
		var LN_TotalD   = 0;
		var LN_TotalND  = 0;
		for (i=1; i <15; i++){
			var LO_MontantND  = parseInt("0"+document.getElementById("ed_TRAVAUX_NonDeductible"+i).value);
			var LO_MontantD   = parseInt("0"+document.getElementById("ed_TRAVAUX_Deductible"+i).value);
			var LO_MontantMob = parseInt("0"+document.getElementById("ed_TRAVAUX_Mobilier"+i).value);
			LN_TotalD   = LN_TotalD   + LO_MontantD;
			LN_TotalND  = LN_TotalND  + LO_MontantND;
			LN_TotalMob = LN_TotalMob + LO_MontantMob;
		}
		document.getElementById("ed_PrixTravauxNDeduc").value=LN_TotalND;
		document.getElementById("ed_PrixTravauxDeduc").value =LN_TotalD;
		document.getElementById("ed_PrixMobilier").value     =LN_TotalMob;
	}
	
	
	function TRAVAUX_ReagirLigne(PC_Ligne, PC_Type){
		var LO_MontantND = document.getElementById("ed_TRAVAUX_NonDeductible"+PC_Ligne);
		var LO_PartND    = document.getElementById("ed_TRAVAUX_PartNonDeductible"+PC_Ligne);
		var LO_MontantD  = document.getElementById("ed_TRAVAUX_Deductible"+PC_Ligne);
		var LO_PartD     = document.getElementById("ed_TRAVAUX_PartDeductible"+PC_Ligne);
		var LO_MontantMOB= document.getElementById("ed_TRAVAUX_Mobilier"+PC_Ligne);
		var LO_Total     = document.getElementById("ed_TRAVAUX_Global"+PC_Ligne);
		var LO_TotalTrav = 0;
		LN_Total=0;
		if (PC_Type!="T")
			{
				LN_Total = parseInt("0"+LO_MontantND.value) 
						 + parseInt("0"+LO_MontantD.value)
						 + parseInt("0"+LO_MontantMOB.value)
				LO_Total.value=LN_Total;		   
			}	
			
		if ((PC_Type=="MND") || (PC_Type=="MD") )
			{
				LN_TotalTrav =  parseInt("0" + LO_MontantND.value) + 
								parseInt("0" + LO_MontantD.value);
				if (LN_TotalTrav != 0){
					LO_PartND.value = Math.round(parseInt("0" + LO_MontantND.value) * 100 / LN_TotalTrav);
					LO_PartD.value  = Math.round(parseInt("0" + LO_MontantD.value)  * 100 / LN_TotalTrav);
				}	
			}
		if (PC_Type=="T")
			{
				LN_TotalTrav =  LO_Total.value -
								parseInt("0" + LO_MontantMOB.value)
				LO_MontantND.value = LN_TotalTrav * parseInt("0" + LO_PartND.value) / 100;
				LO_MontantD.value  = LN_TotalTrav * parseInt("0" + LO_PartD.value) / 100;				
			}
		TRAVAUX_Totalise();	
	}
			function TRAVAUX_InsereLigne(){
				    //var tr = document.createElement('tr');
//					elmt.appendChild(tr);
  //   
					//var td = document.createElement('td');
					//tr.appendChild(td);
					//var tdText = document.createTextNode(value);
					//td.appendChild(tdText);
//					
					//var tbody = document.getElementById
//(id).getElementsByTagName("TBODY")[0];
//var annee=an-5-compteur;
    //var row = document.createElement("TR")
 
// 
//var td1 = document.createElement("TD")
    //var td1a = td1.appendChild (document.createElement('input'))
//td1a.setAttribute("type", "text")
//td1a.setAttribute("name", "AN"+annee)
//td1a.setAttribute("value", annee)
//td1a.setAttribute("readonly", "readonly")
// 
    //var td2 = document.createElement("TD")
//var td2a = td2.appendChild (document.createElement('input'))
////td2a.setAttribute("type", "text")
//td2a.setAttribute("name", "service"+annee)
 
//var td3 = document.createElement("TD")
//var td3a = td3.appendChild (document.createElement('input'))
//td3a.setAttribute("type", "text")
//td3a.setAttribute("name", "bureau"+annee)
 
//var td4 = document.createElement("TD")
//var td4a = td4.appendChild (document.createElement('input'))
//td4a.setAttribute("type", "text")
//td4a.setAttribute("name", "fonction"+annee)
 
//row.appendChild(td1);
    //row.appendChild(td2);
//row.appendChild(td3);
//row.appendChild(td4);
			}
		</Script>
		
<div class = 'td_LibelleTitre td_LibelleTitreTravaux'>
	Frais des travaux engagés
</div>
				

<table class='table_Cadre' style='width:90%'>
	<!-- entete -->
	<tr>
		<td width='25%'></td>
		<td width='5%'></td>		
		<td width='12%'></td>
		<td width='9%'></td>
		<td width='12%'></td>
		<td width='9%'></td>
		<td width='12%'></td>		
		<td width='12%'></td>		
	</tr>
	<tr>
		<th class='td_TitreGH' rowspan='2'>
			Descriptif
		</th>
		<th class='td_TitreGH'>
			Délai
		</th>
		<th class='td_TitreGH' colspan='2'>
			Part non déductible
		</th>	
		<th class='td_TitreGH' colspan='2'>
			Part déductible
		</th>		
		<th class='td_TitreGDH' rowspan='2'>
			Mobilier
		</th>
		<th class='td_TitreGDH' rowspan='2'>
			Coût global
		</th>
	</tr>
	<tr>
		<th class='td_TitreGH'>
			<select name	='cb_DelaiTravaux'
					style	='width:60px;'
					class	= 'input_Usuel'>
				<option value='M' <?PHP if ($LB_XMLS){echo TOOL_DecodeCombo($LO_XML->simulation->travaux->delai, "M");} ?>>/mois</option>
				<option value='A' <?PHP if ($LB_XMLS){echo TOOL_DecodeCombo($LO_XML->simulation->travaux->delai, "A");} ?>>/années</option>
			</select>
		</th>
		<th class='td_TitreGH'>
			En euros
		</th>
		<th class='td_TitreH'>
			%
		</th>		
		<th class='td_TitreGH'>
			En euros			
		</th>
		<th class='td_TitreH'>
			%
		</th>
	</tr>
	<?PHP
		for ($i=1; $i<15; $i++)
			{
	?>
		<tr>
			<td class='td_DetailG'>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_Lib<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_Lib<?PHP Echo $i; ?>'
					class	= 'input_Usuel'	
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->libelle;} ?>' 
					size	= '20'
					maxlength='30' />
			</td>
			<td class='td_DetailG'>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_Delai<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_Delai<?PHP Echo $i; ?>'
					class	= 'input_MontantUsuel'
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->delai;} ?>' 	
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					size	= '3'
					maxlength='3' />
			</td>
			<td class='td_DetailG'>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_NonDeductible<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_NonDeductible<?PHP Echo $i; ?>'
					class	= 'input_MontantUsuel'
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->montantnondeductible;} ?>' 	
					onblur	= 'TRAVAUX_ReagirLigne(<?PHP Echo $i; ?>, "MND")'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					size	= '7'
					maxlength='7' />
			</td>
			<td>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_PartNonDeductible<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_PartNonDeductible<?PHP Echo $i; ?>'
					readonly= 'true'
					class	= 'input_MontantUsuel'
					value	= '<?PHP  if ($LB_XML){echo $LO_XML->simulation->travaux->detail[$i-1]->partnondeductible;} ?>' 	
					size	= '3'
					maxlength='3' />
			</td>
			<td class='td_DetailG'>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_Deductible<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_Deductible<?PHP Echo $i; ?>'
					class	= 'input_MontantUsuel'
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->montantdeductible;} ?>' 	
					onblur	= 'TRAVAUX_ReagirLigne(<?PHP Echo $i; ?>, "MD")'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					size	= '7'
					maxlength='7' />
			</td>
			<td>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_PartDeductible<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_PartDeductible<?PHP Echo $i; ?>'
					readonly= 'true'
					class	= 'input_MontantUsuel'					
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->partdeductible;} ?>' 	
					size	= '3'
					maxlength='3' />
			</td>
			<td class='td_DetailG'>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_Mobilier<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_Mobilier<?PHP Echo $i; ?>'
					class	= 'input_MontantUsuel'					
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->meuble;} ?>' 	
					onblur	= 'TRAVAUX_ReagirLigne(<?PHP Echo $i; ?>, "MOB")'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					size	= '7'
					maxlength='7' />
			</td>
			<td class='td_DetailGD'>
			<input type		= 'text'
					name	= 'ed_TRAVAUX_Global<?PHP Echo $i; ?>'
					id		= 'ed_TRAVAUX_Global<?PHP Echo $i; ?>'
					class	= 'input_MontantUsuel'	
					value	= '<?PHP  if ($LB_XMLS){echo $LO_XML->simulation->travaux->detail[$i-1]->total;} ?>' 						
					onblur	= 'TRAVAUX_ReagirLigne(<?PHP Echo $i; ?>, "T")'
					onkeypress='return TOOL_FiltreSaisie("ENTIER", event)'
					size	= '7'
					maxlength='7' />
			</td>
			</tr>
		<?PHP
			}
	?>
	<tr>
		<td class='td_DetailGB'></td>
		<td class='td_DetailGB'></td>
		<td class='td_DetailGB' colspan='2'></td>
		<td class='td_DetailGB' colspan='2'></td>
		<td class='td_DetailGB'></td>
		<td class='td_DetailGDB'></td>
	</tr>	
</table>
</div>
