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
	<link href="font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
			?>
			<?php //Calls a recursive method from category_list.php
				make_list($categories[0]); 
			?>	

		</div>
	</nav>
</div>

<div class="container">

	<div class="page-header">
		<h1>Dashboard</h1>      
	 </div>
	<div class="row">

		<div class="col-md-6">
			<a href="adminCategories.php">
			<div class="row">
				<div class="col-md-8">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-list fa-4x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<div class="huge"></div>
									<div><h3>Categories</h3></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div></a>
		</div>

		<a href="adminProducts.php">
		<div class="col-md-6">
			<div class="row">
				<div class="col-lg-8 ">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-list fa-4x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<div class="huge"></div>
									<div><h3>Products</h3></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div></a>		
	</div>	

</div>

</body></html>