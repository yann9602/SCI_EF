<?PHP
Session_Start();

	$LC_Alias = Date("His");
	$LB_OK    = true;
	if (!copy("../Data/XML/DEMO_INV.xml", "../Data/Demonstrations/INV_" . $LC_Alias . ".xml")) 
	{
		$LB_OK = false;
	}
	if (!copy("../Data/XML/DEMO_SIM.xml", "../Data/Demonstrations/SIM_" . $LC_Alias . ".xml")) 
	{
		$LB_OK = false;
	}
	
	// simulation
	$_SESSION["SCI_CTX"]   ="DEMO";
	$_SESSION["SCI_ALIAS"] = $LC_Alias;
	$_SESSION["USER_STATUT"] = "X"; 
	Unset($_SESSION["USER_ID"]);
	header("Location: ../SCI_EF_index.php?page=simulation");
	return;

?>
