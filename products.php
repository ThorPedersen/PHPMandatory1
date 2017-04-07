<?php
session_start();

require_once('db_handler.php');
require_once('category_list.php');

//checks if quantity (of any individual products) is posted. Then inserts that amount into session cart, along with a product id.
if(isset($_POST["quantity"]) &&  $_POST["quantity"] != "")
{
	$_SESSION['cart'][$_GET['product_id']] = $_POST["quantity"];
}

//Selects a specific category
if(isset($_GET['category_id']))
{
	$category_id = $_GET['category_id'];
	$query = "SELECT * FROM products WHERE category_id =?";

	$stmt = $conn->stmt_init();
	if($stmt->prepare($query))
	{
		$stmt->bind_param("s", $category_id);

		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_array())
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
	<title>Products</title>
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
			?>
			<?php make_list($categories[0]); ?>	

		</div>
	</nav>
</div>

<div class="container">
	<div class="page-header">
    <h1>Products</h1>      
	</div>

		<?php
		if(isset($_GET['category_id']) && isset($rows))
		{
			foreach($rows as $row)
			{			
				echo "<div class='row'>";
				echo "<form method='post' action='products.php?category_id=" . $_GET['category_id'] . "&product_id=" . $row['id'] . "'>";
				echo "<div class='col col-lg-2'><a href='single_product.php?product_id=" . $row['id'] . "'><img src='img/" . $row['image'] . ".jpg' width='100' height='100'></a></div>";
				echo "<div class='col col-lg-1'><label>" . $row['name'] . "</label></div>";
				echo "<div class='col col-lg-1'><label>" . $row['price'] . "</label></div>";
				echo "<div class='col col-lg-2'><label>Stock: " . $row['stock'] . "</label></div>";

				if(isset($_SESSION['username']))
				{
					echo "<div class='col col-lg-2'><label>Amount</label></div>";
					echo "<div class='col col-lg-2'><input type='text' name='quantity' value='1' size='2' required pattern='[0-9.-_]{1,}' /></div>";
					echo "<div class='col col-lg'2'><input type='image' name='submit' value='Add to cart' src='img/cart.png' height='30' width='30' ></div>";
				}
				echo "</form></div><hr>";
			}

		}
		?>
</div>



</body></html>