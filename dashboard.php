<?php
session_start();
?>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container" style="height:100px; background-color:grey;">
</div>
<div class="container" style="background-color:grey; height: 50px;">
</div>

<div class="container">

		<div class="row jumbotron">
			<div class="col-lg-12" style="text-align: center;">
				<h2>User Dashboard</h2>

				<?php
				if(isset($_SESSION["username"])) {
				?>
				<p>Welcome <?php echo $_SESSION["username"]; ?>. Click here to <a href="logout.php" tite="Logout">Logout</a></p>
				<?php
				}

				if(isset($_SESSION["access"]))
				{
					if($_SESSION["access"] == 2)
					{
						echo "<p>You are an admin</p>";	
					}
				}

				?>
				</td>
				</tr>
			</div>
	</div>

</div>

</body></html>