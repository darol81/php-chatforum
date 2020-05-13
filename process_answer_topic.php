<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}
if(!isset($_POST["topic_id"]))
{
    header("Location: forum_main.php");
    return;
}
$topic_id = $_POST["topic_id"];
$user_id = $_SESSION["user_id"];
$message_text = $_POST["message_text"];

$mysql_conn = mydb_connect();                
/* Testing that there's a topic with this id. */
$data_array = $mysql_conn -> GetRow("SELECT forum_topics.id FROM forum_topics WHERE forum_topics.id=%1%", $topic_id);
if(!$data_array)
{
    mydb_close($mysql_conn);
    header("Location: forum_main.php");
    return;
}
/* Inserting into post table */
$mysql_conn -> Execute("INSERT INTO forum_posts (topic_id, creator_id, message, post_time) ".
                "VALUES (%1%, %2%, @3@, NOW())", $topic_id, $user_id, $message_text);
mydb_close($mysql_conn);
$_SESSION["info_text"] = "Vastaus aktiiviseen viestiketjuun lähetetty.";
header("Location: forum_main.php");
?>



