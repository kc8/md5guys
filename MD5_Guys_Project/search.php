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
			?>
		</div>
		<br />
			<div id="banner">
			<img src="./MD5_Guys_Banner.jpg" alt="MD5 Guys" width="400px" height="140px" />
			</div>

	</div>

		<div id="searchbar">
			<!--./search_update_delete.php-->
			<form input="text" method="POST" name="search" action="">
				Search: &nbsp;
				<input type="text" name="search_keyword">
				&nbsp;What to search:&nbsp;
				<select name="parameter">
					<option value="all">Employees</option>
					<option value="department">Department</option>
					<option value="project">Project</option>
				</select>
				<input type="Submit" name="Search" value="Search">
				<input type="Submit" name="all" value="Search All">
			</form>
		</div>

		<?php
			//below is reserved for message use. Prompts the user to success or failure
			// of different operations for exmpale: delete entry success or update success
			echo "<div id='logout'>" .$_SESSION['message']. "</div>"; $_SESSION['message'] = " ";
			echo "<div id='error'>" .$_SESSION['error']. "</div>"; $_SESSION['error'] = " ";
		//variable for output of session
		?>

		<?php
			/*
				MD5 Guys
				By: Johnathan Lamberson, Mike Bercume, Kyle Cooper
				Date: May 3, 2016
				Professor Jung
				CSCI 373
				Purpose of Page: The purpose of this page is to query the database and
				display the results in a table. The user can query the department, project
				or employee table.
			*/
			//Start a session, declare session variables
			session_start();
			$_SESSION['keywords']; //the actual keywords to search the database
			$_SESSION['parameter']; //What to search by

			/*
				Check that the POST data has data in it, if not that becomes search term
				and then becomes the session variable "keywords"
				Otherwise we check that session is full, if it is then that becomes the
				keywords search
				Also look at what parameter has been selected for search
				$keys varaible holds the array that has the users search terms
				$parameter holds the option in the dropdown for what table to search
			*/
			// attempts to search the whole database
			//just sets the session to all, so that I dont have to re-write my code 0_o
			if(isset($_POST['all'])) {
					$_SESSION['keywords'] = "all";
					$_SESSION['parameter'] = $_POST['parameter'];
			}

			if($_POST['search_keyword'] != NULL) {
				$_SESSION['keywords'] = $_POST['search_keyword'];
				$_SESSION['parameter'] = $_POST['parameter'];
				$keys = $_POST['search_keyword'];
				$parameter = $_POST['parameter'];
			}
			elseif($_SESSION['keywords']) {
				$keys = $_SESSION['keywords'];
				$parameter = $_SESSION['parameter'];;
			}
			elseif($_POST['search_keyword'] == "all") {
				$_SESSION['keywords'] = "all";
				$_SESSION['parameter'] = $_POST['parameter'];
				$keys = "all";
				$parameter = $_POST['parameter'];
			}
			else {
				$keys = $_SESSION['keywords'];
				$parameter = $_SESSION['parameter'];
			}
			//look for a comma and a space that separate keywords
			$keyword = explode(", ", $keys);

			$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject") or die("Cannot Connect to MySQL Server! Contact Administrator.");

			//check the dropdown box, execute correct function according to what user wants
				//to search
			if($parameter == "all"){ //search the whole database
				main();
			}
			elseif($parameter == "department"){ //search the depertment table
				department_search();
			}
			elseif ($parameter == "project"){ //search the project table
				project_search();
			}
			else {
					echo "<div id='logout'>Please select a department to search.
					 	To use multiple keywords use a comma and a space example: keyword1, keyword2.</div>";
			}

			/*
				@para will clean any plantext
				@return returns clean, new data
				A function that can take data and clean it. this helps prevent against SQL
				attackes
				Uses built in php functions

			*/
			function clean_keys($safeKey) {
				$safeKey = trim($safeKey);
				$safeKey = stripcslashes($safeKey);
				$safeKey = htmlspecialchars($safeKey);
				return $safeKey; //we have cleansed our keys with built in PHP functions to
				//echo "$safeKey";
			}

			/*
				Searchs the entire database and returns a table showing an amployee name,
				there photo, join data, deperatment name, project name, and salary
				Also lets the user print update, and delete a selected entry
			*/
			function main() {
				//get global variables: keyword and the link
				global $keyword;
				global $link;

				//the actual query that searches based on keywords
				$query = "SELECT emEmployeeID, emFullName, emPhoto, emJoinDate, deDepartmentName, plProjectName, emSalary
				FROM tblEmployee
				JOIN tblplDepartment
				ON emDepartmentID = deDepartmentID
				JOIN tblplProject
				ON emProjectID = plProjectID
				WHERE (emFullName LIKE '%$keyword%'"; //placeholder for our 'or' statement below
				//clean each keyword and then search through keywords and add them to the query,
				foreach($keyword as $k){
					$k = clean_keys($k);
					if($k == "all"){
						$k = "";
					}
					//query is no able to search by dates or salary
					$query .= " OR emFullName LIKE '%$k%'
						OR emJoinDate LIKE '%$k%'
						OR deDepartmentName LIKE '%$k%'
						OR plProjectName LIKE '%$k%'
						OR emSalary LIKE '%$k%'
						OR emEmployeeID LIKE '%$k%'";
			}
				$query .= ");";
				//echo $query; //for testing/debugging the query
				//display the query":
				echo "<div id=query>";
				$result = mysqli_query($link, $query) or die("failed to query". mysqli_connect_error());
				echo "<form action=./update.php method=POST>";
				echo "<input type=hidden name=parameter value=employee>";
				echo "<table>";
				echo "<th>Employee ID</th><th>Full Name</th><th>Photo</th><th>Join Date</th>";
				echo "<th>Department Name</th><th>Project Name</th><th>Salary</th><th>Update</th><th>Delete</th>";
				while($info = mysqli_fetch_assoc($result)) {
					echo "<tr>";
					echo "<td>$info[emEmployeeID]";
					echo "<td>$info[emFullName]</td>";
					//below displays the output of the image correctly
					echo '<td><img src="data:image/jpeg;base64,'.base64_encode($info['emPhoto']).'" alt="No Image" height="60" width="60" /></td>';
					echo "<td>".date_format(date_create($info['emJoinDate']),'m/d/Y')."</td>"; //formats the date correctly
					echo "<td>$info[deDepartmentName]";
					echo "<td>$info[plProjectName]";
					//format the salary numbers correclty:
					echo "<td>$".number_format($info['emSalary'],2,'.',',')."</td>";
					echo "<td><input type=checkbox value=$info[emEmployeeID] name=modifyEmployee[]></td>";
					echo "<td> <button type=submit name=delete value=$info[emEmployeeID] formaction=./delete.php>Delete</button</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<button type=submit name=submit>Update Selected</button>";
				echo "&nbsp;<input type=button id=print name=print value=Print onclick=window.print(); />";
				echo "</form>";
				echo "</div>";
				echo "<div id=footer>
						&copy;2016 MD5 Guys LLC.
						</div>";
			}

			/*
				Search only for departments by keywords. Prints out table of departments
				displaying their name
				Also lets the user print update, and delete a selected entry
			*/
			function department_search() {
				//get global variables: keyword and the link
				global $keyword;
				global $link;
				//the actual query:
				$query = "SELECT deDepartmentID, deDepartmentName FROM tblplDepartment
				WHERE (deDepartmentID LIKE '%$keyword%'"; //better way to do this?
				//clean each keyword, and then concatned each keyword
				foreach($keyword as $k) {
					$k = clean_keys($k);
					if($k == "all"){
						$k = "";
					}
					$query .= " OR deDepartmentID LIKE '%$k%' OR deDepartmentName LIKE '%$k%'";
				}
				$query .= ");";
				$result = mysqli_query($link, $query) or die("failed to query". mysqli_connect_error());
				//the table that will display the results
				echo "<div id=query><form action=./update.php method=POST>";
				echo "<input type=hidden name=parameter value=department>";
				echo "<table>";
				echo "<th>Department ID</th><th>Department Name</th><th>Update</th><th>Delete</th>";
				while($info = mysqli_fetch_assoc($result)) {
					echo "<tr>";
					echo "<td>$info[deDepartmentID]</td>";
					echo "<td>$info[deDepartmentName]</td>";
					echo "<td><input type=checkbox value=$info[deDepartmentID] name=modifyDepartment[]></td>"; //check this.
					echo "<td> <button type=submit name=delete value=$info[deDepartmentID] formaction=./delete.php>Delete</button</td>";
				}
				echo "</table>";
				echo "<button type=submit name=submit>Update Selected</button>";
				echo "&nbsp;<input type=button id=print name=print value=Print onclick=window.print(); />";
				echo "</form>";
				echo "</div>";
				echo "<div id=footer>
						&copy;2016 MD5 Guys LLC.
						</div>";
			}
			/*
				Search only the projects table, based on keyword(s) and then display
				a table with those results
				Also lets the user print update, and delete a selected entry
			*/
			function project_search() {
				//get global variables: keyword and the link
				global $keyword;
				global $link;
				//The query:
				$query = "SELECT plProjectID, plProjectName FROM tblplProject
				WHERE (plProjectID LIKE '%$keyword%'"; //placeholder for the or statement
				//clean each key and then concatinate it to the query.
				foreach($keyword as $k) {
					$k = clean_keys($k);
					if($k == "all") {
						$k = "";
					}
					$query .= " OR plProjectID LIKE '%$k%' OR plProjectName LIKE '%$k%'";
				}
				$query .= ");";
				$result = mysqli_query($link, $query) or die("failed to query". mysqli_connect_error());
				//display the table:
				echo "<div id=query><form action=./update.php method=POST>";
				echo "<input type=hidden name=parameter value=project>";
				echo "<table>";
				echo "<th>Project ID</th><th>Project Name</th><th>Update</th><th>Delete</th>";
				while($info = mysqli_fetch_assoc($result)) {
					echo "<tr>";
					echo "<td>$info[plProjectID]</td>";
					echo "<td>$info[plProjectName]</td>";
					echo "<td><input type=checkbox value=$info[plProjectID] name=modifyProject[]></td>"; //check this.
					echo "<td> <button type=submit name=delete value=$info[plProjectID] formaction=./delete.php>Delete</button</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<button type=submit name=submit>Update Selected</button>";
				echo "&nbsp;<input type=button id=print name=print value=Print onclick=window.print(); />";
				echo "</form>";
				echo "</div>";
				echo "<div id=footer>
						&copy;2016 MD5 Guys LLC.
						</div>";
			}
		mysqli_close();
		?>
  </div>
	</body>

</html>
