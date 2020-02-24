<?php

Class LO_ClasseHisto{
	Public $LC_Valeur;
	Public $LC_Libelle;
	Public $LC_Couleur;	
	function __construct($PN_Valeur, $PC_Couleur, $PC_Libelle)
		{
			$this->LN_Valeur	= $PN_Valeur; 
			$this->LC_Couleur	= $PC_Couleur;
			$this->LC_Libelle	= $PC_Libelle;
		}
}
	
	
function TraceGrapheHisto($LT_Elements, $LN_Largeur, $LN_Hauteur, $PC_Image)	
{

	$LO_Graphe=imagecreatetruecolor($LN_Largeur, $LN_Hauteur); 
	$LO_Blanc =imagecolorallocate($LO_Graphe,255,255,255);
	$LO_Noir  =imagecolorallocate($LO_Graphe,0,0,0);
	$LN_Marge = 20;

	// peindre en blanc
	ImageFill($LO_Graphe,1,1,$LO_Blanc);

	//Recherche de la valeur maxi
	$LN_Maxi=0;
	for($i=0;$i<Count($LT_Elements);$i++)
		{
			$LN_Maxi = Max($LN_Maxi,$LT_Elements[$i]->LN_Valeur); 
		}

	//coefficient d'échelle
	$LN_Coeff = 1;
	if ($LN_Maxi != 0){$LN_Coeff=(($LN_Hauteur-($LN_Marge*2))*1)/ $LN_Maxi;} 
	$XO = $LN_Marge;
	$YO = $LN_Hauteur-$LN_Marge;
	$LN_HistoEcart = $LN_Largeur / $LN_Marge;
	$LN_HistoLarge = ($LN_Largeur - 100) / Count($LT_Elements); 
	$LN_HistoLarge = $LN_HistoLarge - $LN_HistoEcart;

	//coordonnées des sommets des rectangles
	for($i=0;$i<Count($LT_Elements);$i++) 
		{
			$LT_TabX[$i] = $XO + ($LN_HistoLarge*$i) + ($LN_HistoEcart*$i);
			$LT_TabY[$i] = $YO - ($LN_Coeff * $LT_Elements[$i]->LN_Valeur);
		}
	//tracé des rectangles
	for($i=0;$i<Count($LT_Elements);$i++) 
		{
			$LT_Couleur = explode(",", $LT_Elements[$i]->LC_Couleur);
			$LO_Couleur = imageColorAllocate($LO_Graphe,$LT_Couleur[0], 
													   $LT_Couleur[1],
													   $LT_Couleur[2]);
			//tracés des rectangles en noir
			ImageRectangle($LO_Graphe,$LT_TabX[$i] + $LN_HistoEcart,
									  $LT_TabY[$i],
									  $LT_TabX[$i] + $LN_HistoLarge,
									  $YO,
									  $LO_Noir);
			imageFilledRectangle($LO_Graphe,$LT_TabX[$i] + $LN_HistoEcart + 1,
									  $LT_TabY[$i] + 1,
									  $LT_TabX[$i] + $LN_HistoLarge - 2,
									  $YO - 2,
									  $LO_Couleur);
		}

	// tracé des legendes
	$y=20;
	$x=$LN_Largeur - 70;
	$x_ecrit=$LN_Largeur - 50;
	$police=2;
	
	for ($i=0;$i<Count($LT_Elements);$i++)
		{
			$y=$y+imageFontHeight($police)+10;
			$LT_Couleur = explode(",", $LT_Elements[$i]->LC_Couleur);
			$LO_Couleur = imageColorAllocate($LO_Graphe,$LT_Couleur[0], 
														$LT_Couleur[1],
														$LT_Couleur[2]);
			ImageRectangle($LO_Graphe,$x,$y,
							$x+imageFontHeight($police),
							$y+imageFontHeight($police),
							$LO_Noir);
			imageFilledRectangle($LO_Graphe,$x+1,$y+1,
							$x+imageFontHeight($police)-1,
							$y+imageFontHeight($police)-1,
							$LO_Couleur);
			imagestring($LO_Graphe,$police,	$x_ecrit,$y,
							$LT_Elements[$i]->LC_Libelle,$LO_Noir);
		}		
		
		//enregistre l'image
		imagejpeg($LO_Graphe, "Ajax/" . $PC_Image);
}
	

?>