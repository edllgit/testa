<?php
/*
 * Generated configuration file
 * Generated by: phpMyAdmin 4.9.10 setup script
 * Date: Wed, 27 Jul 2022 13:54:02 +0000
 */

/* Servers configuration */
$i = 0;
$mysql_host = getenv("MYSQL_HOST");

/* Server: Instance AWS [1] */
$i++;
$cfg['Servers'][$i]['verbose'] = 'Instance AWS';
$cfg['Servers'][$i]['host'] = $mysql_host;
$cfg['Servers'][$i]['port'] = 3306;
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = '';

/* End of servers configuration */

$cfg['blowfish_secret'] = 'gBhC[05*-F0FQnx^MbFBN"i[g;k|Ybq';
$cfg['DefaultLang'] = 'en';
$cfg['ServerDefault'] = 1;
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
?>