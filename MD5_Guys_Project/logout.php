<?php
/*
	MD5 Guys
	By: Johnathan Lamberson, Mike Bercume, Kyle Cooper
	Date: May 3, 2016
	Professor Jung
	CSCI 373
	Purpose of Page: Logs the user out of the  current session and then redirects
	them back to index.php. This page unsets the loggedin and username varables and then 
	writes a successful logout message to the index.php page. If there is an error, an error is 
	printed on the index.php page.
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
