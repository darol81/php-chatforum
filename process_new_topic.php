<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}
$user_id = $_SESSION["user_id"];
$topic = $_POST["topic"];
$message_text = $_POST["message_text"];

$mysql_conn = mydb_connect();                
/* Inserting into forum_topics table */
$mysql_conn -> Execute("INSERT INTO forum_topics (topic, creator_id) VALUES (@1@, %2%)", $topic, $user_id);
$forum_topic_id = $mysql_conn -> LastRowId();

/* Inserting into post table */
$mysql_conn -> Execute("INSERT INTO forum_posts (topic_id, creator_id, message, post_time) ".
                "VALUES (%1%, %2%, @3@, NOW())", $forum_topic_id, $user_id, $message_text);
mydb_close($mysql_conn);
$_SESSION["info_text"] = "Uusi viesti lähetetty.";
header("Location: forum_main.php");
?>


