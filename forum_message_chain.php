<?php

require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}
$mysql_conn = mydb_connect();                

if(isset($_GET["page"])) 
{
    $page = $_GET["page"];
}
else
{
    $page = 1; 
}
if(!isset($_GET["id"]))
{
    header("Location: forum_main.php");
    return;
}
$topic_id = $_GET["id"];
$messages_per_page = 10;
$limit_num = (($page - 1) * $messages_per_page);

$pagedata = $mysql_conn -> GetRow("SELECT COUNT(forum_posts.id) AS count FROM forum_posts WHERE ".
                           "forum_posts.topic_id = %1%", $topic_id);
$pagecount = (int)(($pagedata["count"] - 1) / $messages_per_page) + 1;
$data = $mysql_conn -> GetRows("SELECT users.real_name as real_name, forum_posts.post_time as post_time, ".
                        "forum_posts.message as message ".
                        "FROM forum_posts, users WHERE ".
                        "forum_posts.topic_id = %1% AND forum_posts.creator_id = ".
                        "users.id ORDER BY ".
                        "forum_posts.id ASC LIMIT %2%,%3%", 
                        $topic_id, $limit_num, $messages_per_page);
mydb_close($mysql_conn);
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
$TBS->LoadTemplate("templates/forum_message_chain.html");
$TBS->MergeBlock('data',$data);
$TBS->MergeBlock('pagedata', 'num', $pagecount);
$TBS->Show();
?>
  