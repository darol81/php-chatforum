<?php

require_once("functions/database.php"); 
  
/* Called on top of each php page which accesses php variables. */
  
function sec_session_start() 
{
    $session_name = 'chatforum_session_id'; // Set a custom session name
    $secure = false; // Set to true if using https.
    $httponly = true; // This stops javascript being able to access the session id. 

    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
    session_name($session_name); // Sets the session name to the one set above.
    session_start(); // Start the php session
    session_regenerate_id(true); // regenerated the session, delete the old one.     
}  
  
function debug_info()
{
    $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    $password = hash('sha512', "<password>".$random_salt);
    return "pass: ". $password ."<br>salt: ". $random_salt;
}

// Data examples

//pass: 2527f6973710ca64441ce41643f36215c433394a3897515c9ca4202c5e64fa64f752a0a7f8183d9560addc7b44faac82b6e9c23999c12808f6b8fc081af85638
//salt: 40da3d4b38b846d3cc4fa08f24404a566c7bba770174bc4f22e8fdaa94454fc9a574f5cd15bcfed9cf0f3e434fe3af6ec084d7653402ff41fc41fd7b598f3881

/* This function is used when the user is logging in from the login screen. It is used to */
/* verify that the user is correct. */

function handle_login_process($user_name, $password) 
{   
    $mysql_conn = mydb_connect();                
    $data_array = $mysql_conn -> GetRow("SELECT id, user_name, password, salt FROM users WHERE user_name=@1@ LIMIT 1", $user_name);
    if(!$data_array)
    {
        // no such user
        return false;
    }
    $sql_salt = $data_array["salt"];
    $sql_passwd = $data_array["password"]; 
    $password = hash('sha512', $password.$sql_salt); // hash the password with the unique salt.
    
    if($sql_passwd == $password) // Check if the password in the database matches the password the user submitted. 
    { 
        // Password is correct!
        $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

        $user_id = preg_replace("/[^0-9]+/", "", $data_array["id"]); // XSS protection as we might print this value
        $_SESSION['user_id'] = $user_id; 
        $user_name = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user_name); // XSS protection as we might print this value
        $_SESSION['user_name'] = $user_name;
        $_SESSION['login_string'] = hash('sha512', $password.$ip_address.$user_browser);
        // Login successful.
        return true; 
    }   
    else 
    {
        // Password is not correct
        return false;
    }
    mydb_close($mysql_conn);
}

/* This function is used together with sec_session_start to check that the user is logged */
/* and that the user can be identified correctly. */

function login_check($require_admin = 0)
{
    // Check if all session variables are set
    if(isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['login_string'])) 
    {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
        $mysql_conn = mydb_connect();                
        $data_array = $mysql_conn -> GetRow("SELECT password, admin FROM users WHERE id=%1% LIMIT 1", $user_id);
        if(!$data_array) return false;
        if($require_admin && $data_array["admin"] < $require_admin) return false;
        $login_check = hash('sha512', $data_array["password"].$ip_address.$user_browser);    
        if($login_check == $login_string) return true;
    }
    return false;
}
?>