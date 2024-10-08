<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Plate-forme de requisitions: Back End d'Administration</title>
    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">


<?php if ($_REQUEST[err]=='y'){
	  	 echo "<div class=\"alert alert-danger\" role=\"alert\">Username or password is invalid. Remember both are case sensitive.</div>";
	  } ?>

      <form class="form-signin" action="login.php" method="post" role="form">
        <h2 class="form-signin-heading">Veuillez vous authentifier</h2>
        
        <label for="inputusername" class="sr-only">Username</label>
        <input type="text" id="inputusername" name="inputusername" class="form-control"  placeholder="Username" required autofocus>
        
        <label for="inputpassword" class="sr-only">Password</label>
        <input type="password" id="inputpassword" name="inputpassword" class="form-control"  placeholder="Password" required>
        
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Se souvenir de moi
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Connexion</button>
      </form>

    </div> <!-- /container -->

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>