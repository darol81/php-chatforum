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
$topics_per_page = 10;
$limit_num = (($page - 1) * $topics_per_page);

$pagedata = $mysql_conn -> GetRow("SELECT COUNT(forum_topics.id) AS count FROM forum_topics");
$pagecount = (int)(($pagedata["count"] - 1) / $topics_per_page) + 1;
$data = $mysql_conn -> GetRows("SELECT forum_topics.id as topic_id, users.real_name as real_name, forum_topics.topic as topic, ".
                       "(SELECT count( forum_posts.id ) FROM forum_posts WHERE ".
                       "forum_posts.topic_id = forum_topics.id) AS count FROM ".
                       "forum_topics, users WHERE users.id = forum_topics.creator_id ".
                       "ORDER BY forum_topics.id DESC LIMIT %1%,%2%", $limit_num, $topics_per_page);
// limit <mistä>,<montako>              
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
$TBS->LoadTemplate("templates/forum_main.html");
$TBS->MergeBlock('data',$data);
$TBS->MergeBlock('pagedata', 'num', $pagecount);
$TBS->Show();
?>
  