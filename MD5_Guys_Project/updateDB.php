<?php
/*

				*/

//check to make sure the user is logged in.
require "./session.php";
if(returnLoggedin() == "false")
{
	//if not redirect to index.php
	header("Location: ./index.php");
}
if (getRole() == "other")
{
	session_start();
	$_SESSION['error'] = "You are not authorized to use this funtion. Please login as an authorized user.";
	header('Location: ./search.php');
}
//MySQL connection
$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject")
		or die('Failure to Connect to Database');
//Check what to update and execute apropriate function
if ($_POST['parameter'] == "updateEmployee")
{
	updateEmployee();
}
elseif ($_POST['parameter'] == "updateDepartment")
{
	updateDepartment();
}
elseif ($_POST['parameter'] == "updateProject")
{
	updateProject();
}
/*
	Clean the input from the html page before entering into our database
	this strips all characters. Helps mitigate attacks as well.
*/
function clean_input($value) {
	$value = trim($value);
	$value = stripcslashes($value);
	$value = htmlspecialchars($value);
	return $value; //we have cleansed our keys with built in PHP functions to
}
/*
	below inserts the Employee values into the database
*/
function updateEmployee()
{
		global $link;
		$id = array();
		$employee = array();
		$joindate = array();
		$department= array();
		$image = array();
		$project = array();
		$salary = array();
		$fileCount = count($_FILES);
		echo "file count: ".$fileCount;
		$fileCount = count($_FILES['photo']);

			foreach($_FILES as $key => $value) {
				$curPhoto = $key;
				foreach($_FILES as $key => $value) {
					$filee = $_FILES[$key]['tmp_name'];
					$imageTemp = $_FILES['image']['tmp_name'];
					$fp = fopen($filee, 'r');
					$imageFile = fread($fp, filesize($filee));
					$imageFile = addslashes($imageFile);
					fclose($fp);
					$image[] = $imageFile;
			}
		}
//check which field and save in the appropriate variable
		foreach($_POST as $key => $value){
			if(preg_match('/id/', $key)){
				$id[] = $value;
			}
			if(preg_match('/name/', $key)){
				$employee[] = $value;
			}
			if(preg_match('/join/', $key)){
				$joindate[] = $value;
			}
			if(preg_match('/department/', $key)){
				$department[] = $value;
			}
			if(preg_match('/project/', $key)){
				$project[] = $value;
			}
			if(preg_match('/salary/', $key)){
				$salary[] = $value;
			}
		}

		$num = count($employee);
		for($i = 0; $i < $num; $i++) {
			if(!empty($image[$i])) {
				$tempEmployee = clean_input($employee[$i]);
				$tempDepartment = clean_input($department[$i]);
				$tempProject = clean_input($project[$i]);
				$tempSalary = clean_input($salary[$i]);
				$actualSalary = str_replace(',','',$tempSalary);
				$sql = "UPDATE tblEmployee
						SET emFullName='$tempEmployee',
						emJoinDate='$joindate[$i]',
						emPhoto= '$image[$i]',
						emDepartmentID='$tempDepartment',
						emProjectID='$tempProject',
						emSalary= '$actualSalary'
						WHERE emEmployeeID='$id[$i]'";
						echo "sql one";
			}
			elseif(empty($image[$i])) {
				$tempEmployee = clean_input($employee[$i]);
				$tempDepartment = clean_input($department[$i]);
				$tempProject = clean_input($project[$i]);
				$tempSalary = clean_input($salary[$i]);
				$actualSalary = str_replace(',','',$tempSalary);
				$sql = "UPDATE tblEmployee
						SET emFullName='$tempEmployee',
						emJoinDate='$joindate[$i]',
						emDepartmentID='$tempDepartment',
						emProjectID='$tempProject',
						emSalary= '$actualSalary'
						WHERE emEmployeeID='$id[$i]'";
						echo "sql two";
			}
			else {
				echo "Could not update the database: ";
			}
			$result = mysqli_query($link, $sql) or die("Query failed");
		}
		header("Location: ./search.php");//Send user back to query
		$_SESSION['message'] = "Entry succesully updated"; //sets the message for confirmation to the user
}

/*
*/
function updateDepartment()
{
	global $link;
	$id = array();
	$department = array();
	foreach($_POST as $key => $value)
	{
		//check which field and save in the appropriate variable
			if(preg_match('/id/', $key)){
				$id[] = $value;

			}
			if(preg_match('/department/', $key)){
				$department[] = $value;
			}
	}
	$num = count($department);
	for($i = 0; $i < $num; $i++) {
			$tempDepartment = clean_input($department[$i]);
			$sql = "UPDATE tblplDepartment
					SET deDepartmentName='$tempDepartment'
					WHERE deDepartmentID='$id[$i]'";
			$result = mysqli_query($link, $sql) or die("Query failed");
		}

		header("Location: ./search.php");//Send user back to query
		$_SESSION['message'] = "Entry succesully updated";//sets the message for confirmation to the user
}
/*
*/
function updateProject()
{
	global $link;
	$id = array();
	$project = array();
	foreach($_POST as $key => $value)
	{
		//check which field and save in the appropriate variable
			if(preg_match('/id/', $key)){
				$id[] = $value;

			}
			if(preg_match('/project/', $key)){
				$project[] = $value;

			}
	}
	$num = count($project);
	for($i = 0; $i < $num; $i++) {
		$tempProject = clean_input($project[$i]);
			$sql = "UPDATE tblplProject
					SET plProjectName='$project[$i]'
					WHERE plProjectID='$id[$i]'";
			$result = mysqli_query($link, $sql) or die("Query failed");
		}

		header("Location: ./search.php");//Send user back to query
		$_SESSION['message'] = "Entry succesully updated";//sets the message for confirmation to the user
}
?>
