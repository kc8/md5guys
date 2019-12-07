<?php
/*

*/
session_start();
$user = $_SESSION[user];
$loggedin = $_SESSION[loggedin];
$role = $_SESSION[role];
/*
	checks to see if the user is logged in based on session variables.
	returns the role of the user (admin, regular or employee) to see
	if they can access the admian panel
	If there is no user logged in, then they are redirected to index.php
	so they can not access any page
*/
function printLogout()
{
	//retrive global variables
	global $loggedin;
	global $user;
	global $role;
	//check if loggedin= yes 
	if ($loggedin == "yes")
	{
		//if yes print the username to the header
		echo "Username: " .$user ; 
		//if role is admin then print the admin.php link which is hidden for everyone else 
		if ($role == "admin")
		{
			echo " "."<a href='./admin.php'>Admin Page</a>";
		}
	}
		else
	{
		//if you are not logged in, send to login page
		header("Location: ./index.php");
	}

}
/*
	returns if the user is loggedin or not.
	true means they are
	false means they are not. This is used for non visable pages 
	such as UpdateDB.php which the user does not see. 
*/
function returnLoggedin()
{
	global $loggedin;
	if ($loggedin == "yes")
	{
		return "true";
	}
	else
	{
		return "false";
	}
}
/*
	returns the user name of the logged in user
*/
function getUser()
{
	global $user;
	return $user;
}

/*
	gets the role of the user and then returns it.
*/
function getRole()
{
	global $role;
	return $role;
}
?>
