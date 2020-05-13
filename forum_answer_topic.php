<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}
if(!isset($_GET["id"]))
{
    header("Location: forum_main.php");
    return;
}
$topic_id = $_GET["id"];
$TBS = new clsTinyButStrong;
$TBS->LoadTemplate("templates/forum_answer_topic.html");
$TBS->Show();
?>
