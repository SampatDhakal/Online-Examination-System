<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Web based online examination application</title>

	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>
	    <link rel="stylesheet" type="text/css" href="vendor/TimeCircle/TimeCircles.css"/>
	    <style>
	    	.border-top { border-top: 1px solid #e5e5e5; }
			.border-bottom { border-bottom: 1px solid #e5e5e5; }

			.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
	    </style>
	</head>
	<body>
	
		<?php
		if($object->is_student_login())
		{
		?>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  	<a class="navbar-brand" href="#">Student Panel</a>
		  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
		    	<span class="navbar-toggler-icon"></span>
		  	</button>
		  	<div class="collapse navbar-collapse" id="navbarText">
		    	<ul class="navbar-nav mr-auto">
				<li class="nav-item">
		        		<a class="nav-link" href="../index.php">Home</a>
		      		<li class="nav-item">
		        		<a class="nav-link" href="exam.php">MCQ Exam</a>
		      		</li>
		      		<li class="nav-item">
		        		<a class="nav-link" href="pexam.php">Labwork Exam</a>
		      		</li>
		      		<li class="nav-item">
		        		<a class="nav-link" href="logout.php">Logout</a>
		      		</li>
		    	</ul>
		  	</div>
		</nav>
		<?php
		}
		else
		{
		?>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">

  	<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../index.php">Home</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="">Student</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="admin/index.php">Admin</a>
      </li>
      
  </div>
</nav>

	    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
	      	<h1 class="display-4">Student Panel</h1>
	    </div>
	    <br />
	    <br />
	    <?php
		}
	    ?>
	    <div class="container-fluid">