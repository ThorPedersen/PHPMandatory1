<?php
session_start();

//session_destroy();
require_once('db_handler.php');
require_once('category_list.php');

if(isset($_SESSION['cart']))
{
	//print_r($_SESSION['cart']);
}
	
if(isset($_GET['remove_id']))
{
	$_SESSION['cart'] = array_diff($_SESSION['cart'], [$_GET['remove_id']]);
}


if(isset($_SESSION['cart']))
{

	$query = "SELECT * FROM products WHERE id IN(".implode(',',$_SESSION['cart']).")";

	if ($result = $conn->query($query)) {

		/* fetch associative array */
		while($row = $result->fetch_array())
		{
			$rows[] = $row;
		}

		/* free result set */
		$result->free();
	}


}

$conn->close();

?>

<html>
<head>
	<title>Cart</title>
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
	<h2>Cart</h2>
	<form>
	<?php
	if(isset($rows) && $rows != null)
	{
		foreach($rows as $row)
		{
			echo "<div class='form-group row'>";
			echo "<div class='col-lg-2'><img src='img/" . $row['image'] . ".jpg' width='100' height='100'></div>";
			echo "<label class='col-lg-2'>Amount: </label>";
			echo "<div class='col-lg-2'><input type='text' class='form-control' id='" . $row['id'] . "' width='20'></div>";
			echo "<div class='col-lg-4'>" . $row['name'] . " <a href='cart.php?remove_id=" . $row['id'] . "'><span class='glyphicon glyphicon-remove' color:red;></span></a></div>";
			echo "</form></div><hr>";
		}
	echo "<div class='row'>";
	echo "<div class='col-md-3 offset-md-2'><input type='submit' class='btn btn-success' value='Purchase'></div>";
	echo 	"</form>";
	echo "</div>";
	}
	else
	{
		echo "<span>Your cart is empty. Log in to fill it up</span>";
	}
	?>
</div>

</body></html>