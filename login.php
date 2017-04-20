<?php
session_start();
require_once('db_handler.php');
require_once('category_list.php');

//check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Logs in by checking inputs
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
    if($stmt->num_rows == 1) 
    {
        if($stmt->fetch())
        {
            $_SESSION['access'] = $access;
            $_SESSION['id'] = $email;
            $_SESSION['username'] = $username;
			$_SESSION['cart'] = [];
            header("Location: cart.php");
        }
    }
    else {
        $LoginMessage = "Your login credentials are invalid.";
    }
    $stmt->close();
}
//Registers user by checking inputs. Always only creates normal user.
if(isset($_POST['register']))
{
	$firstName 	= $_POST['first_name'];
	$lastName 	= $_POST['last_name'];
	$userName 	= $_POST['user_name'];
	$email 		= $_POST['email'];
	$password 	= $_POST['password'];
	
	//Change to 2 for admin access privileges
	$access = 1;

	$Registermessage = "";

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

<div class="container">
	<div class="jumbotron">
	  <div class="container text-center">
		<h1>Shopping cart</h1>      
		<p>mandatory assignment</p>
	  </div>
	</div>
</div>

<div class="container">
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<?php 
			if(isset($_SESSION['id']))
			{
				echo "<ul class='nav navbar-nav navbar-right'>";
				echo "<li><a href='cart.php'><span class='glyphicon glyphicon-shopping-cart'></span> Cart</a></li>";
				echo "<li><a href='logout.php'><span class='glyphicon glyphicon-log-out'></span> Log out</a></li>";
				echo "</ul>";
			}
			?>
			<?php make_list($categories[0]); ?>	

		</div>
	</nav>
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-6">
		<div class="page-header">
			<h1>Login</h1>      
		</div>
		
			<form action="" method="post" id="frmLogin">
				<div class="form-group" style="height:20px">
					<span style="color:red;"><?php if(isset($LoginMessage)) { echo $LoginMessage; } ?></span>
				</div>	
				<div class="form-group">
					<label for="login">Username</label>
					<input name="userName" type="text" class="form-control" required placeholder="'admin' is username" pattern="[a-z0-9_-]{3,50}" title="Username have at least 3 characters, and at most 50 characters">
				</div>
				<div class="form-group">
					<div><label for="password">Password</label></div>
					<div><input name="password" type="password" class="form-control" required placeholder="'admin' is password" pattern=".{4,}" title="four or more characters (for testing with admin)"> </div>
				</div>
				<div class="form-group">
					<button class="btn btn-success" type="submit" name="login" value="Login">Login</button>
				</div>       
			</form>
		</div>
		<div class="col-lg-6">
			<div class="page-header">
				<h1>register</h1>      
			</div>

			<form action="" method="post" id="frmRegister">
				<div class="form-group" style="height:20px">
					<span style='color:red'><?php if(isset($Registermessage)) { echo $Registermessage; } ?></span>
				</div>	
				<div class="form-group">
					<label for="first_name">First name</label>
					<input name="first_name" type="text" class="form-control" pattern="[A-Za-z]{2,}" title="First name must only have letters and be longer than 2 letters" required>
				</div>
				<div class="form-group">
					<label for="last_name">Last name</label>
					<input name="last_name" type="text" class="form-control" pattern="[A-Za-z]{2,}" title="Last name must only have letters and be longer than 2 letters" required>
				</div>
				<div class="form-group">
					<label for="user_name">User name</label>
					<input name="user_name" type="text" class="form-control" pattern="[a-z0-9_-]{3,50}" title="Username have at least 3 characters, and at most 50 characters" required>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input name="email" type="email" class="form-control" pattern="[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input name="password" type="password" class="form-control" pattern=".{6,}" title="Six or more characters" required>
				</div>
				<div class="form-group">
					<button type="submit" name="register" value="register" class="btn btn-success">Register</button>
				</div>       
			</form>
		</div>
	</div>
</div>


</body></html>