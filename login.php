<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "mandatory");

$message = "";
/* check connection */
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['login']))
{
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
        $message = "INVALID USERNAME/PASSWORD Combination!";
    }
    $stmt->close();
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
	<div class="error-message"><?php if(isset($message)) { echo $message; } ?></div>	
	<div class="field-group">
		<div><label for="login">Username</label></div>
		<div><input name="userName" type="text" class="input-field"></div>
	</div>
	<div class="field-group">
		<div><label for="password">Password</label></div>
		<div><input name="password" type="password" class="input-field"> </div>
	</div>
	<div class="field-group">
		<div><input type="submit" name="login" value="Login" class="form-submit-button"></span></div>
	</div>       
</form>
</body></html>