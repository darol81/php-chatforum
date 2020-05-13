<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check(1)== false) 
{
    header("Location: ./main_screen.php");
    return;
}
/* Infopalkin päivitys */
if(isset($_SESSION["info_text"]))
{
    $info_text = $_SESSION["info_text"];
    unset($_SESSION["info_text"]);
}
else
{
    $info_text = ""; 
}

$TBS = new clsTinyButStrong;
$TBS->LoadTemplate("templates/admin_create_login.html");
$TBS->Show();

?>