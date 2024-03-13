<?php

/**
 * Call a constant with: constant("FTP_WINDOWS_VM")
 */
 
  
 define('OVG_LAB_FTP', '47.100.11.65');
 define('FTP_USER_OVG_LAB','U56yKp1a');
 define('FTP_PASSWORD_OVG_LAB','w9H6n3xy');

// IPs
define("FTP_WINDOWS_VM", getenv('FTP_WINDOWS_VM')); // App's Windows VM IP
define("GODADDY_FTP", getenv('GODADDY_FTP'));
define("GKB_FTP", getenv('GKB_FTP'));
define("SWISSCOAT_FTP", getenv('SWISSCOAT_FTP'));
define("HKO_FTP", getenv('HKO_FTP'));
define("OVG_LAB_FTP", getenv('OVG_LAB_FTP'));

// TODO - Define whos IP this is
define("UNKNOWN_FTP_SERVER_210", getenv('UNKNOWN_FTP_SERVER_210'));
define("UNKNOWN_FTP_SERVER_72", getenv('UNKNOWN_FTP_SERVER_72'));

// HKO
define('FTP_USER_HKO', getenv('FTP_USER_HKO'));
define('FTP_PASSWORD_HKO', getenv('FTP_PASSWORD_HKO')); // When connecting to FTP_WINDOWS_VM
define('FTP_PASSWORD_HKO_ALT', getenv('FTP_PASSWORD_HKO_ALT')); // When connecting to HKO_FTP

// OVG_LAB
define('FTP_USER_OVG_LAB', getenv('FTP_USER_OVG_LAB'));
define('FTP_PASSWORD_OVG_LAB', getenv('FTP_PASSWORD_OVG_LAB'));

// RCO
define('FTP_USER_RCO', getenv('FTP_USER_RCO'));
define('FTP_PASSWORD_RCO', getenv('FTP_PASSWORD_RCO'));

// Optipro HBC
define('FTP_USER_OPTIPRO_HBC', getenv('FTP_USER_OPTIPRO_HBC'));
define('FTP_PASSWORD_OPTIPRO_HBC', getenv('FTP_PASSWORD_OPTIPRO_HBC'));

// Optipro EDLL
define('FTP_USER_OPTIPRO_EDLL', getenv('FTP_USER_OPTIPRO_EDLL'));
define('FTP_PASSWORD_OPTIPRO_EDLL', getenv('FTP_PASSWORD_OPTIPRO_EDLL'));

// DLN
define('FTP_USER_DLN', getenv('FTP_USER_DLN'));
define('FTP_PASSWORD_DLN', getenv('FTP_PASSWORD_DLN'));

// KANDR
define('FTP_USER_KANDR', getenv('FTP_USER_KANDR'));
define('FTP_PASSWORD_KANDR', getenv('FTP_PASSWORD_KANDR'));

// SCT
define('FTP_USER_SCT', getenv('FTP_USER_SCT'));
define('FTP_PASSWORD_SCT', getenv('FTP_PASSWORD_SCT'));

// 0D013
define('FTP_USER_0D013', getenv('FTP_USER_0D013'));
define('FTP_PASSWORD_0D013', getenv('FTP_PASSWORD_0D013'));

// AGIASSON
define('FTP_USER_AGIASSON', getenv('FTP_USER_AGIASSON'));
define('FTP_PASSWORD_AGIASSON', getenv('FTP_PASSWORD_AGIASSON'));

// SYNC SHAPES
define('FTP_USER_SYNC_SHAPES', getenv('FTP_USER_SYNC_SHAPES'));
define('FTP_PASSWORD_SYNC_SHAPES', getenv('FTP_PASSWORD_SYNC_SHAPES'));

// VOT
define('FTP_USER_VOT', getenv('FTP_USER_VOT'));
define('FTP_PASSWORD_VOT', getenv('FTP_PASSWORD_VOT'));