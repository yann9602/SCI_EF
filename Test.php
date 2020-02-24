<?PHP
if( empty(session_id()) ){ session_start(); }
header('Content-type:text/html; charset=UTF-8');
error_reporting(E_ALL); 	// en TEST !!
$_SESSION["test"] = "xxxxxxxx";
header("Location: SCI_EFtest.php");
?>