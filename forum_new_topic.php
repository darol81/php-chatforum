<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}

$TBS = new clsTinyButStrong;
$TBS->LoadTemplate("templates/forum_new_topic.html");
$TBS->Show();
?>
