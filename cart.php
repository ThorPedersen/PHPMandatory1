<?php
session_start();

require_once('db_handler.php');
require_once('category_list.php');

//Checks content of cart session
if(isset($_SESSION['cart']))
{
	//print_r($_SESSION['cart']);
}

//Checks content of price session
$_SESSION['price'] = [];
if(isset($_SESSION['price']))
{
	//print_r($_SESSION['price']);
}

//Removes product id from cart
if(isset($_GET['remove_id']))
{
	unset($_SESSION['cart'][$_GET['remove_id']]);
}

//Fetches all products from cart
if(isset($_SESSION['cart']))
{
	$items = [];
	foreach ($_SESSION['cart'] as $key => $value)
	{
		$items[] = $key;
	}

	//Not sure how to make this more secure
	$query = "SELECT * FROM products WHERE id IN(".implode(',',$items).")";

	if ($result = $conn->query($query)) {

		while($row = $result->fetch_array())
		{
			$rows[] = $row;
		}

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
	<div class="page-header">
    <h1>Cart</h1>      
  </div>
	<form>
	<?php
	if(isset($rows) && $rows != null)
	{
		foreach($rows as $row)
		{
			echo "<div class='form-group row'>";
			echo "<div class='col-lg-2'><img src='img/" . $row['image'] . ".jpg' width='100' height='100'></div>";
			echo "<label class='col-lg-2'>Price: " . $row['price'] . " </label>";
			echo "<label class='col-lg-2'>Amount: </label>";
			echo "<div class='col-lg-2'><input type='text' class='form-control' id='" . $row['id'] . "' width='20' value=" . $_SESSION['cart'][$row['id']] . "></div>";
			echo "<div class='col-lg-4'>" . $row['name'] . " <a href='cart.php?remove_id=" . $row['id'] . "'><span class='glyphicon glyphicon-remove' color:red;></span></a></div>";
			echo "</form></div><hr>";
			
			if(array_key_exists($row['id'], $_SESSION['price'])){
				
			}
			else
			{
				$_SESSION['price'][$row['id']] = ($_SESSION['cart'][$row['id']] * $row['price']);
			}			
		}
		echo "<div class='row'>";
		echo "<div class='col-md-3 offset-md-2'><input type='submit' class='btn btn-success' value='Purchase'></div>";
		echo "</form>";
		echo "</div>";
	
	}
	else
	{
		echo "<span>Your cart is empty</span><br>";
	}
	if(isset($_SESSION['price'])) {
		$totalprice = 0;
		foreach($_SESSION['price'] as $price)
		{
			$totalprice+=$price;
			//echo $price;
		}

	}
	if(isset($totalprice))
	{
		echo "<h2>Total price: " . $totalprice . ",-</h2>";
	}

	?>
</div>

</body></html>