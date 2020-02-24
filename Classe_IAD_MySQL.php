<?PHP
// définition de la classe de gestion d'accès aux données

Class Classe_IAD{
	var $PC_Host;
	var $PC_User;
	var $PC_PW;
	var $PC_Base;
	var $PO_RS;
	var $PO_Conn;
	var $PN_ID;
	
	Function __construct(){
		// cette méthode sera exécutée lors de la création de la classe au moment de son instanciation
		// en PHP 4 elle portait le nom de la classe (Classe_IAD)
		$this->PC_Host = "localhost";
	    $this->PC_User = "root";
	    $this->PC_PW   = "";
	    $this->PC_Base = "sci_ef";
	    $this->PN_ID  = 0;
		If (1==1){		
			$this->PC_Host = "db714170336.db.1and1.com";
			$this->PC_User = "dbo714170336";
			$this->PC_PW   = "Michel35630";
			$this->PC_Base = "db714170336";
			$this->PO_ID   = 0;
		}
	}
	
	Function __destruc(){
		mysqli_close($this->PO_Conn);
	}
	
	Function ExecuteSQL($PC_SQL){
		// connecter à la base
		$this->PO_Conn = mysqli_connect($this->PC_Host, $this->PC_User, $this->PC_PW, $this->PC_Base);
		if (mysqli_connect_errno()) {
			$this->TraiteErreur("Echec de la connexion : %s\n", mysqli_connect_error());
		}
		mysqli_query($this->PO_Conn, "SET NAMES 'utf8' ");
		
		// exzcuter la syntaxe SQL
		If (StrPos("*" . StrToUpper($PC_SQL), "SELECT") == 1)
			{
				$this->PO_RS = mysqli_query($this->PO_Conn, $PC_SQL) or 
		               $this->TraiteErreur("Erreur syntaxe SQL : ", $PC_SQL);
			}
		else
			{
				$LO_Conn = $this->PO_Conn;
				$LO_Conn->Query($PC_SQL) or $this->TraiteErreur("Erreur insert SQL : ", $PC_SQL);
				// pour un insert récupérer l'ID
				If (Stristr(StrToUpper($PC_SQL), "INSERT")){
					$this->PN_ID = $LO_Conn->insert_id;
				}
				mysqli_close($this->PO_Conn);
			}
	}	
	
	Function EclateSQL($PC_Type){
		Switch ($PC_Type)
			{
				case "Noms":	
					$LT_Eclate = mysqli_fetch_array($this->PO_RS, MYSQLI_ASSOC);
					Break;
				case "Num":	
					$LT_Eclate = mysqli_fetch_array($this->PO_RS, MYSQLI_NUM);
					Break;
				case "StructureNom":	
					$LT_Eclate = Array();
					$LO_RS = $this->PO_RS;
					for ($i=0; $i<$LO_RS->field_count; $i++)
						{
							$LT_Tmp = mysqli_fetch_field_direct($this->PO_RS, $i);
							$LT_Eclate[$i] = $LT_Tmp->name;
						}
					Break;
				case "StructureType":	
					$LT_Eclate = Array();
					$LO_RS = $this->PO_RS;
					for ($i=0; $i<$LO_RS->field_count; $i++)
						{
							$LT_Tmp = mysqli_fetch_field_direct($this->PO_RS, $i);
							$LT_Eclate[$i] = $LT_Tmp->type;
						}
					Break;
			}
		If ($LT_Eclate == False){
				Die("Erreur grave rencontrée <br> ");
				MySQLI_Close($this->PO_Conn);
			}
		Return $LT_Eclate;
	}

	function NombreLignes(){
		$LO_RS = $this->PO_RS;
		return $LO_RS->num_rows;
	}
	
	function NombreChamps(){
		$LO_RS = $this->PO_RS;
		return $LO_RS->field_count;
	}

	Function RecupereID($PC_Table){
		Return $this->PN_ID;
	}

	Function PointeVisite($PC_Page){
		if (IsSet($_SESSION["VIS_ID"]))
			{
				$LC_SQL = "update sci_visites set ";
				$LC_SQL = $LC_SQL . "VIS_Pages=Concat(VIS_Pages, '-', '" . $PC_Page . "') ";
				$LC_SQL = $LC_SQL . "Where VIS_ID=" . $_SESSION["VIS_ID"];
				$this->ExecuteSQL($LC_SQL);
			}
		else
			{
				$LC_SQL = "insert into sci_visites ";
				$LC_SQL = $LC_SQL . " (VIS_IP, VIS_Date, VIS_Pages) ";
				$LC_SQL = $LC_SQL . " values (";
				$LC_SQL = $LC_SQL . "'" . $_SERVER['REMOTE_ADDR'] . "', ";
				$LC_SQL = $LC_SQL . "'" . Date("Ymd_His") . "', "; 
				$LC_SQL = $LC_SQL . "'" . $PC_Page . "') ";
				$this->ExecuteSQL($LC_SQL);
				$_SESSION["VIS_ID"] = $this->PN_ID;
			}	
	}
	
	Function ChargeCombobox($PC_SQL, $PC_Valeur){
		$this->ExecuteSQL($PC_SQL);

		// lire tous les enregistrements
		for ($i=0; $i < $this->PO_RS->num_rows; $i++){			
			$LT_Ligne = mysqli_fetch_array($this->PO_RS, MYSQLI_NUM);
			Echo "<option value = '" . $LT_Ligne[0] . "'";
			If ($LT_Ligne[0] == $PC_Valeur){
				Echo "Selected";
			}	
			Echo ">";
			Echo $LT_Ligne[1];
			Echo "</option> \n";
		}
		mysqli_close($this->PO_Conn);
	}

	Function ExportCSV($PC_SQL, $PC_NomFic){
		// exécuter la requete SQL
		$this->ExecuteSQL($PC_SQL);
		
		// créer le fichier résultat
		$LN_Fh = FOpen($PC_NomFic, "w+");
		
		// lire tous les enregistrements
		for ($i=0; $i < $this->PO_RS->num_rows; $i++){			
			$LT_Ligne = mysqli_fetch_array($this->PO_RS, MYSQLI_NUM);
			For ($j = 0; $j<Count($LT_Ligne); $j++){
				FPuts($LN_Fh, $LT_Ligne[$j]);	// ecrire la valeur du champ de la requete
				FPuts($LN_Fh, ";");				// écrire le caractère de séparation
			}
			FPuts($LN_Fh, "\n");			    // aller à la ligne
		}
		
		FClose($LN_Fh);
		MySQL_Close($this->PO_Conn);		
	}
	

	Function ExportHTML($PC_SQL){	
		$this->ExecuteSQL($PC_SQL);
	
		$LC_HTML="";
		
		$LT_Ligne = $this->EclateSQL("StructureNom");
		$LC_HTML = $LC_HTML . "<tr>";
				foreach ($LT_Ligne as $LO_Cell)
				//for ($j=0; $j<$this->NombreChamps(); $j++)
					{
						$LC_HTML = $LC_HTML . "<th>";
						$LC_HTML = $LC_HTML . $LO_Cell; // $LT_Ligne[$j];
						$LC_HTML = $LC_HTML . "</th>";
					}
		$LC_HTML = $LC_HTML . "</tr>";
		
		for ($i=0; $i<$this->NombreLignes(); $i++)
			{
				$LC_HTML = $LC_HTML . "<tr>";
				$LT_Ligne = $this->EclateSQL("Num");
				for ($j=0; $j<$this->NombreChamps(); $j++)
					{
						$LC_HTML = $LC_HTML . "<td>";
						$LC_HTML = $LC_HTML . $LT_Ligne[$j];
						$LC_HTML = $LC_HTML . "</td>";
					}
				$LC_HTML = $LC_HTML . "</tr>";
			}
		return $LC_HTML;
	}

	Function AfficheTexte($PC_Alias){
		$LC_SQL = "Select TEX_Texte from sci_textes where TEX_Alias='" . $PC_Alias . "' ";
		$this->ExecuteSQL($LC_SQL);
		$LT_Texte = $this->EclateSQL("Noms");
		return $LT_Texte["TEX_Texte"];
	}
	
	Function TraiteErreur($PC_Phase, $PC_SQL){
		Die("Erreur grave rencontrée <br> " . 
		    $PC_Phase .$PC_SQL);
	}
}
?>