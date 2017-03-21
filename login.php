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
$conn->close();

?>
<html>
<head>
<title>User Login</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<form action="" method="post" id="frmLogin">
	<div class="error-message"><?php if(isset($LoginMessage)) { echo $LoginMessage; } ?></div>	
	<div class="field-group">
		<div><label for="login">Username</label></div>
		<div><input name="userName" type="text" class="input-field" required></div>
	</div>
	<div class="field-group">
		<div><label for="password">Password</label></div>
		<div><input name="password" type="password" class="input-field" required> </div>
	</div>
	<div class="field-group">
		<div><input type="submit" name="login" value="Login" class="form-submit-button"></span></div>
	</div>       
</form>

<form action="" method="post" id="frmRegister">
	<div class="error-message"><span style='color:red'><?php if(isset($Registermessage)) { echo $Registermessage; } ?></span></div>	
	<div class="field-group">
		<div><label for="first_name">First name</label></div>
		<div><input name="first_name" type="text" class="input-field" required></div>
	</div>
	<div class="field-group">
		<div><label for="last_name">Last name</label></div>
		<div><input name="last_name" type="text" class="input-field" required> </div>
	</div>
		<div class="field-group">
		<div><label for="user_name">User name</label></div>
		<div><input name="user_name" type="text" class="input-field" required> </div>
	</div>
		<div class="field-group">
		<div><label for="email">Email</label></div>
		<div><input name="email" type="text" class="input-field" pattern="[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}" required> </div>
	</div>
		<div class="field-group">
		<div><label for="password">Password</label></div>
		<div><input name="password" type="password" class="input-field" required> </div>
	</div>
	<div class="field-group">
		<div><input type="submit" name="register" value="register" class="form-submit-button"></span></div>
	</div>       
</form>


</body></html>