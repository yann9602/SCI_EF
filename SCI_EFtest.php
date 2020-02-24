<?PHP
Session_Start();
require('SCI_EF.inc');
require('Classe_IAD_MySQL.php');
header('Content-type:text/html; charset=UTF-8');
//$LO_IAD  = New Classe_IAD();
echo $_SESSION["test"];
//header("Location: Test.php");
//echo 222;

?>