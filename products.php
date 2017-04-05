<?php
session_start();

//session_destroy();
require_once('db_handler.php');

print_r($_SESSION['cart']);

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
//test prints cart session
//print_r($_SESSION['cart']);

//product query
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
</head>
<body>

<div class="container" style="height:100px; background-color:grey;">
<?php 
if(isset($_SESSION['id']))
{
	echo "<h3><a href='logout.php'>Log out</a></h3>";
	echo "<h3><a href='cart.php'>Go to cart</a></h3>";
}
?>
</div>

<div class="container">
<h2>Products</h2>
<?php
if(isset($_GET['category_id']) && isset($rows))
{
	foreach($rows as $row)
	{
		echo "<img src='#'></img>";
		echo "<label>" . $row['name'] . "</label>";
		echo "<a href='products.php?category_id=" . $_GET['category_id'] . "&product_id=" .$row['id'] . "'> Add to cart</a><br>";
	}
}
?>
</div>


</body></html>