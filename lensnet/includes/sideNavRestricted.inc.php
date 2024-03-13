<div id="menu">

  <ul >
    <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
    <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
    <li><a href='order_history.php'><?php echo $lbl_btn_orderhist;?></a></li>
  </ul>
		  <br>
		  <br />
		 
		  <ul >
            <li ><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul> <br /><?php  include("../translator.php"); ?><br>
		  </div>
		  <!-- Start AliveChat Live Site Monitor Code -->
	<script language="javascript">
		function wsa_include_js(){
			var js = document.createElement('script');
			js.setAttribute('language', 'javascript');
			js.setAttribute('type', 'text/javascript');
			js.setAttribute('src','https://www.websitealive8.com/2326/Visitor/vTracker_v2.asp?websiteid=0&groupid=2326');
			document.getElementsByTagName('head').item(0).appendChild(js);
		}
		window.onload = wsa_include_js;
	</script>
	<!-- End AliveChat Live Site Monitor Code -->