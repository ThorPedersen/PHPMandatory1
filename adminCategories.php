<?php
session_start();
require_once('db_handler.php');
require_once('category_list.php');

if(isset($_SESSION["access"]))
{
	if($_SESSION["access"] == 1)
	{
		header("Location: login.php");
	}
}
else
{
	header("Location: login.php");
}
$query = "SELECT * FROM categories";
$result = $conn->query($query);

while($row = $result->fetch_array())
{
$rows[] = $row;
}

$result->close();

if(isset($_POST['submit']))
{
	$name 	= $_POST['name'];
	$category 	= $_POST['category'];

	$Registermessage = "";

	function removespaces($s)
	{
		return str_replace(" ", "", $s);
	}

		
	if($name == null || removespaces($name) == null)
	{
		$Registermessage .= "Firstname is required <br>";
	}
	if ($category == null || removespaces($category) == null)
	{
		$Registermessage .= "Lastname is required <br>";
	}
	if(strlen($Registermessage) == "")
	{
		$curr_timestamp = date('Y-m-d H:i:s');
		
		if($stmt = $conn->prepare("INSERT INTO categories (name, category_id) VALUES (?, ?)")) {
				
			$stmt->bind_param('ss', $name, $category);
			$stmt->execute();
			$Registermessage = "Category was created in the system";
		}
	}
}
$conn->close();
	
	


?>
<html>
<head>
	<title>Create Category</title>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" />
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
				if(isset($_SESSION['access']) && $_SESSION['access'] == 2)
				{
					echo "<li><a href='admin.php'><span class='glyphicon glyphicon glyphicon-cog'></span> Administrate</a></li>";
				}
				echo "<li><a href='cart.php'><span class='glyphicon glyphicon-shopping-cart'></span> Cart</a></li>";
				echo "<li><a href='logout.php'><span class='glyphicon glyphicon-log-out'></span> Log out</a></li>";
				echo "</ul>";
			}
			else
			{
				echo "<ul class='nav navbar-nav navbar-right'>";
				echo "<li><a href='login.php'><span class='glyphicon glyphicon-log-out'></span> Login</a></li>";
				echo "</ul>";
			}
			make_list($categories[0]);?>	

		</div>
	</nav>
</div>

<div class="container">

	<div class="page-header">
		<h1>Administrate categories</h1>      
	 </div>

			<form action="" method="post" id="frmRegister">
				<div class="form-group" style="height:20px">
					<span style='color:red'><?php if(isset($Registermessage)) { echo $Registermessage; } ?></span>
				</div>	
				<div class="form-group">
					<label for="name">name</label>
					<input name="name" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="category">Optional parent category</label>
								
					<select name="category" class="form-control">
						<?php
							echo "<option value='0'>empty</option>";
							foreach($rows as $row)
							{
							echo "<option value=" . $row['id'] . "> " . $row['name'] . "</option>";
							}
						
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="stock">Stock</label>
					<input name="stock" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<button type="submit" name="submit" class="btn btn-default">Create category</button>
				</div>       
			</form>
	</div>

</div>

</body></html>