<?php
/*
	Requires seesion.php which makes sure the user is logged in
	otherwise they will be redriected to the login page
*/
require "./session.php";
//Check if user is in other group. If so an error is prnted to the search.php page using the session variable "error"
if (getRole() == "other")
{
	session_start();
	//save error to session
	$_SESSION['error'] = "You are not authorized to use this funtion. Please login as an authorized user.";
	//redirect to search.php
	header('Location: ./search.php');	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Insert</title>
    <link rel="stylesheet" type="text/css" href="./Project.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
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
			//print username and admin.php link if the user is an admin
			printLogout();
			?>
		</div>
		<br />
			<div id="banner">
			<img src="./MD5_Guys_Banner.jpg" alt="MD5 Guys" width="400px" height="140px" />
			</div>
	</div>
    <?php
	//check to see if user has selected how many entries to insert and print form. If not execute the code to write table
	if ($_POST == NULL)
	{
		echo "
    <div id='login'>
    <h3>Select the number of records to enter</h3>
		<form input='text' method='POST' name='search' action=''>
			Insert into:
			<select name='parameter' required>
				<option value='employee'>Employees</option>
				<option value='department'>Department</option>
				<option value='project'>Project</option>
			</select><br/><br/>
			Number of items to add:
			<select name='amount' required>";
			for($i=1; $i<=20; $i++){echo "<option value=$i>$i</option>";}
			echo "</select><br/><br/>
			<input type='Submit' name='Submit' value='Submit'>
		</form></div>";
	}
	?>

		<?php
		/*
			
		*/
			$amount = $_POST['amount']; //amount of rows to add (from text box)
			$parameter = $_POST['parameter']; //what table to be added to (from dropdown)
			if($amount < 1 || $amount >= 21) { //make sure the proper value was checkd by the user
				echo "<div id='logout'>Please select a department to search and how many
				 entries you would like to make (between 1 and 20)</div>";
			}
			elseif(!is_numeric($amount)) { //check to see if a numerical value was entered
				echo "<div id='error'>Not a numeric value</div>";
			}
			else { //check what table the user selected to add to
				if($parameter == "employee") {
					employee_insert();
				}
				elseif($parameter == "department") {
					department_insert();
				}
				elseif ($parameter == "project") {
					project_insert();
				}
				else {
					echo "<div id='error'>An incorrect option was selected.</div>";
				}
			}


			/*
				employee_insert() insert queries the database for available projects, and
				departments in the database. Then generates textboxes in a table format
				allowing the user to enter new information into the database
				some validation is down through html5 and javascript, while some sanization is
				done on add.php
			*/
			function employee_insert() {
				global $amount;

				//the following code populates two dropdown lists from the database Projects and Departments
				$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject") or die('Failure to Connect to Database');
				$sql = "SELECT * FROM tblplDepartment;";
				$departments = mysqli_query($link, $sql) or die("failed to query". mysqli_connect_error());
				$sql = "SELECT * FROM tblplProject;";
				$projects = mysqli_query($link, $sql) or die("failed to query". mysqli_connect_error());
				$departmentsDropDown = "";
				$projectsDropDown = "";

				$departmentsDropDown .= "<option value=none>Select Department</option>";
				while($info = mysqli_fetch_assoc($departments)) {
						$departmentsDropDown .= "<option value=$info[deDepartmentID]>$info[deDepartmentName]</option>";
				}

				$projectsDropDown .= "<option value=none>Select Project</option>";
				while($info = mysqli_fetch_assoc($projects)) {
						$projectsDropDown .= "<option value=$info[plProjectID]>$info[plProjectName]</option>";
				}
				//create the table for the tuser to enter the data into
				echo "<form enctype=multipart/form-data action=./add.php method=POST>";
				echo "<table>";
				echo "<th>Full Name</th><th>Photo</th><th>Join Date</th>";
				echo "<th>Department Name</th><th>Project Name</th><th>Salary</th>";
				for($i = 0; $i< $amount; $i++) {
					echo "<tr>";
					echo "<td><input type=text name=fullname$i required></td>";
					//echo "<td><input name=MAX_FILE_SIZE value=102400 type=hidden>";
					echo "<td><input type=file name=photo$i accept=image/jpg ></td>"; //com back to this!!
					echo "<td><input type=date name=joindate$i required></td>";
					echo "<td><select name=department$i>$departmentsDropDown</td>";
					echo "<td><select name=project$i>$projectsDropDown</td>"; //fix this
					echo "<td>$<input type=text name=salary$i required></td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<input type=hidden name=parameter value=employee>";
				echo "<input type=Submit name=Submit value=Submit>";
				echo "</form>";
			}

			/*
			deprarment_insert() allows the user to enter new department names into
			the database.
			some validation is down through html5 and javascript, while some sanization is
			done on add.php
			*/
			function department_insert() {
				global $amount;

				echo "<form action=./add.php method=POST>";
				echo "<table>";
				echo "<th>Department Name</th>";
				//create the department form/inputs for the user to enter information into
				for($i = 0; $i< $amount; $i++) {
					echo "<tr>";
					echo "<td><input type=text name=department$i required></td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<input type=hidden name=parameter value=department>";
				echo "<input type=Submit name=Submit value=Submit>";
				echo "</form>";
			}

			/*
			project_insert() allows the user to enter new project names into
			the database.
			some validation is down through html5 and javascript, while some sanization is
			done on add.php
			*/
			function project_insert() {
				global $amount;

				echo "<form action=./add.php method=POST>";
				echo "<table>";
				echo "<th>Project	Name</th>";
				//create the inputs for the user to enter information into
				for($i = 0; $i< $amount; $i++) {
					echo "<tr>";
					echo "<td><input type=text name=project$i required></td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<input type=hidden name=parameter value=project>";
				echo "<input type=Submit name=Submit value=Submit>";
				echo "</form>";
			}
	mysqli_close($link);

	 	?>

	</body>
</html>
