<?php
/*

*/
session_start();
$user = $_SESSION[user];
$loggedin = $_SESSION[loggedin];

if ($loggedin == "yes")
{
	/*Unset the Session Varables*/
	session_unset();
	//set logout message in session. Session will be distroyed once index.php reads the message
	$_SESSION['logout'] = "You have successfully logged out. Have a great day!";
	header("Location: ./index.php");

}
else
{
	//Validation to make sure the user is logged in in order to logout
	$_SESSION['error'] = "You have to be logged in to log out. Please Login to use this service.";
	header("Location: ./index.php");
}


?>
