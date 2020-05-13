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
$mysql_conn = mydb_connect();                
     
$data_array = $mysql_conn -> GetRow("SELECT user_name, real_name, email, admin FROM users WHERE id=%1%", $user_id);
mydb_close($mysql_conn);
if(!$data_array)
{
    // no such user
    return false;
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
$user_name = $data_array["user_name"];
$email = $data_array["email"];
$real_name = $data_array["real_name"];
$admin = $data_array["admin"];

$TBS = new clsTinyButStrong;
if($admin < 1)
{
    $TBS->LoadTemplate("templates/profile.html");
}
else
{
    $TBS->LoadTemplate("templates/admin_profile.html");
}
$TBS->Show();

?>
