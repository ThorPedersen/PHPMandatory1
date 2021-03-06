<?php
session_start();
require_once('db_handler.php');
require_once('category_list.php');

//Checks if a user is set who has admin access
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

//Populates rows array with categories.
$query = "SELECT * FROM categories";
$result = $conn->query($query);
while($row = $result->fetch_array())
{
	$rows[] = $row;
}

//populates rows2 array with products
$query2 = "SELECT * FROM products";
$result2 = $conn->query($query2);
while($row2 = $result2->fetch_array())
{
	$rows2[] = $row2;
}

//Makes editing for each product
function make_edited_list($rows2) {
	foreach($rows2 as $row2)
	{
		echo $row2['name'] . ": <a href='adminproducts.php?product_id=" . $row2['id'] . "'> edit</a><br>";
	}
}

$result->close();
$result2->close();

//Creates product in database, after validating the fields a bit more
if(isset($_POST['submit']))
{
	$name 	= $_POST['name'];
	$category 	= $_POST['category'];
	$image = $_POST['image'];
	$stock 	= $_POST['stock'];
	$price 	= $_POST['price'];

	$Registermessage = "";

	//Removes whitespace from input
	function removespaces($s)
	{
		return str_replace(" ", "", $s);
	}
	
	if($name == null || removespaces($name) == null)
	{
		$Registermessage .= "name is required <br>";
	}
	if ($category == null || removespaces($category) == null)
	{
		$Registermessage .= "Category is required <br>";
	}
	if ($stock == null || removespaces($stock) == null)
	{
		$Registermessage .= "Stock is required <br>";
	}
	if ($price == null || removespaces($price) == null)
	{
		$Registermessage .= "price is required <br>";
	}
	if(strlen($Registermessage) == "")
	{
		$curr_timestamp = date('Y-m-d H:i:s');
		
		if($stmt = $conn->prepare("INSERT INTO products (name, image, price, date_added,  category_id, stock) VALUES (?, ?, ?, ?, ?, ?)")) {
				
			$stmt->bind_param('ssssss', $name, $image, $price, $curr_timestamp, $category, $stock);
			if($stmt->execute())
			{
				$Registermessage = "Product was created in the system";
			}

		}
	}
}

//Updates product from form
if (isset( $_GET['product_id'] ))
{
	$product_id = $_GET["product_id"];

	if ($stmt = $conn->prepare("SELECT * FROM products WHERE id =?")) {
		$stmt->bind_param("s", $product_id);
		if($stmt->execute()) {
			$result = $stmt->get_result();
			$stmt->close();
			
			if($row = $result->fetch_array())
			{
				$product_id = $row['id'];
				$product_name = $row['name'];
				$product_image = $row['image'];
				$product_price = $row['price'];
				$product_stock = $row['stock'];		
				
				if (isset($_POST['edit'] ))
				{	
					$name = $_POST['product_name'];
					$image = $_POST['product_image'];
					$price = $_POST['product_price'];
					$stock = $_POST['product_stock'];
			
					$stmt2 = $conn->prepare("UPDATE products SET name=?, image=?, price=?, stock=? WHERE id=?");
					$stmt2->bind_param("sssss", $name, $image, $price, $stock, $product_id);
					$stmt2->execute();
					$stmt2->close();
					
					header("Location: adminProducts.php");
				}
			}
		}
	}
}

$conn->close();
	
	


?>
<html>
<head>
	<title>Create Product</title>
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
		<h1>Administrate products</h1>      
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
					<label for="category">Category</label>
								
					<select name="category" class="form-control">
						<?php
						
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
					<label for="price">Price</label>
					<input name="price" type="text" class="form-control" required>
				</div>				
				<div class="form-group">
					<label for="image">image</label>
					<input name="image" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<button type="submit" name="submit" class="btn btn-default">Create product</button>
				</div>       
			</form>
</div>
<div class="container">
	<div class="page-header">
		<h3>edit products</h3>      
	 </div>
<?php
	make_edited_list($rows2);
	
	if(isset($_GET['product_id']))
	{	
		echo "<form method='POST'>
		<input type='hidden' value='$product_id'><br>
		<label>Name: </label><input type='text' name='product_name' value='$product_name'><br>
		<label>Image path: </label><input type='text' name='product_image' value='$product_image'><br>
		<label>Price: </label><input type='text' name='product_price' value='$product_price'><br>
		<label>Stock: </label><input type='text' name='product_stock' value='$product_stock'><br>
		<button name='edit' type='submit'>edit task</button></form>
		";
	}
?>
</div>

</div>

</body></html>