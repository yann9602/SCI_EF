<Script Language="JavaScript">
	function ACHAT_Reagir(PC_Nombre, PC_Prix)
	{
		document.getElementById("ed_Nombre").value   = PC_Nombre;
		document.getElementById("ed_Paiement").value = PC_Prix;
		document.getElementById("id_Libelle").value  = "Achat tickets ";
		document.getElementById("id_Montant").value  = PC_Prix;
	}
	function ACHAT_Valide()
	{
		if (document.getElementById("ed_Paiement").value != "")
		{
			document.fo_SCIEF.action = "https://www.paypal.com/cgi-bin/webscr"; 
			document.getElementById("return").value = 
			document.getElementById("return").value + 
			document.getElementById("ed_Nombre").value;
			document.fo_SCIEF.submit();
		}
		else
		{
			alert("Il faut définir la formule à acheter");
		}
	}
</Script>
<br/>
<div style='float:left;width:100%;' 
	 class	= 'td_LibelleTitre'>
	Achat de tickets	
</div>

<br/>
<br/>
<br/>
<table align='center'
		class='table_Page'
		style='	border-color :#DD0000;
				border-width	:2px;
				border-collapse :collapse;
				border-color	:#FF0000;
				border-style	:solid;'>

  <tr>
	<th width='30%'></th>
    <th width='15%'>Nombre de tickets</th>
    <th width='15%'>Prix</th>
    <th width='15%'>Prix unitaire</th>
    <th width='15%'>Réduction</th>
	<th width='10%'></th>
  </tr>

  <tr>
	<td>Formule Découverte</td>
    <td align='center'>10</td>
    <td align='center'>2,00€</td>
    <td align='center'>0,20€</td>
    <td align='center'>0%</td>
	<td align='center'><input type='radio' name='rd_Formule' value='D' onclick='ACHAT_Reagir("10", "2.00")' /></td>
  </tr>

  <tr>
  	<td>Formule Projet</td>
    <td align='center'>20</td>
    <td align='center'>3,50€</td>
    <td align='center'>0,175€</td>
    <td align='center'>-13%</td>
	<td align='center'><input type='radio' name='rd_Formule' value='J' onclick='ACHAT_Reagir("20", "3.50")' /></td>
  </tr>

  <tr>
  	<td>Formule Investisseur</td>
    <td align='center'>50</td>
    <td align='center'>8,00€</td>
    <td align='center'>0,16€</td>
    <td align='center'>-20%</td>
	<td align='center'><input type='radio' name='rd_Formule' value='I' onclick='ACHAT_Reagir("50", "8.00")' /></td>
  </tr>

  <tr>
  	<td>Formule Habitué</td>
    <td align='center'>100</td>
    <td align='center'>15,00€</td>
    <td align='center'>0,15€</td>
    <td align='center'>-25%</td>
	<td align='center'><input type='radio' name='rd_Formule' value='H' onclick='ACHAT_Reagir("100", "15.00")' /></td>
  </tr>
  
  <tr>
  	<td>Formule Professionnel</td>
    <td align='center'>200</td>
    <td align='center'>25,00€</td>
    <td align='center'>0,125€</td>
    <td align='center'>-38%</td>
	<td align='center'><input type='radio' name='rd_Formule' value='P' onclick='ACHAT_Reagir("25.00")' /></td>
  </tr>
</table>

<br/>
<table align='center'
		class='table_Page'>
	<tr>
		<td>
			Somme à payer 
		</td>
		<td>
			<input type='text'
					name='ed_Paiement'
					id='ed_Paiement' />
			<input type='hidden'
					name='ed_Nombre'
					id='ed_Nombre' />
		</td>
		<td>
			<input type = 'hidden' name = 'cmd' value='_xclick'>
			<input type = 'hidden' name = 'business' value='<?PHP Echo Def_PaypalCompte; ?>'>
			<input type = 'hidden' name = 'item_name' id = "id_Libelle" value='Achat de tickets'>
			<Input Type = "hidden" name = "item_number" value="1">
			<Input Type = "hidden" name = "amount" id="id_Montant" value="0.00">
			<input type = 'hidden' name = 'currency_code' value='EUR'>
			<input type = 'hidden' name = 'no_shipping' value='1'>
			<Input Type = "hidden" name = "return" id="return" value="<?PHP echo Def_PaypalURL; ?>/SCI_EF.php?id=<?PHP echo $_SESSION["USER_ID"];?>&achattickets=">
			<Input Type = "hidden" name = "cancel_return" value="<?PHP echo Def_PaypalURL; ?>/SCI_EF_index.php?page=achattickets">
			<input type = 'hidden' name = 'no_note' value='1'>
			<input type = 'hidden' name = 'email' value=''>
			<a href="javascript:ACHAT_Valide()">
				<img src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_buynowCC_LG.gif" />
			</a>
		</td>
	</tr>
</table>

<br/>
<br/>
<a href="SCI_EF_index.php?page=PDF_Accueil&onglet=SAISIE_Financement">
Retour à la page de résultats
</a>
