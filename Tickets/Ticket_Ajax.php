<?PHP
header("Content-Type:text/xml;charset:utf-8");
	
require('../SCI_EF.inc');
require('../Tickets/TRT_Tickets.php');
require('../Classe_IAD_MySQL.php');

// traitement des tickets
$LO_IAD  = New Classe_IAD();
DecompteTicket("PDFBILANA", TOOL_SessionLire("GET", "id"), TOOL_SessionLire("GET", "logid"));

	$LC_XML  = "";
	$LC_XML .= "<?xml version=\"1.0\"?> \n";
	$LC_XML .= "<data> \n";
	$LC_XML .= "<status> \n";	
	$LC_XML .= "<valeur>" . "OK" . "</valeur> \n";
	$LC_XML .= "</status> \n";	
	$LC_XML .= "</data> \n";
	echo $LC_XML;

?>