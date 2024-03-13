<?php

/**
 * Call a constant with: constant("DIRECT_LENS_URL")
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

// DIRECT LENS
if($isOnProductionEnv) {
    define('DIRECT_LENS_URL', 'https://direct-lens.com');
} else if($isOnStagingEnv) {
    define('DIRECT_LENS_URL', 'https://staging.direct-lens.com');
} else {
    define('DIRECT_LENS_URL', 'http://localhost:8080');
}