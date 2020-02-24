<?php


Class LO_ClasseHistoEmpiles{
	Public $LC_Valeur;
	Public $LC_Libelle;
	Public $LC_Couleur;	
	Public $LN_Serie;
	function __construct($PN_Serie, $PN_Valeur, $PC_Couleur, $PC_Libelle)
		{
			$this->LN_Valeur	= $PN_Valeur; 
			$this->LC_Couleur	= $PC_Couleur;
			$this->LC_Libelle	= $PC_Libelle;
			$this->LN_Serie		= $PN_Serie;	
		}
}


function TraceGrapheHistoEmpiles($LT_Elements, $LN_Largeur, $LN_Hauteur, $PC_Image)
	{
	// nombre de série
	$LN_NbSerie = 0;
	for ($i=0; $i<Count($LT_Elements);$i++)
		{
			if ($LT_Elements[$i]->LN_Serie > $LN_NbSerie)
				{
					$LN_NbSerie = $LT_Elements[$i]->LN_Serie;
					$LN_Serie   = $LT_Elements[$i]->LN_Serie;
					$LT_BarreValeur[$LN_Serie]=0;
				}
				$LN_Serie   = $LT_Elements[$i]->LN_Serie;
				$LT_BarreValeur[$LN_Serie]=$LT_BarreValeur[$LN_Serie] + $LT_Elements[$i]->LN_Valeur;
		}

	$espace		= 10; //espace entre les barres
	$large		= (int)((($LN_Largeur / 2) / $LN_NbSerie) - ($espace * $LN_NbSerie)); //largeur d'une barre
	$police		= 2; //police d'écriture des intitulés de segments
	$police_pc	= 1;   //police d'écriture des pourcentages 
	$LO_Graphe	= imageCreate($LN_Largeur, $LN_Hauteur);
	$fond		= imageColorAllocate($LO_Graphe, 240, 240, 240);
	$noir		= imageColorAllocate($LO_Graphe, 0, 0, 0);
	
	imagefill($LO_Graphe,0,0,$fond);

	// calculs de cadrage
	$max	=(int)($LN_Hauteur - ($LN_Hauteur * 10 / 100));	// hauteur des barres
	$x2		=$espace * 2;										  	// position du graphique par rapport au côté gauche de l'image
	$x1		=0;
	$y_haut	=(int)($LN_Hauteur * 10 / 100);					// position du graphique par rapport au haut de l'image
	$y2		=$max+$y_haut;

	for ($i=1;$i<=$LN_NbSerie;$i++)
		{
		$x1=$x2;
		$x2=$x1+$large;
		$y1=$y_haut; 		
		for ($j=0;$j<Count($LT_Elements);$j++)
			{
				if ($i == $LT_Elements[$j]->LN_Serie)
					{
						$LT_Couleur = explode(",", $LT_Elements[$j]->LC_Couleur);
						$LO_Couleur = imageColorAllocate($LO_Graphe,$LT_Couleur[0], 
																	$LT_Couleur[1],
																	$LT_Couleur[2]);
						$h = 0;
						if ($LT_BarreValeur[$i] != 0)
							{
								$h = ($LT_Elements[$j]->LN_Valeur/$LT_BarreValeur[$i])*100;
							}	
						$h = round(($max*$h)/100);  // hauteur de chaque segment
						$y2=$y1+$h;
						if ($y2>$max+$y_haut)
							$y2=$max+$y_haut;
						imagerectangle($LO_Graphe,$x1+$espace,$y1,$x2,$y2,$noir);
						imageFilledRectangle($LO_Graphe,$x1+$espace+1,$y1+1,$x2-1,$y2-1,$LO_Couleur);
						$y1=$y2;
					}
			}		
		}
	$y=20;
	$x=$x2+50;
	$x_ecrit=$x2+70;
	for ($i=0;$i<Count($LT_Elements);$i++)
		{
			$y=$y+imageFontHeight($police)+10;
			$LT_Couleur = explode(",", $LT_Elements[$i]->LC_Couleur);
			$LO_Couleur = imageColorAllocate($LO_Graphe,$LT_Couleur[0], 
														$LT_Couleur[1],
														$LT_Couleur[2]);
			imagerectangle($LO_Graphe,$x,$y,$x+imageFontHeight($police),$y+imageFontHeight($police),$noir);
			imageFilledRectangle($LO_Graphe,$x+1,$y+1,$x+imageFontHeight($police)-1,$y+imageFontHeight($police)-1,$LO_Couleur);
			imagestring($LO_Graphe,$police,$x_ecrit,$y,$LT_Elements[$i]->LC_Libelle,$noir);
		}

	imagejpeg($LO_Graphe, "Ajax/" . $PC_Image);
	}
	

?>