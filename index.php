<?php
include_once("tbs_class.php");

if(isset($_GET["error"]))
{
    $login_msg = "Kirjautuminen ep�onnistui.";
}
else
{
    $login_msg = "";
}
$TBS = new clsTinyButStrong;
$TBS->LoadTemplate("templates/index.html");
$TBS->Show();
?>
