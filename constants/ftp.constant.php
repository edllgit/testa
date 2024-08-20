<?php

/**
 * Call a constant with: constant("FTP_WINDOWS_VM")
 */
 
 define('OVG_LAB_FTP', '47.100.11.65');
 define('FTP_USER_OVG_LAB','U56yKp1a');
 define('FTP_PASSWORD_OVG_LAB','w9H6n3xy');
 
// PROCREA

 define('PROCREA_FTP', 'ftp.daioptical.com');
 define('FTP_USER_PROCREA','edll');
 define('FTP_PASSWORD_PROCREA','10o8BS98dFcn');
 
 
 /*
 define('FTP_WINDOWS_VM', '192.168.2.60');
 define('FTP_USER_OPTIPRO_EDLL','Administrateur');
 define('FTP_PASSWORD_OPTIPRO_EDLL','{REF:P@I:01D1877BF5F6814A80FB412850FB3F5B}');  */

// IPs
define("FTP_WINDOWS_VM", getenv('FTP_WINDOWS_VM')); // App's Windows VM IP
define("GODADDY_FTP", getenv('GODADDY_FTP'));
define("GKB_FTP", getenv('GKB_FTP'));
define("SWISSCOAT_FTP", getenv('SWISSCOAT_FTP'));
define("HKO_FTP", getenv('HKO_FTP'));
define("OVG_LAB_FTP", getenv('OVG_LAB_FTP'));
define("PROCREA_FTP", getenv('PROCREA_FTP'));



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


//PROCREA
define('FTP_USER_PROCREA', getenv('FTP_USER_PROCREA'));
define('FTP_PASSWORD_PROCREA', getenv('FTP_PASSWORD_PROCREA'));

// RCO
define('FTP_USER_RCO', getenv('FTP_USER_RCO'));
define('FTP_PASSWORD_RCO', getenv('FTP_PASSWORD_RCO'));

//echo '<br>'.var_dump(getenv('FTP_USER_RCO'));

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

/*

// Vérifiez si la constante est définie et affichez sa valeur
if (defined('GODADDY_FTP')) {
    // Utilisation de echo pour afficher la valeur
    echo "La valeur de GODADDY_FTP est : " . GODADDY_FTP;

    // Ou utilisation de var_dump pour afficher la valeur et le type
    var_dump(GODADDY_FTP);
} else {
    echo "La constante GODADDY_FTP n'est pas définie.";
}

// Vérifiez si la constante est définie et affichez sa valeur
if (defined('FTP_USER_AGIASSON')) {
    // Utilisation de echo pour afficher la valeur
    echo "La valeur de FTP_USER_AGIASSON est : " . FTP_USER_AGIASSON;

    // Ou utilisation de var_dump pour afficher la valeur et le type
    var_dump(FTP_USER_AGIASSON);
} else {
    echo "La constante GODADDY_FTP n'est pas définie.";
}


// Vérifiez si la constante est définie et affichez sa valeur
if (defined('FTP_PASSWORD_AGIASSON')) {
    // Utilisation de echo pour afficher la valeur
    echo "La valeur de FTP_PASSWORD_AGIASSON est : " . FTP_PASSWORD_AGIASSON;

    // Ou utilisation de var_dump pour afficher la valeur et le type
    var_dump(FTP_PASSWORD_AGIASSON);
} else {
    echo "La constante FTP_PASSWORD_AGIASSON n'est pas définie.";


}*/