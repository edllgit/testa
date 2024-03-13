<?php
 // connection to the db
include "../Connections/directlens.php";
include "../includes/getlang.php";

  //  $conn=mysql_connect(IPHOST,DBUSER,DBPASSWORD) or die(mysql_error());
  //  mysql_select_db(DATABASE) or die(mysql_error());

    $username = mysql_real_escape_string($_POST['user_id']); // $_POST is an array (not a function)
    // mysql_real_escape_string is to prevent sql injection

    $sql = "SELECT user_id FROM accounts WHERE user_id='".$username."'"; // Username must enclosed in two quotations

    $query = mysql_query($sql);

    if(mysql_num_rows($query) == 0)
    {
        echo('USER_AVAILABLE');
    }
    else
    {
        echo('USER_EXISTS');
    }
?>
