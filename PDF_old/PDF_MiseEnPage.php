<?PHP
	Class LO_PageSyntheseType{
		var $LN_HautLigNormale = 6;
		var $LN_HautLigTitre = 12;
		var $LN_HautLigSTitre = 12;
		var $LN_HautLigRem = 3;
		var $LN_BlocColLib  = 5;
		var $LN_BlocColVal  = 60;
		var $LN_ColBilanAnnu  = 40;
		var $LC_Pyjama = "P";
		var $LN_LargeurPage = 210;
		var $LN_HauteurPage = 287;
	}
	Class LO_PageBilanType{
		var $LN_HautLigNormale = 6;
		var $LN_HautLigTitre = 12;
		var $LN_HautLigSTitre = 12;
		var $LN_HautLigRem = 3;
		var $LN_BlocColLib  = 5;
		var $LN_BlocColVal  = 60;
		var $LN_ColBilanAnnu  = 40;
		var $LC_Pyjama = "P";
		var $LN_LargeurPage = 287;
		var $LN_HauteurPage = 210;
	}
	Class LO_PageVerifType{
		var $LN_MargeGauche = 10;
		var $LN_HautLigNormale = 6;
		var $LN_HautLigTitre = 12;
		var $LN_HautLigSTitre = 12;
		var $LN_HautLigRem = 10;
		var $LN_BlocColLib  = 5;
		var $LN_BlocColVal  = 60;
		var $LN_ColBilanAnnu  = 40;
		var $LC_Pyjama = "P";
		var $LN_LargeurPage = 210;
		var $LN_HauteurPage = 287;
	}

Function PDF_Style($PC_Style){
Global $LO_PDF;
	//if ($PC_Style == "TITRE"){$LO_PDF->SetFont('Arial','B',16);}
	//if ($PC_Style == "SOUSTITRE"){$LO_PDF->SetFont('Arial','B',14);}
	//if ($PC_Style == "DETAIL"){$LO_PDF->SetFont('Arial','',12);}
	//if ($PC_Style == "REMARQUES"){$LO_PDF->SetFont('Arial','I',8);}

	SWITCH ($PC_Style){
		Case "TITRE":
			$LO_PDF->SetFont('Arial','B',16);
			Break;
		Case "SOUSTITRE" :
			$LO_PDF->SetFont('Arial','B',14);
			Break;
		Case "DETAIL" :
			$LO_PDF->SetFont('Arial','',10);
			Break;
		Case "REMARQUES" :
			$LO_PDF->SetFont('Arial','I',8);
			Break;
		Case "BILAN" :
			$LO_PDF->SetFont('Arial','B',12);
			Break;
		Case "GROS" :
			$LO_PDF->SetFont('Arial','',20);
			Break;

	}
}


Function PDF_AligneDroite($PC_Chaine){
Global $LO_PDF;
	$LC_Result = $PC_Chaine;
	// cadrer à droite
	While ($LO_PDF->GetStringWidth($LC_Result) < 15){$LC_Result = " " . $LC_Result;}
	Return $LC_Result;
}


Function PDF_AligneMontant($PC_Montant){
Global $LO_PDF;
	$LC_Result = $PC_Montant;
	// cadrer à 2 décimales
	$LC_Result = number_format((float)$LC_Result, 2, '.', '');
	// cadrer à droite
	While ($LO_PDF->GetStringWidth($LC_Result) < 15){$LC_Result = " " . $LC_Result;}
	Return $LC_Result;
}


Function PDF_Debug($PC_Test){
Global $LO_PDF;

	PDF_Style("TITRE");
	$LO_PDF->Text(5,  5, "Debug :" . $PC_Test);
}


Function PDF_CentreLibelle($PC_Libelle, $PC_Taille){
Global $LO_PDF;
	// centrer
	$LC_Result = "";
	$LN_Taille = ($PC_Taille - $LO_PDF->GetStringWidth($PC_Libelle));
	$LN_Taille = $LN_Taille / 2;
	While ($LO_PDF->GetStringWidth($LC_Result) < $LN_Taille){$LC_Result = " " . $LC_Result;}
	$LC_Result = $LC_Result . $PC_Libelle;
	Return $LC_Result;
}


Function PDF_Ruban($PN_Colonne, $PN_Ligne, $PN_Largeur, $PN_Hauteur){
Global $LO_PDF, $LO_Presentation;
	$LO_PDF->SetFillColor(75, 182, 254);
	$LO_PDF->Rect(	$PN_Colonne, 
					$PN_Ligne - $LO_Presentation->LN_HautLigNormale, 
					$PN_Largeur, 
					$PN_Hauteur, "F");
}


Function PDF_Pyjama($PN_Colonne, $PN_Ligne){
Global $LO_PDF, $LO_Presentation;

	if ($LO_Presentation->LC_Pyjama == "P")
	{
		$LO_PDF->SetFillColor(213, 237, 254);
		$LO_Presentation->LC_Pyjama = "I";
	}
	else
	{
		$LO_PDF->SetFillColor(167, 217, 254);
		$LO_Presentation->LC_Pyjama = "P";
	}
	$LO_PDF->Rect(	$PN_Colonne, 
					$PN_Ligne - $LO_Presentation->LN_HautLigNormale + 2, 
					$LO_Presentation->LN_LargeurPage, 
					$LO_Presentation->LN_HautLigNormale, "F");
	$LO_PDF->SetFillColor(255, 255, 255);
}

?>
