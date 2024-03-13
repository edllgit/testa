<?php 
require_once(__DIR__.'/../constants/url.constant.php');
require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<script src="formFunctions.js" type="text/javascript"></script>

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
<script language="JavaScript" type="text/javascript">
	
	function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
  
 function copyRE() { //v2.0
document.stock.MATERIAL2.selectedIndex=document.stock.MATERIAL.selectedIndex;

document.stock.INDEX2.length=0;

document.stock.INDEX2.options[document.stock.INDEX2.options.length]=new Option("Select an Index","");
		
for(i=1;i<document.stock.INDEX.length;i++){
document.stock.INDEX2.options[document.stock.INDEX2.options.length]=new Option(document.stock.INDEX.options[i].value,document.stock.INDEX.options[i].value);
					}
		
document.stock.INDEX2.selectedIndex=document.stock.INDEX.selectedIndex;

document.stock.COATING2.length=0;
document.stock.COATING2.options[document.stock.COATING2.options.length]=new Option("Select a Coating","");
		
for(i=1;i<document.stock.COATING.length;i++){

						if (document.stock.COATING.options[i].value=='UC')
							var optionText="Un-Coated";
							
						if (document.stock.COATING.options[i].value=='AR')
							var optionText="Anti-Reflective";
	
						if (document.stock.COATING.options[i].value=='SR')
							var optionText="Scratch Resistant";

						if (document.stock.COATING.options[i].value=='SR AR')
							var optionText="Scratch Resistant and Anti-Reflective";
							
document.stock.COATING2.options[document.stock.COATING2.options.length]=new Option(optionText,document.stock.COATING.options[i].value);
				}

document.stock.COATING2.selectedIndex=document.stock.COATING.selectedIndex;

document.stock.SPHERE2.length=0;

document.stock.SPHERE2.options[document.stock.SPHERE2.options.length]=new Option("Select","");
		
for(i=1;i<document.stock.SPHERE.length;i++){
document.stock.SPHERE2.options[document.stock.SPHERE2.options.length]=new Option(document.stock.SPHERE.options[i].value,document.stock.SPHERE.options[i].value);
					}
					
document.stock.SPHERE2.selectedIndex=document.stock.SPHERE.selectedIndex;

document.stock.CYLINDER2.length=0;

document.stock.CYLINDER2.options[document.stock.CYLINDER2.options.length]=new Option("Select","");
		
for(i=1;i<document.stock.CYLINDER.length;i++){
document.stock.CYLINDER2.options[document.stock.CYLINDER2.options.length]=new Option(document.stock.CYLINDER.options[i].value,document.stock.CYLINDER.options[i].value);
					}
					
document.stock.CYLINDER2.selectedIndex=document.stock.CYLINDER.selectedIndex;
}

function validate(theForm)
{

  if (theForm.TRAY.value== "")
  {
    alert("You must enter a value in the \"Tray Reference\" field.");
    theForm.TRAY.focus();
    return (false);
  }
 }
	
