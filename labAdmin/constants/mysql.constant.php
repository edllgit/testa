<?php

/**
 * Call a constant with: constant("MYSQL_HOST")
 */

$isOnProductionEnv = false;
$isOnStagingEnv = false;
$isOnLocalEnv = false;

if(getenv("ENV") === 'production') {
    $isOnProductionEnv = true;
}

if(getenv("ENV") === 'staging') {
    $isOnStagingEnv = true;
}

if(getenv("ENV") === 'development') {
    $isOnLocalEnv = true;
}


if($isOnProductionEnv) {
    define('MYSQL_VERBOSE', 'Instance AWS');
} else if($isOnStagingEnv) {
    define('MYSQL_VERBOSE', 'Staging Instant AWS');
} else {
    define('MYSQL_VERBOSE', 'localhost');
}

// MySQL Conn
define('MYSQL_HOST', getenv('MYSQL_HOST'));
define('MYSQL_PORT', 3306);
define('MYSQL_USER', getenv('MYSQL_USER'));
define('MYSQL_PASSWORD', getenv('MYSQL_PASSWORD'));

define('MYSQL_HOST_TEST', 'dlens3-test-mysql.ckwdqi2xheoy.us-east-1.rds.amazonaws.com');

// Requisitions
define('MYSQL_USER_REQUISITIONS', getenv('MYSQL_USER_REQUISITIONS'));
define('MYSQL_PASSWORD_REQUISITIONS', getenv('MYSQL_PASSWORD_REQUISITIONS'));

// DBs
define('MYSQL_DB_DIRECT_LENS', getenv('MYSQL_DB_DIRECT_LENS'));
define('MYSQL_DB_HBC', getenv('MYSQL_DB_HBC'));
define('MYSQL_DB_REQUISITIONS', getenv('MYSQL_DB_REQUISITIONS'));
define('MYSQL_DB_VISION_WONDERS', getenv('MYSQL_DB_VISION_WONDERS'));