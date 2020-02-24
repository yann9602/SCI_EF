<?PHP

Class ClasseCalculs{
	Public $LN_Date;				//Date
	Public $LN_Mois;				//Mois
	Public $LN_Loyer;				//Loyer
	Public $LN_LoyersPercu;			//perçu
	Public $LN_LoyerAnnuel;			//perçu annuel
	Public $LN_LoyersImposable;
	Public $LN_CRD;	//Capital restant du
	Public $LN_InteretEmprunt;			//Intéret
	Public $LN_InteretsAnnuels;		//Intéret annuel
	Public $LN_Mensualite;			//Mensualité
	Public $LN_MensualiteAnnuelle;	//Mensualité annuelle
	Public $LN_MensualiteADI;
	Public $LN_AssurancePret;		//Assurance prêt
	Public $LN_AssurantePretAnnuelle;//Assurance prêt annuelle
	Public $LN_AssurancePNO;		//Assurance PNO
	Public $LN_EvolPNO;
	Public $LN_AssurancePNUAnnuelle;// PNO annuelle
	Public $LN_AssuranceGRL;		// GRL
	Public $LN_AssuranceGRLAnnuelle;// GRL annuelle
	Public $LN_AssuranceCopro;		// copro
	Public $LN_AssuranceCoproAnnuelle;	// copro annuelle
	Public $LN_ChargesCopro;		// copro
	Public $LN_ChargesCoproAnnuelle;// copro annuelle
	Public $LN_ChargesIndividuel;
	Public $LN_AutresCharges;		// autres charges
	Public $LN_ChargesSCI;
	Public $LN_AutresRevenus;
	Public $LN_AutresChargesAnnuelles;	// annuelles
	Public $LN_FraisAgence;			// d'agence
	Public $LN_FraisAgenceAnnuel;	//
	Public $LN_TaxeFonciere;		// foncière
	Public $LN_TaxeFonciereAnnuelle;//
	Public $LN_TravauxRenovation;		
	Public $LN_TravauxRenovationAnnuel;
	Public $LN_TravauxEntretien;
	Public $LN_TravauxEntretienAnnuel;
	Public $LN_Tresorerie;
	Public $LN_TresorerieCumul;
	Public $LN_DureeRestanteAnticipeIndiv;
	Public $LN_DateRemAnticipeIndiv;
	Public $LN_DateRemAnticipeIndivCalculee;

	Public $LN_Annee;
	Public $LN_NbPartsFiscales;
	Public $LN_MontantImposableIndiv;
	Public $LN_TotalImposable;
	Public $LC_RegimeFiscIndiv;
	Public $LN_TxImpots;
	Public $LN_MontantImpots;
	Public $LN_MontantCSG;
	Public $LN_MicroFoncDeduc;
	Public $LN_MensualiteEmprunt;
	Public $LN_ChargesDeductiblesCommunes;
	
	Public $LN_MontantImposableSCI;
	Public $LN_ImpotsSCI;
	Public $LN_TresorerieSCI;

	Public $LN_DividendeVerse;
	Public $LN_TxImpotsAssocie;
	Public $LN_ImpotsAssocie;
	Public $LN_CSGAssocie;
	Public $LN_DividendeNetAssocie;

	Public $LN_ChargesLMNP;
	Public $LN_AmortiBati;
	Public $LN_AmortiMeubles;
	Public $LN_TravauxRenovDeduc;
	Public $LN_TravauxRenovNonDeduc;
	Public $LN_TresorerieSCIRestante;
	Public $LN_TresorerieSCIRestanteCumul;
	Public $LN_DureeRestanteAnticipeSCI;
	Public $LN_DateRemAnticipeSCI;
	Public $LN_DateRemAnticipeSCICalculee;



	function __construct($PN_Date, $PN_Mois)
		{ 
			$this->LN_Date	= $PN_Date; 
			$this->LN_Mois	= $PN_Mois;
		} 		
		
	function CALCUL_Loyer(){
		$LN_Result = 0;
	}
}

?>