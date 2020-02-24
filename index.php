<?PHP
Session_Start();
MB_Internal_Encoding("UTF-8");

// prise en compte du blocage du site
if (file_exists("SCI_EF_Bloque.txt"))
{
	if (IsSet($_SESSION["ADMIN_OK"]) == false)
	{
		header("Location: SCI_EF_Travaux.php");
		return;
	}
}

header("Location: Presentation/SCI_EF_indexPresentation.htm");
