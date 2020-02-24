<?php

// objet
Class LO_ClasseCamembert{
	Public $LN_Valeur;
	Public $LC_Couleur;	
	Public $LC_Libelle;
	function __construct($PN_Serie, $PN_Valeur, $PC_Couleur, $PC_Libelle)
		{ 
			$this->LN_Valeur	= $PN_Valeur; 
			$this->LC_Couleur	= $PC_Couleur;
			$this->LC_Libelle	= $PC_Libelle; 
			$this->LN_Green		= Substr($PC_Couleur, 4, 3);
			$this->LN_Red		= Substr($PC_Couleur, 0, 3);
			$this->LN_Blue		= Substr($PC_Couleur, 8, 3);		
			$this->LN_OmbreGreen= 0;
			$this->LN_OmbreRed	= 0;
			$this->LN_OmbreBlue	= 0;
			if ($this->LN_Green > 15){$this->LN_OmbreGreen= $this->LN_Green-15;}
			if ($this->LN_Red > 15)  {$this->LN_OmbreRed  = $this->LN_Red-15;}
			if ($this->LN_Blue > 15) {$this->LN_OmbreBlue = $this->LN_Blue-15;}
		} 		
}


function TraceGrapheCamembert($LT_Elements, $LN_Largeur, $LN_Hauteur, $PC_Image)
{

	$LO_Graphe= imagecreatetruecolor($LN_Largeur, $LN_Hauteur);
	$fond     = imagecolorallocate($LO_Graphe, 240, 240, 240);
	imagefilledrectangle($LO_Graphe, 0 , 0, $LN_Largeur, $LN_Hauteur, $fond);

	// calculer le total
	$LN_Total = 0;
	for ($i=0; $i<Count($LT_Elements); $i++)
		{
			$LN_Total += $LT_Elements[$i]->LN_Valeur;
		}

	//Creation de l'effet 3D
	//Dessiner des arc remplis de 20px d'épeseur 
	$LN_CentreY = $LN_Hauteur / 2;
	for ($i = $LN_CentreY; $i > $LN_CentreY - 20; $i--)
		{
			//Angle de début pour le premier produit
			$LN_Debut=0;
			for ($j=0; $j<Count($LT_Elements); $j++)
				{
					//Calcul de l'angle correspondant à la quantité de produit vendu
					$LN_Valeur=$LT_Elements[$j]->LN_Valeur/$LN_Total*360;

					//Calcul de l'angle de la fin pour le produit
					$LN_Fin=$LN_Debut + $LN_Valeur;
					//Dessiner l'arc
					$LO_Couleur = imagecolorallocate($LO_Graphe,
													$LT_Elements[$j]->LN_OmbreRed,
													$LT_Elements[$j]->LN_OmbreGreen,
													$LT_Elements[$j]->LN_OmbreBlue);													
					imagefilledarc($LO_Graphe,
									$LN_Largeur / 5 * 3,
									$i,
									$LN_Hauteur,
									120,
									$LN_Debut,
									$LN_Fin,
									$LO_Couleur, IMG_ARC_PIE);
					//L'angle de début pour le produit suivant
					$LN_Debut=$LN_Fin;
				}
		}

	// mémoriser la dernière valeur de i
	$LN_CentreY = $i;

	//Dessiner les arcs claires
	$LN_EcartX = 10;
	$LN_EcartY = 120;

	for ($i=0; $i<Count($LT_Elements); $i++)
		{
			$LN_Valeur=$LT_Elements[$i]->LN_Valeur / $LN_Total * 360;
			$LN_Fin=$LN_Debut+$LN_Valeur;
			$LO_Couleur = imagecolorallocate($LO_Graphe,
											$LT_Elements[$i]->LN_Red,
											$LT_Elements[$i]->LN_Green,
											$LT_Elements[$i]->LN_Blue);
			imagefilledarc($LO_Graphe,
							$LN_Largeur / 5 * 3,
							$LN_CentreY,
							$LN_Hauteur,
							120,
							$LN_Debut,
							$LN_Fin,
							$LO_Couleur, IMG_ARC_PIE);
			$LN_Debut=$LN_Fin;
			//Légende
			//Mettons 4 produits par colonne
			if(($i % 5)==4)
				{
					$LN_EcartX += 150;
					$LN_EcartY  = 270;
				}
			//Le nom du produit et la quatité vendue avec la couleur qui lui est attribué
			$LC_Libelle = $LT_Elements[$i]->LC_Libelle; // . " " . $LT_Elements[$i]->LN_Valeur;
			$LO_Noir    = imagecolorallocate($LO_Graphe, 0, 0, 0);
			imagestring($LO_Graphe, 2, $LN_EcartX, $LN_EcartY, $LC_Libelle, $LO_Noir);
			//Le petit rectangle qui designe la couleur
			imagefilledrectangle($LO_Graphe,$LN_EcartX+65 , $LN_EcartY+2, $LN_EcartX+80, $LN_EcartY+9, $LO_Couleur);
			$LN_EcartY+=12;
		}
		
	imagejpeg($LO_Graphe, "Ajax/" . $PC_Image);
	//imagedestroy($LO_Graphe);
}



?>
