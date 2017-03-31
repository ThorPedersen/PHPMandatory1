<?php
session_start();
require_once('db_handler.php');

/* check connection */
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['login']))
{
	$LoginMessage = "";
	$username = $_POST['userName'];
	$password = $_POST['password'];
	
	$stmt = $conn->prepare("SELECT user_name, user_email, user_access FROM users WHERE user_name = ? AND user_password = ? LIMIT 1");
	$stmt->bind_param('ss', $username, $password);
    $stmt->execute();
	
	$stmt->bind_result($username, $email, $access);
    $stmt->store_result();
    if($stmt->num_rows == 1)  //To check if the row exists
    {
        if($stmt->fetch()) //fetching the contents of the row
        {
            $_SESSION['access'] = $access;
            $_SESSION['id'] = $email;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
        }
    }
    else {
        $LoginMessage = "INVALID USERNAME/PASSWORD Combination!";
    }
    $stmt->close();
}

if(isset($_POST['register']))
{
	$firstName 	= $_POST['first_name'];
	$lastName 	= $_POST['last_name'];
	$userName 	= $_POST['user_name'];
	$email 		= $_POST['email'];
	$password 	= $_POST['password'];
	$access = 1;

	$Registermessage = "";

	//todo: sanitize the post data
	//check if the form is posted and the form values are not empty then run the code

	function removespaces($s)
	{
		return str_replace(" ", "", $s);
	}

		
	if($firstName == null || removespaces($firstName) == null)
	{
		$Registermessage .= "Firstname is required <br>";
	}
	if ($lastName == null || removespaces($lastName) == null)
	{
		$Registermessage .= "Lastname is required <br>";
	}
	if ($userName == null || removespaces($userName) == null)
	{
		$Registermessage .= "Username is required <br>";
	}
	if ($email == null || removespaces($email) == null)
	{
		$Registermessage .= "Email is required <br>";
	}
	if ($password == null || removespaces($password) == null)
	{
		$Registermessage .= "Password is required <br>";
	}
	if(strlen($Registermessage) == "")
	{
		if($stmt = $conn->prepare("INSERT INTO users (first_name, last_name,  user_name, user_email, user_password, user_access) VALUES (?, ?, ?, ?, ?, ?)")) {
				
			$stmt->bind_param('sssssi', $firstName, $lastName, $userName, $email, $password, $access);
			$stmt->execute();
			$Registermessage = "You are created in the system";
		}
	}
}

$q = 'SELECT id, name, parent_id FROM categories';
$r = mysqli_query($conn, $q);

$categories = array();

while(list($category_id, $category, $parent_id) = mysqli_fetch_array($r, MYSQLI_NUM))
{
	$categories[$parent_id][$category_id] = $category;
}

function make_list($parent) {
	global $categories;
	echo "<ul>";
	foreach($parent as $category_id => $cat) {
		echo " <li><a href='/PHPMandatory1/products.php?category_id=$category_id'> $cat </a> ";
		if(isset($categories[$category_id]))
		{
			make_list($categories[$category_id]);
		}
		echo "</li>";
	}
	echo "</ul>";
	
}

//make_list($categories[0]);
/*echo "<pre>";
print_r($categories);
echo "</pre>";*/
	
$conn->close();

?>
<html>
<head>
	<title>User Login</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
		
</head>
<body>

<div class="container" style="height:100px; background-color:grey;">
</div>


<div class="container" style="background-color:grey; height: 100px;">
	<nav>
	<?php make_list($categories[0]); ?>
	</nav>
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-12">
		<h2>Login</h2>
		
			<form action="" method="post" id="frmLogin">
				<div class="form-group" style="height:20px">
					<span style="color:red;"><?php if(isset($LoginMessage)) { echo $LoginMessage; } ?></span>
				</div>	
				<div class="form-group">
					<label for="login">Username</label>
					<input name="userName" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<div><label for="password">Password</label></div>
					<div><input name="password" type="password" class="form-control" required> </div>
				</div>
				<div class="form-group">
					<button class="btn btn-default" type="submit" name="login" value="Login">Login</button>
				</div>       
			</form>
			
			<h2>Register</h2>

			<form action="" method="post" id="frmRegister">
				<div class="form-group" style="height:20px">
					<span style='color:red'><?php if(isset($Registermessage)) { echo $Registermessage; } ?></span>
				</div>	
				<div class="form-group">
					<label for="first_name">First name</label>
					<input name="first_name" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="last_name">Last name</label>
					<input name="last_name" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="user_name">User name</label>
					<input name="user_name" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input name="email" type="email" class="form-control" pattern="[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input name="password" type="password" class="form-control" required>
				</div>
				<div class="form-group">
					<button type="submit" name="register" value="register" class="btn btn-default">Register</button>
				</div>       
			</form>
		</div>
	</div>
</div>


</body></html>