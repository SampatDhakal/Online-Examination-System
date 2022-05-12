
<?php

include('oea.php');

$object = new oea();

if($object->is_login())
{
    header("location:".$object->base_url."admin/dashboard.php");
}

?>



<!DOCTYPE html>
<html lang="en">
<nav class="navbar navbar-expand-lg navbar-light bg-light">

<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
<li class="nav-item">
  <a class="nav-link" href="../../index.php">Home</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="../index.php">Student</a>
</li>
<li class="nav-item active">
  <a class="nav-link" href="">Admin</a>
</li>
</div>
</nav>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Web based online examination application</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>

</head>

<body>
<div class="container">
<div class="row justify-content-md-center mt-5">
					<div class="col-sm-6">
						<span id="error"></span>
				      	<div class="card">
				      		<form method="post" class="form-horizontal" action="" id="forget_adminpassword_form">
					      		<div class="card-header"><h3 class="text-center">Forget Password</h3></div>
					      		<div class="card-body">
				      			
				      				<div class="row form-group">
				      					<label class="col-sm-4 col-form-label"><b>Email Address</b></label>
				      					<div class="col-sm-8">
					      					<input type="text" name="user_email" id="user_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
					      				</div>
				      				</div>
				      			</div>
				      			<div class="card-footer text-center">
				      				<br />
				      				<input type="hidden" name="page" value="forgetpassword_admin" />
				      				<input type="hidden" name="action" value="get_adminpassword" />
				      				<p><input type="submit" name="submit" id="forget_password_button" class="btn btn-primary" value="Send" /></p>

				      				<p><a href="index.php">Login</a></p>
				      			</div>
				      		</form>
				      	</div>
				    </div>
				</div>
                </div>
</body>



    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script type="text/javascript" src="../vendor/parsley/dist/parsley.min.js"></script>

</body>

</html>

<script>



    $(document).ready(function(){

$('#forget_adminpassword_form').parsley();

$('#forget_adminpassword_form').on('submit', function(event){
    event.preventDefault();
    if($('#forget_adminpassword_form').parsley().isValid())
    {
        $.ajax({
            url:"../ajax_action.php",
            method:"POST",
            data:$(this).serialize(),
            dataType:"JSON",
            beforeSend:function()
            {
                $('#forget_password_button').attr('disabled', 'disabled');
                $('#forget_password_button').val('wait...');
            },
            success:function(data)
            {
                $('#forget_password_button').attr('disabled', false);
                $('#error').html(data.error);
                $('#forget_password_button').val('Send');
            }
        });
    }
});

});

</script>