<?php
session_start();

//session_destroy();
require_once('db_handler.php');

if(isset($_SESSION['cart']))
{
	print_r($_SESSION['cart']);
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
</head>
<body>

<div class="container" style="height:100px; background-color:grey;">
<?php 
if(isset($_SESSION['id']))
{
	echo "<h3><a href='logout.php'>Log out</a></h3>";
	echo "<h3><a href='cart.php'>Go to cart</a></h3>";
	echo "<h3><a href='products.php?category_id=6'>Go to products</a></h3>";
}
?>
</div>

<div class="container">
<h2>Cart</h2>
<?php
if(isset($rows) && $rows != null)
{
	foreach($rows as $row)
	{
		echo $row['name'] . " <a href='cart.php?remove_id=" . $row['id'] . "'>Remove from list</a><br>";
		/*echo "<img src='#'></img>";
		echo "<label>" . $row['name'] . "</label>";
		echo "<a href='products.php?category_id=" . $_GET['category_id'] . "&product_id=" .$row['id'] . "'> Add to cart</a><br>";*/
	}
}
?>
</div>


</body></html>