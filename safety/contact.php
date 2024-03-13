<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>
    
</head>

<body>
<div id="container">   
	<div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">Contact</div>            
			<div class="Subheader" style="height:600px;">
				<?php 	if ($mylang == 'lang_french') {  ?>                    
                    <p>Nous invitons les entreprises à contacter le représentant le plus près de leur région. Celui-ci ira vous rencontrer pour  
                    vous faire découvrir les différentes montures et accessoires qui conviennent à votre milieu de travail. De plus, il pourra 
                    évaluer vos besoins avec vous et vous faire bénéficier de prix de groupe.</p>
                    
                    <p>Pour les particuliers, il est préférable de vous rendre dans la succursale la plus près de chez vous. Un opticien sera sur
                    place pour s’assurer de votre confort visuel et une conseillère pourra vous guider dans le choix d’une monture adaptée à vos 
                    besoins. Elle pourra aussi vous conseiller sur un choix d’accessoires qui rendront votre lunette adaptée à votre travail.</p>
                                
                    <table>
                    	<tr>
                        	<td valign="top" width="300">
                            	<p><strong>Entrepôt de Trois-Rivières</strong></p>
                                <p>Tel : 1 819 840-4622 </p>                                   
								<p>5175, rue St-Joseph<br />
                                Trois-Rivières<br />
                                Québec</p>                              
                            </td>
                        	<td valign="top" width="300">
                            	<p><strong>Entrepôt de Drummondville</strong></p>
                                <p>Tel : 1 819 850-7245</p>                                 
                                <p>1242, boulevard Saint-Joseph<br />
                                Drummondville<br />
                                Québec</p>   
							</td>  
                        	<td valign="top" width="300">
                            	<p><strong>Entrepôt de Laval</strong></p>
                                <p>Tel : 1 450 934-0444</p>                                     
								<p>1776, boulevard le Corbusier<br />
                                Laval<br />
                                Québec</p>
                            </td>                                                      
                        </tr>
                    	<tr>
                        	<td valign="top" width="300">
                            	<p><strong>Entrepôt de Halifax</strong></p>
                                <p>Tel : 1 902 444-1082   </p>  
                                <p>103, Chain Lake Drive<br />
                                Halifax<br />
                                Nouvelle Écosse</p>                       
                            </td>
                        	<td valign="top" width="300">
                            	<p><strong>Entrepôt de St. Catherines</strong></p>
                                <p>Tel : 1 905 228-1887 </p>  
                                <p>277, Welland Avenue<br />
                                St. Catharines<br />
                                Ontario</p>
							</td>  
                        	<td valign="top" width="300">
                            	<p><strong>Siège social</strong></p>
                                <p>Tel : 1-877-570-3522</p> 
                                <p>240, boulevard des Forges bureau 203<br />
                                Trois-Rivières<br />
                                Québec G9A 2G8</p> 
                            </td>                                                      
                        </tr>                       
                    </table>           
                <?php  	}else{ ?>
                    <p>We invite companies to contact the nearest representative in your region. Our representative will make an appointment 
                    to show you the different frames and accessories that suit your workplace. In addition, the representative will evaluate 
                    your company's needs and provide a group rate to ensure savings.</p> 
                    
                    <p>For individuals, please go to your nearest Canadian Optical Wharehouse. Our opticians will ensure visual comfort and guide 
                    you in choosing a safety frame perfectly fits to your needs. The optician will also advise you on a range of accessories that 
                    will make your glasses suited to the type of work you do.</p>                                 
                    <table>
                    	<tr>
                        	<td valign="top" width="300">
                            	<p><strong>Trois-Rivieres Warehouse</strong></p>
                                <p>Tel : 1 819 840-4622 </p>                                   
								<p>5175, St-Joseph Street<br />
                                Trois-Rivieres<br />
                                Quebec</p>                              
                            </td>
                        	<td valign="top" width="300">
                            	<p><strong>Drummondville Warehouse</strong></p>
                                <p>Tel : 1 819 850-7245</p>                                 
                                <p>1242, Saint-Joseph Boulevard<br />
                                Drummondville<br />
                                Quebec</p>   
							</td>  
                        	<td valign="top" width="300">
                            	<p><strong>Laval Warehouse</strong></p>
                                <p>Tel : 1 450 934-0444</p>                                     
								<p>1776, le Corbusier Boulevard<br />
                                Laval<br />
                                Quebec</p>
                            </td>                                                      
                        </tr>
                    	<tr>
                        	<td valign="top" width="300">
                            	<p><strong>Halifax Warehouse</strong></p>
                                <p>Tel : 1 902 444-1082   </p>  
                                <p>103 Chain Lake Dr.<br />
                                Halifax<br />
                                Nova Scotia</p>                       
                            </td>
                        	<td valign="top" width="300">
                            	<p><strong>St. Catherines Warehouse</strong></p>
                                <p>Tel : 1 905 228-1887 </p>  
                                <p>277 Welland Avenue<br />
                                St. Catharines<br />
                                Ontario</p>
							</td>  
                        	<td valign="top" width="300">
                            	<p><strong>Head Office</strong></p>
                                <p>Tel : 1-877-570-3522</p> 
                                <p>240, des Forges Boulevard - office 203<br />
                                Trois-Rivieres<br />
                                Quebec G9A 2G8</p> 
                            </td>                                                      
                        </tr>                       
                    </table>   
                <?php 	} ?>  
                <br /><br />
                <a href="https://www.facebook.com/AvantagesSecurite"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/safety/images/facebook.gif" /></a>              

			</div><!--END Subheader-->             
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>