<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
require_once("../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié */
	exit();
}
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Gestion des produits</title>
    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Interface de gestion des Réquisitions</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Accueil</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Aller à <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
			  <li><a href="#categories-EDLL">Gestion des Catégories EDLL</a></li>
			  <li><a href="#fournisseurs-EDLL">Gestion des Fournisseurs EDLL</a></li>
              <li><a href="#produits-EDLL">Gestion des Produits EDLL</a></li>
			  <li><a href="#requisitions-EDLL">Gestion des Réquistions EDLL</a></li>
			    <li class="divider"></li>
			  <li><a href="#categories-HBC">Gestion des Catégories HBO</a></li>
			  <li><a href="#fournisseurs-EDLL">Gestion des Fournisseurs HBO</a></li>
			  <li><a href="#produits-HBC">Gestion des Produits HBO</a></li>				        
              <li><a href="#requisitions-HBC">Gestion des Réquistions HBO</a></li>
              </ul>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	  
	  
<div class="container">

  <p>&nbsp;</p>  
     

        <div class="row">
         <div class="col-md-6">
          <table class="table">
            <tbody>
              <form action="lentilles_fabrication.php" method="post">
               <tr>
              	<th><h3> </th>

              </tr>
              </form> 
            
            </tbody>
          </table>
		</div>
     </div>   
     
      
      <p>&nbsp;</p>
          
 
 
             
    </div> <!-- /container -->


  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>