<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./Project.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
		<meta charset="utf-8">
		<title>Employee Search</title>
		<script>
		</script>
	</head>
	<body>
		<nav>
		<ul id="nav">
			<li><a href="home.php">Home</a></li>
			<li><a href="search.php">Search</a></li>
			<li><a href="insert.php">Add</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</nav>
  <div id="wrapper">
	<div id="header">
		<div id="sessiondata">
			<?php
			/*
				Requires seesion.php which makes sure the user is logged in
				otherwise they will be redriected to the login page
			*/
			require "./session.php";
			printLogout();
			/*
			*/
			?>
		</div>
		<br/>
			<div id="banner">
			<img src="./MD5_Guys_Banner.jpg" alt="MD5 Guys" width="400px" height="140px" />
			</div>
	</div>
    <div id="instructions">
		<h1 class="center">MD5 Guys</h1>
        <h2 class="center">Encryption and Hashes</h2>
		<div id="slogan" class="center"> Security is our number one concern! </div>

	<div id="main-title">How to use this website:</div>
    <br/>
		<div id="title">Search Functions:</div>
    <p>
         Searches can use multiple keywords for example Department name, Employee name, project name and a combination of all of them. There is also
        a search all function. This will list all the items in the given field.
				Search also allows the user to use multiple keywords, to do this enter a comma and a space after each keyword
				for example: keyword1, keyword2 etc.
	</p>
    <div id="title">Update Entries:</div>
    <p>

         You can update any existing entries by checking the check box next to the record. After you have selected all the record to be updated, hit the update selected button on the bottom of the page. This will redirrect you to the update page. Make sure all fields are correct. If you choose to add a new picture, select the file to be uploaded and it will replace the existing picture.
    </p>
   <div id="title">Add Entries:</div>
    <p>
        You can add records by clicking on the add link at the top of the page. This screen gives you a few options, which thing to add (Employee, Department, or Project). It also asks you how many entries you would like to make. This number can be between 1 and 20. When you click submit, the text boxes for the apropriate number of entries is then loaded. Enter in your information and click add.
    </p>
     <?php
	 //only show if user is an admin
   if (getRole() == "admin")
   {
		echo "<div id='title'>Admin Tool:</div>
   <p>
   	You are an administrator of this server. You have rights to use the admin.php page. This page allows you to add users to use this tool. This page will require you to
	select a role for the user. Admin's have all the rights and can add more users. Employees can do all functions except adding users. Other is a group for uncategorized users. These users will only be able to view and not to edit. The username and password are for the the user to logon to the tool. Click submit and there will be success message or an error message.
   </p>";
   }
   ?>
    <div id="title">Login and Logout:</div>
   <p>
   		You will be required to login every time you vist this tool. If you try to visit a page when you are not logged in, you will be asked to login. The logout button will securely
        log you out. <div id="instructions-logout" class="center">Please be sure to logout when you are done.</div>
   </p>

</div>
	</body>
</html>
