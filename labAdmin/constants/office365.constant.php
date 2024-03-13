<?php

/**
 * Call a constant with: constant("OFFICE365_SMTP_HOST")
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

// SMTP
define('OFFICE365_SMTP_HOST', "smtp.office365.com");
define('OFFICE365_SMTP_PORT', 587);
define('OFFICE365_SMTP_USERNAME', getenv("OFFICE365_SMTP_USERNAME")); // Sender account
define('OFFICE365_SMTP_PASSWORD', getenv("OFFICE365_SMTP_PASSWORD"));