</script>
</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="<?php echo constant('DIRECT_LENS_URL'); ?>/safety/design_images/ifc-masthead.jpg" width="1050" height="175" alt="IFC Optic CLub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php   
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="stock.php" method="post" name="stock" id="stock" onSubmit="return validate(this)">
      <div class="header">Order Stock Lenses - By Tray </div>
	  <div class="loginText">User: <?php print $_SESSION["sessionUser_Id"];?></div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

              <tr >
                <td colspan="2" align="right" bgcolor="#17A2D2" class="formCellNosides">&nbsp;</td>
                </tr>
              <tr >
                <td width="95" align="right" class="formCellNosides">Tray Reference:</td>
                <td width="541" class="formCellNosides"><input name="TRAY" type="text" id="TRAY" value="<?php print $_POST[TRAY];?>" size="10"></td>
                </tr>
            </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr>
                <td colspan="2" align="center"  class="formCellNosides">&nbsp;</td>
                <td align="center"  class="formCellNosides">MATERIAL</td>
                <td align="left" class="formCellNosides">INDEX</td>
                <td align="left" class="formCellNosides">COATING</td>
                <td align="left" class="formCellNosides">SPHERE</td>
                <td align="left" class="formCellNosides">CYLINDER</td>
              </tr>
              <tr>
                <td align="center"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/safety/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
                <td align="right"  class="formCell">R.E.</td>
                <td  class="formCellNosides"><select name="MATERIAL" class="pullDownText" id="MATERIAL"  onChange="fetchIndex('getIndex.php','MATERIAL','INDEX','COATING','SPHERE','CYLINDER',this.value)">
                  <option value="" selected>Select a Material</option>
                  <option value="GL">Glass</option>
                  <option value="GH">Glass (High Index)</option>
                  <option value="PL">Plastic</option>
                  <option value="PH">Plastic (High Index)</option>
                  <option value="PY">Polycarbonate</option>
                </select></td>
                <td  class="formCellNosides"><select name="INDEX" class="pullDownText" id="INDEX" onChange="fetchCoating('getCoating.php','INDEX','COATING','SPHERE','CYLINDER',MATERIAL.value,this.value)">
                  <option value="">-</option>
                </select></td>
                <td   class="formCellNosides">
                  <select name="COATING" class="pullDownText" id="COATING" onChange="fetchSphere('getSphere.php','SPHERE','COATING','CYLINDER',MATERIAL.value,INDEX.value,this.value);">
                    <option value="">-</option>
                  </select>               </td>
                <td   class="formCellNosides"><select name="SPHERE" class="pullDownText" id="SPHERE" onChange="	fetchCylinder('getCylinder.php','CYLINDER','SPHERE',MATERIAL.value,INDEX.value,COATING.value,this.value)">
                  <option>-</option>
                </select></td>
                <td  class="formCellNosides"><select name="CYLINDER" class="pullDownText" id="CYLINDER">
                  <option value="">-</option>
                </select></td>
              </tr>
              <tr>
                <td colspan="2" align="right"class="formCell">L.E.</td>
                <td  class="formCellNosides"><select name="MATERIAL2" class="pullDownText" id="MATERIAL2"  onChange="fetchIndex('getIndex.php','MATERIAL2','INDEX2','COATING2','SPHERE2','CYLINDER2',this.value)">
                    <option value="" selected>Select a Material</option>
                    <option value="GL">Glass</option>
                    <option value="GH">Glass (High Index)</option>
                    <option value="PL">Plastic</option>
                    <option value="PH">Plastic (High Index)</option>
                    <option value="PY">Polycarbonate</option>
                </select></td>
                <td  class="formCellNosides"><select name="INDEX2" class="pullDownText" id="INDEX2" onChange="fetchCoating('getCoating.php','INDEX2','COATING2','SPHERE2','CYLINDER2',MATERIAL2.value,this.value)">
                    <option value="">-</option>
                </select></td>
                <td   class="formCellNosides"><select name="COATING2" class="pullDownText" id="COATING2" onChange="fetchSphere('getSphere.php','SPHERE2','COATING2','CYLINDER2',MATERIAL2.value,INDEX2.value,this.value);">
                    <option value="">-</option>
                  </select>                </td>
                <td   class="formCellNosides"><select name="SPHERE2" class="pullDownText" id="SPHERE2" onChange="	fetchCylinder('getCylinder.php','CYLINDER2','SPHERE2',MATERIAL2.value,INDEX2.value,COATING2.value,this.value)">
                    <option>-</option>
                </select></td>
                <td  class="formCellNosides"><select name="CYLINDER2" class="pullDownText" id="CYLINDER2">
                    <option value="">-</option>
                </select></td>
              </tr>
            </table>
		    <div align="center" style="margin:11px">&nbsp;
		      <input name="Submit" type="submit" class="formText" value="Search" tabindex="1">
		      <input name="from_form" type="hidden" id="from_form" value="yes">
		    </div>
		  </form> <?php 
	if ($_POST[from_form]=="yes"){
		$_POST[from_form]="false";
		include("includes/stockSearch.inc.php");}
		
	if ($_POST[fromTrayAdd]=="true"){
		$COUNT=$_SESSION["COUNT"]+1;

		$_SESSION["COUNT"]=$COUNT;
		$_SESSION["TRAY_REF"][$COUNT]=$_SESSION["TEMP_TRAY_REF"];

		$_SESSION["RE"][$COUNT]=$_POST[RE_RADIO];
		$_SESSION["LE"][$COUNT]=$_POST[LE_RADIO];
		$RE=$_SESSION["RE"][$COUNT];
		$LE=$_SESSION["LE"][$COUNT];
		
		$_SESSION["ITEM_NUMBER"]=$_SESSION["ITEM_NUMBER"]+1;
		
		include("includes/displayTray.inc.php");}
		
	elseif($_SESSION["ITEM_NUMBER"]!=0){
		include("includes/displayTray.inc.php");
		}
	?></td>
  </tr>
</table>

</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>
