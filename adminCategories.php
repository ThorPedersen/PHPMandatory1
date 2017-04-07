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
	$categories 	= $_POST['categories'];

	$Registermessage = "";

	function removespaces($s)
	{
		return str_replace(" ", "", $s);
	}

		
	if($name == null || removespaces($name) == null)
	{
		$Registermessage .= "Firstname is required <br>";
	}
	if ($categories == null || removespaces($categories) == null)
	{
		$Registermessage .= "Lastname is required <br>";
	}
	if(strlen($Registermessage) == "")
	{
		$curr_timestamp = date('Y-m-d H:i:s');
		
		if($stmt = $conn->prepare("INSERT INTO categories (name, categories_id) VALUES (?, ?)")) {
				
			$stmt->bind_param('ss', $name, $categories);
			$stmt->execute();
			$Registermessage = "categories was created in the system";
		}
	}
}

// Make list
function make_edited_list($parent) {
	global $categories;
	echo "<ol>";
	foreach($parent as $category_id => $todo) {
		echo "<li>$todo <a href='adminCategories.php?category_id=$category_id'>edit</a>";
		if(isset($categories[$category_id])) {
			
			make_edited_list($categories[$category_id]);
		}
		echo '</li>';
	}
	echo "</ol>";
}


if ( isset( $_GET['category_id'] ) && !empty( $_GET['category_id'] ) )
{
	$category_id = $_GET["category_id"];

	$q = "SELECT id, name FROM categories WHERE id = $category_id";
	$r = mysqli_query($conn, $q);
	
	$row=mysqli_fetch_assoc($r);
	$category_id = $row['id'];
	$category_name = $row['name'];
	
	if (isset($_POST['edit'] ))
	{		
		$q2 = "UPDATE categories set name = '" . $_POST['category_name'] . "' WHERE id = $category_id";
		$r2 = mysqli_query($conn, $q2);
		header("Location: adminCategories.php");
	}
}


$conn->close();
	
	


?>
<html>
<head>
	<title>Create categories</title>
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
					<label for="categories">Optional parent categories</label>
								
					<select name="categories" class="form-control">
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
					<button type="submit" name="submit" class="btn btn-default">Create categories</button>
				</div>       
			</form>
</div>
<div class="container">
	<div class="page-header">
		<h3>edit categories</h3>      
	 </div>
<?php make_edited_list($categories[0]);
	if(isset($_GET['category_id']))
	{
		echo "<form method='POST'>
		<input type='hidden' value='$category_id'><br>
		<input type='text' name='category_name' value='$category_name'><br>
		<button name='edit' type='submit'>edit task</button></form>
		";
	}
?>
</div>

</div>

</body></html>