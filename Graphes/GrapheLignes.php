<?php

Class LO_ClasseLigne{
	Public $LC_Valeur;
	Public $LC_Date;
	function __construct($PN_Valeur, $PC_Date)
		{
			$this->LN_Valeur = $PN_Valeur; 
			$this->LC_Date   = $PC_Date; 
		}
}
	
	
function TraceGrapheLigne(	$LT_Elements, 
							$LN_Largeur,
							$LN_Hauteur,
							$PC_Image,
							$PN_EpaisseurTraitGraphe,
							$PN_EpaisseurTraitRepere,
							$PN_TaillePolice)
{
	$LO_Graphe=imagecreatetruecolor($LN_Largeur, $LN_Hauteur); 
	$LO_Blanc =imagecolorallocate($LO_Graphe,255,255,255);
	$LO_Noir  =imagecolorallocate($LO_Graphe,0,0,0);
	$LO_Rouge =imagecolorallocate($LO_Graphe,255,0,0);
	$LO_Vert  =imagecolorallocate($LO_Graphe,0, 255,0);

	// peindre en blanc
	ImageFill($LO_Graphe,1,1,$LO_Blanc);

	//Recherche de la valeur maxi
	$LN_Maxi=0;
	$LN_Mini=0;
	for($i=0;$i<Count($LT_Elements);$i++)
		{
			$LN_Maxi = max($LN_Maxi, $LT_Elements[$i]->LN_Valeur); 
			$LN_Mini = min($LN_Mini, $LT_Elements[$i]->LN_Valeur); 
		}

	//coefficient d'échelle
	$LN_Marge     = 20;
	$LN_Amplitude = abs($LN_Maxi) + abs($LN_Mini);
	$LN_Coeff     = 1;
	if ($LN_Amplitude != 0){$LN_Coeff=(($LN_Hauteur-($LN_Marge*2))*1)/ $LN_Amplitude;} 
	$YO = $LN_Hauteur-$LN_Marge;
	$LN_Pas = $LN_Largeur / (Count($LT_Elements)-1); 
	
	// ligne du 0
	$LN_Zero = $YO - ($LN_Coeff * abs($LN_Mini));
	imagesetthickness($LO_Graphe, $PN_EpaisseurTraitRepere);
	imageline ($LO_Graphe,  1,
							$YO - ($LN_Coeff * abs($LN_Mini)),
							1 + $LN_Largeur,
							$YO - ($LN_Coeff * abs($LN_Mini)),
							$LO_Noir);

	//application de l'amplitude graphique
	for($i=0;$i<Count($LT_Elements);$i++) 
		{
			$LN_Valeur   = abs($LN_Mini) + $LT_Elements[$i]->LN_Valeur;
			$LT_TabX[$i] = $LN_Pas*$i;
			$LT_TabY[$i] = $YO - ($LN_Coeff * $LN_Valeur);
		}
		
	//tracé de la ligne
	$LO_CouleurPrec = 0;
	$LO_Font = "calligra.ttf";
	for($i=1;$i<Count($LT_Elements);$i++) 
	{
		$LO_Couleur = 0;
		if ($LT_TabY[$i]<$LN_Zero){$LO_Couleur = $LO_Vert;}
		if ($LT_TabY[$i]>$LN_Zero){$LO_Couleur = $LO_Rouge;}
		if ($LO_CouleurPrec <> $LO_Couleur)
		{
			// on change de couleur on marque
			if ($LO_CouleurPrec <> 0)
			{
				imagesetthickness($LO_Graphe, $PN_EpaisseurTraitRepere);
				imageline ($LO_Graphe,
							$LT_TabX[$i-1],
							$YO,
							$LT_TabX[$i-1],
							$YO - ($LN_Coeff * $LN_Amplitude),
							$LO_Noir);
				imagettftext ($LO_Graphe,
							  $PN_TaillePolice, 
							  1,
							  $LT_TabX[$i-1], 
							 $YO - ($LN_Coeff * $LN_Amplitude) + 50,  
							 $LO_Noir,
							 $LO_Font,
							 $LT_Elements[$i]->LC_Date);
			}
			$LO_CouleurPrec = $LO_Couleur;

		}
		imagesetthickness($LO_Graphe, $PN_EpaisseurTraitGraphe);
		imageline ($LO_Graphe,$LT_TabX[$i-1],
							  $LT_TabY[$i-1],
							  $LT_TabX[$i],
							  $LT_TabY[$i],
							  $LO_Couleur);
	}

	//enregistre l'image
	imagejpeg($LO_Graphe, "" . $PC_Image);
}
	

?>