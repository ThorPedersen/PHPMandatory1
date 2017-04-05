<?php
session_start();
require_once('db_handler.php');

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
	$stock 	= $_POST['stock'];

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
	if ($stock == null || removespaces($stock) == null)
	{
		$Registermessage .= "Username is required <br>";
	}
	if(strlen($Registermessage) == "")
	{
		$curr_timestamp = date('Y-m-d H:i:s');
		
		if($stmt = $conn->prepare("INSERT INTO products (name, date_added,  category_id, stock) VALUES (?, ?, ?, ?)")) {
				
			$stmt->bind_param('ssss', $name, $curr_timestamp, $category, $stock);
			$stmt->execute();
			$Registermessage = "Product was created in the system";
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
</head>
<body>

<div class="container" style="height:100px; background-color:grey;">
</div>

<div class="container">

<h2>Create Product</h2>

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
					<button type="submit" name="submit" class="btn btn-default">Create product</button>
				</div>       
			</form>
	</div>

</div>

</body></html>