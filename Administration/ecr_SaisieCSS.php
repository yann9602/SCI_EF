<Script Language="JavaScript">

</script>	

<br/>
<br/>
<input type='hidden' id='ed_Phase' name='ed_Phase' value='ADMIN' /> 

<div class='td_LibelleTitre'>
	Gestion en ligne de la feuille de style
</div>
<br/>
<br/>

<div class='DI3070_Gauche'>
	<br/>
	<br/>
	<input type='button'
			name='btn_ReInit'
			id='btn_ReInit'
			style='width:200px;' 
			value='Recharger depuis le site public' />
	<br/>
	<br/>
	<input type='button'
			name='btn_ReInit'
			id='btn_ReInit'
			style='width:200px;' 
			value='Recharger depuis le site de test' />
	<br/>
	<br/>
	<input type='button'
			name='btn_Valide'
			id='btn_Valide'
			style='width:200px;' 
			value='Publier sur le site de test' />			
</div>
<div class='DI3070_Droite' style='text-align:left;'>
	<textarea   rows='30'
				cols='60'
				name='ed_CSS'
				id='ed_css'><?PHP CSS_Charge();?></textarea>	
</div>



<?PHP
function CSS_Charge()
{
	$LN_Fh = fOpen ("SCI_EF_test.css", "r");
	Echo Stream_Get_Contents($LN_Fh);
	fClose ($LN_Fh);
}
?>