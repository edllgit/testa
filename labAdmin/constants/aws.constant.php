<?php

/**
 * Call a constant with: constant("AWS_S3_BUCKET")
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

// S3
define('AWS_S3_USER_ACCESS_KEY', getenv("AWS_S3_USER_ACCESS_KEY"));
define('AWS_S3_USER_SECRET_KEY', getenv("AWS_S3_USER_SECRET_KEY"));

if($isOnProductionEnv) {
    define('AWS_S3_BUCKET', 'direct-lens-public');
} else if($isOnStagingEnv) {
    define('AWS_S3_BUCKET', 'direct-lens-public-staging');
}

// SES
define('AWS_SES_USER_ACCESS_KEY', getenv("AWS_SES_USER_ACCESS_KEY"));
define('AWS_SES_USER_SECRET_KEY', getenv("AWS_SES_USER_SECRET_KEY"));