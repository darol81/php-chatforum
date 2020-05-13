<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}
$real_name = $_POST["real_name"];
$email = $_POST["email"];
$user_id = $_SESSION["user_id"];

$mysql_conn = mydb_connect();                
$mysql_conn -> Execute("UPDATE users SET real_name=@1@, email=@2@ WHERE id=%3%", $real_name, $email, $user_id);
mydb_close($mysql_conn);
$_SESSION["info_text"] = "Tiedot päivitetty.";
header("Location: profile.php");
?>
