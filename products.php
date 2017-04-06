<?php
session_start();

//session_destroy();
require_once('db_handler.php');
require_once('category_list.php');

//print_r($_SESSION['cart']);

//inserts product id into cart session
//TODO check if value already is in cart
if(isset($_GET['product_id']))
{
	if (in_array($_GET['product_id'], $_SESSION['cart'])) {
	}
	else
	{
		$_SESSION['cart'][] = $_GET['product_id'];
	}

}

if(isset($_GET['category_id']))
{
	$query = "SELECT * FROM products WHERE category_id = " . $_GET['category_id'];

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
	<title>Products</title>
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
			?>
			<?php make_list($categories[0]); ?>	

		</div>
	</nav>
</div>

<div class="container">
	<h2>Products</h2>

		<?php
		if(isset($_GET['category_id']) && isset($rows))
		{
			foreach($rows as $row)
			{
				echo "<div class='row'>";
				echo "<div class='col col-lg-2'><a href='single_product.php'><img src='img/" . $row['image'] . ".jpg' width='100' height='100'></a></div>";
				echo "<div class='col col-lg-2'><label>" . $row['name'] . "</label></div>";
				echo "<div class='col col-lg-2'><label>" . $row['price'] . "</label></div>";
				echo "<div class='col col-lg-2'><label>Stock: " . $row['stock'] . "</label></div>";
				if(isset($_SESSION['username']))
				{
					echo "<div class='col col-lg-2'><a href='products.php?category_id=" . $_GET['category_id'] . "&product_id=" .$row['id'] . "'>Click to add to cart <span class='glyphicon glyphicon-shopping-cart'></span></a></div>";
				}
				echo "</div><hr>";
			}

		}
		?>
</div>



</body></html>