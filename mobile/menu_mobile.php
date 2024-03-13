<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
//ini_set('display_errors', '1');
session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié */
	exit();
}
require_once(__DIR__.'/../constants/url.constant.php');
?>
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

    <title>Labadmin Mobile Login</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo constant('DIRECT_LENS_URL'); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	  <?php
	  /*
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
          <a class="navbar-brand" href="#">Labadmin Mobile</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Accueil</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Aller à <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
              <li><a href="#autres">Autres</a></li>
                <li class="divider"></li>
                <li><a href="#exportations">Exportations</a></li>
                <li class="divider"></li>
                <li><a href="#promotions">Promotions</a></li>
                <li class="divider"></li>
                <li><a href="#comissions">Rapports de comission</a></li>
                <li class="divider"></li>
                <li><a href="#redirections">Redirections</a></li>
              </ul>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	  */
	  ?>

<div class="container">

  <p>&nbsp;</p>  
     

        <div class="row">
         <div class="col-md-6">
          <table class="table">
            <tbody>
              <form action="lentilles_fabrication.php" method="post">
               <tr>
              	<th><h3>EDLL</h3>Lentilles&nbsp;en&nbsp;Fabrication</th>
                
                <td>Mois: 
                <select name="month" class="formText" id="month">
                     <option value="janvier">Janvier</option>
                     <option value="fevrier">Février</option>
                     <option value="mars">Mars</option>
                     <option value="avril">Avril</option>
                     <option value="mai">Mai</option>
                     <option value="juin">Juin</option>
                     <option value="juillet">Juillet</option>
                     <option value="aout">Août</option>
                     <option value="septembre">Septembre</option>
                     <option value="octobre">Octobre</option>
                     <option value="novembre">Novembre</option>
                     <option value="decembre">Décembre</option>
                </select>
                
                <td>Année:  
                <select name="year" class="formText" id="year">
					 
					 <option  value="2021">2021</option>
					 <option selected value="2022">2022</option>
					 <option  value="2023">2023</option>
					 <option  value="2024">2024</option>
                </select>
                
                </td>
                <td><button type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
				<td>Le rapport sera envoyé par courriel aux personnes concernées</td>
              </tr>
              </form> 
            
            </tbody>
          </table>
		</div>
     </div>   
     
      
     
	 

	 <p>&nbsp;</p>
       
        <div class="row">
         <div class="col-md-6">
          <table class="table">
            <tbody>
              <form action="lentilles_fabrication_hbc.php" method="post">
               <tr>
              	<th><h3>HBC</h3> Lentilles&nbsp;en&nbsp;Fabrication</th>
                
                <td>Mois: 
                <select name="month" class="formText" id="month">
                     <option value="janvier">Janvier</option>
                     <option value="fevrier">Février</option>
                     <option value="mars">Mars</option>
                     <option value="avril">Avril</option>
                     <option value="mai">Mai</option>
                     <option value="juin">Juin</option>
                     <option value="juillet">Juillet</option>
                     <option value="aout">Août</option>
                     <option value="septembre">Septembre</option>
                     <option value="octobre">Octobre</option>
                     <option value="novembre">Novembre</option>
                     <option value="decembre">Décembre</option>
                </select>
                
                <td>Année:  
                <select name="year" class="formText" id="year">
					 <option  value="2021">2021</option>
					 <option selected value="2022">2022</option>
					 <option  value="2023">2023</option>
					 <option  value="2024">2024</option>
                </select>
                
                </td>
				<td><button type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
				<td>Le rapport sera envoyé par courriel aux personnes concernées</td>
                
              </tr>
              </form> 
            
            </tbody>
          </table>
		</div>
     </div>   

 <p>&nbsp;</p>
          
 
     
	  <div class="row">
         <div class="col-md-6">
          <table class="table">
            <tbody>
              <form action="commandes_par_knr.php" method="post">
               <tr>
              	<th><h3>EDLL</h3>Commandes par KNR (EDM, HLX, VAU, SOR,FRE,GRIFFÉ)</th>
                
                <td>Mois: 
                <select name="month" class="formText" id="month">
                     <option value="janvier">Janvier</option>
                     <option value="fevrier">Février</option>
                     <option value="mars">Mars</option>
                     <option value="avril">Avril</option>
                     <option value="mai">Mai</option>
                     <option value="juin">Juin</option>
                     <option value="juillet">Juillet</option>
                     <option value="aout">Août</option>
                     <option value="septembre">Septembre</option>
                     <option value="octobre">Octobre</option>
                     <option value="novembre">Novembre</option>
                     <option value="decembre">Décembre</option>
                </select>
                
                <td>Année:  
                <select name="year" class="formText" id="year">
					<option  value="2021">2021</option>
					 <option selected value="2022">2022</option>
					 <option  value="2023">2023</option>
					 <option  value="2024">2024</option>
                </select>
                
                </td>
                <td><button type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
				<td>Le rapport sera envoyé par courriel aux personnes concernées</td>
              </tr>
              </form> 
            
            </tbody>
          </table>
		</div>
     </div>  


       <p>&nbsp;</p> 
       

	
	<?php
	?>
       
       <p>&nbsp;</p> 
       
       
       
     
       
	<div class="row">
        <div class="col-md-6" >
          <table class="table" >
            <tbody>
            <tr><td colspan="3" align="center"><h3>Rapport de reprise</h3></td></tr>
            
               <form action="../rapports/reprises/rapport_comparatif_reprise_mensuel.php" method="post">
               <tr>
              	<th>Rapport de reprise comparatif EDLL</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>

				<th>Comparer avec (Facultatif)</th>
				<td>Date&nbsp;From: <input type="text" id="date3" name="date3" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date4" name="date4" size="10">	</td>
                <td><button type="submit" class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
			 
			   <p>&nbsp;</p> 
			 
			  <form action="../rapports/reprises/rapport_comparatif_reprise_mensuel_hbc.php" method="post">
               <tr>
              	<th>Rapport de reprise comparatif HBO</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>

				<th>Comparer avec (Facultatif)</th>
				<td>Date&nbsp;From: <input type="text" id="date3" name="date3" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date4" name="date4" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
			 
			   <p>&nbsp;</p> 
			 
			  <form action="rapport_reprise_griffe_tr.php" method="post">
               <tr>
              	<th>Griffé Lunetier Trois-Rivières</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 


			 <form action="rapport_reprise_swiss.php" method="post">
               <tr>
              	<th>Swisscoat EDLL</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
			  
			  
			   <form action="rapport_reprise_swiss_doubles.php?debug=yes" method="post">
               <tr>
              	<th>Swisscoat EDLL (commandes en double, peu importe la raison)</th>
                <td>Date&nbsp;From: <input type="text" id="datea" name="datea" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="dateb" name="dateb" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
			  
			  
			  
			  
			 <form action="rapport_reprise_knr.php" method="post">
               <tr>
              	<th>K and R EDLL</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
			  
			  
			   <form action="rapport_reprises_swiss_hbc.php" method="post">
               <tr>
              	<th>Swisscoat HBC</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
				
			   <form action="rapport_reprise_hko.php" method="post">
               <tr>
              	<th>HKO</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
			 </form> 
			
			  <form action="../rapport_reprise_hko_roberto.php" method="post">	
			 <tr>
              	<th>HKO avec toutes raisons de reprises (Roberto)</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button disabled type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
              </tr>
              </form> 
				
				  <form action="rapport_reprise_gkb.php" method="post">	
			 <tr>
              	<th>GKB</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                <td><button type="submit" class="btn btn-lg btn-secondary" name="export_to" value="xls">Exporter</button></td>
              </tr>
              </form> 
              

            </tbody>
          </table>
		</div>
     </div>
	
	
	       
       <p>&nbsp;</p> 
	
	
	
	<div class="row">
        <div class="col-md-6" >
          <table class="table" >
            <tbody>
            <tr><td colspan="3" align="center"><h3>RAPPORT INCENTIVE</h3></td></tr>
            
			  <form action="rapport_incentive_h2.php" method="post">
               <tr>
              	<th>Rapport de Bonus/Incentive HBC</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
              </tr>
              </form> 
				
				
				<form action="rapport_incentive_edll_v2_test.php" method="post">
               	<tr>
              	<th>Rapport de Bonus/Incentive EDLL</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
              	</tr>
              </form> 
			  
			  
			  <form action="rapport_incentive_moncton.php" method="post">
               	<tr>
              	<th>Rapport de Bonus/Incentive [Moncton seulement]</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
              	</tr>
              </form> 
				
        <form action="rapport_incentive_fredericton.php" method="post">
                <tr>
                <th>Rapport de Bonus/Incentive [Fredericton seulement]</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
                </tr>
              </form> 


            </tbody>
          </table>
		</div>
     </div>
	 
	 
	 
	 
	 
	
	<div class="row">
        <div class="col-md-6" >
          <table class="table" >
            <tbody>
            <tr><td colspan="3" align="center"><h3>RAPPORT SUB LICENSE HBO</h3></td></tr>

				<form action="rapport_vente_sub_license.php" method="post">
               	<tr>
              	<th>Ventes Sub License HBO</th>
                <td>Date&nbsp;From: <input type="text" id="date1" name="date1" size="10">	</td>
                <td>Date&nbsp;To:   <input type="text" id="date2" name="date2" size="10">	</td>
                <td><button  type="submit"  class="btn btn-lg btn-danger">Exécuter</button></td>
              	</tr>
              </form> 
				


            </tbody>
          </table>
		</div>
     </div>
	
       
             
    </div> <!-- /container -->


  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>