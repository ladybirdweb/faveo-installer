<?php
//MAIN CONFIG FILE OF AUTO PHP LICENSER. CAN BE EDITED MANUALLY OR GENERATED USING Extra Tools > Configuration Generator TAB IN AUTO PHP LICENSER DASHBOARD. THE FILE MUST BE INCLUDED IN YOUR SCRIPT BEFORE YOU PROVIDE IT TO USER.


//-----------BASIC SETTINGS-----------//
setAplInfo();
function setAplInfo()
{
	$salt = parse_ini_file(__DIR__.'../../Storage/faveoconfig.ini');
	//Random salt used for encryption. It should contain random symbols (16 or more recommended) and be different for each application you want to protect. Cannot be modified after installing script.
	 define("APL_SALT", $salt['APL_SALT']);

	//Unique numeric ID of product that needs to be licensed. Can be obtained by going to Products > View Products tab in Auto PHP Licenser dashboard and selecting product to be licensed. At the end of URL, you will see something like products_edit.php?product_id=NUMBER, where NUMBER is unique product ID. Cannot be modified after installing script.
	define("APL_PRODUCT_ID", $salt['PRODUCT_ID']);
}

//The URL (without / at the end) where Auto PHP Licenser from /WEB directory is installed on your server. No matter how many applications you want to protect, a single installation is enough.
define("APL_ROOT_URL", "https://license.faveohelpdesk.com");


//Time period (in days) between automatic license verifications. The lower the number, the more often license will be verified, but if many end users use your script, it can cause extra load on your server. Available values are between 1 and 365. Usually 7 or 14 days are the best choice.
define("APL_DAYS", 5);

//Place to store license signature and other details. "DATABASE" means data will be stored in MySQL database (recommended), "FILE" means data will be stored in local file. Only use "FILE" if your application doesn't support MySQL. Otherwise, "DATABASE" should always be used. Cannot be modified after installing script.
define("APL_STORAGE", "DATABASE");

//Name of table (will be automatically created during installation) to store license signature and other details. Only used when "APL_STORAGE" set to "DATABASE". The more "harmless" name, the better. Cannot be modified after installing script.
define("APL_DATABASE_TABLE", "faveo_license");

//Name and location (relative to directory where "apl_core_configuration.php" file is located, cannot be moved outside this directory) of file to store license signature and other details. Can have ANY name and extension. The more "harmless" location and name, the better. Cannot be modified after installing script. Only used when "APL_STORAGE" set to "FILE" (file itself can be safely deleted otherwise).
define("APL_LICENSE_FILE_LOCATION", "signature/license.key.example");

//Notification to be displayed when connection to server can't be established. Other notifications will be automatically fetched from server.
define("APL_NOTIFICATION_NO_CONNECTION", "Can't connect to licensing server.");

//Notification to be displayed when response received from server is invalid. Other notifications will be automatically fetched from server.
define("APL_NOTIFICATION_INVALID_RESPONSE", "Invalid server response.");

//Notification to be displayed when updating database fails. Only used when APL_STORAGE set to DATABASE.
define("APL_NOTIFICATION_DATABASE_WRITE_ERROR", "Can't write to database.");

//Notification to be displayed when updating license file fails. Only used when APL_STORAGE set to FILE.
define("APL_NOTIFICATION_LICENSE_FILE_WRITE_ERROR", "Can't write to license file.");

//Notification to be displayed when installation wizard is launched again after script was installed.
define("APL_NOTIFICATION_SCRIPT_ALREADY_INSTALLED", "Script is already installed (or database not empty).");

//Notification to be displayed when license could not be verified because license is not installed yet or corrupted.
define("APL_NOTIFICATION_LICENSE_CORRUPTED", "License is not installed yet or corrupted.");

//Notification to be displayed when license verification does not need to be performed. Used for debugging purposes only, should never be displayed to end user.
define("APL_NOTIFICATION_BYPASS_VERIFICATION", "No need to verify");


//-----------ADVANCED SETTINGS-----------//


//Secret key used to verify if configuration file included in your script is genuine (not replaced with 3rd party files). It can contain any number of random symbols and should be different for each application you want to protect. You should also change its name from "APL_INCLUDE_KEY_CONFIG" to something else, let's say "MY_CUSTOM_SECRET_KEY"
define("APL_INCLUDE_KEY_CONFIG", "515548a88b8ab641");

//IP address of your Auto PHP Licenser installation. If IP address is set, script will always check if "APL_ROOT_URL" resolves to this IP address (very useful against users who may try blocking or nullrouting your domain on their servers). However, use it with caution because if IP address of your server is changed in future, old installations of protected script will stop working (you will need to update this file with new IP and send updated file to end user). If you want to verify licensing server, but don't want to lock it to specific IP address, you can use APL_ROOT_NAMESERVERS option (because nameservers change is unlikely).
define("APL_ROOT_IP", "");

//Nameservers of your domain with Auto PHP Licenser installation (only works with domains and NOT subdomains). If nameservers are set, script will always check if "APL_ROOT_NAMESERVERS" match actual DNS records (very useful against users who may try blocking or nullrouting your domain on their servers). However, use it with caution because if nameservers of your domain are changed in future, old installations of protected script will stop working (you will need to update this file with new nameservers and send updated file to end user). Nameservers should be formatted as an array. For example: array("ns1.phpmillion.com", "ns2.phpmillion.com"). Nameservers are NOT CAse SensitIVE.
//define("APL_ROOT_NAMESERVERS", array()); //ATTENTION! THIS FEATURE ONLY WORKS WITH PHP 7.0 AND HIGHER, ONLY UNCOMMENT THIS LINE IF PROTECTED SCRIPT WILL RUN ON COMPATIBLE SERVER!

//When option set to "YES", script files and MySQL data will be deleted when illegal usage is detected. This is very useful against users who may try using pirated software; if someone shares his license with 3rd parties (by sending it to a friend, posting on warez forums, etc.) and you cancel this license, Auto PHP Licenser will try to delete all script files and any data in MySQL database for everyone who uses cancelled license. For obvious reasons, data will only be deleted if license is cancelled. If license is invalid or expired, no data will be modified. Use at your own risk!
define("APL_DELETE_CANCELLED", "NO");

//When option set to "YES", script files and MySQL data will be deleted when cracking attempt is detected. This is very useful against users who may try cracking software; if some unauthorized changes in core functions are detected, Auto PHP Licenser will try to delete all script files and any data in MySQL database. Use at your own risk!
define("APL_DELETE_CRACKED", "NO");

//When option set to "YES", ALL files and MySQL data will be deleted when cracking attempt is detected. This option only works when APL_DELETE_CRACKED is set to "YES". The main difference between standard (used by default when APL_DELETE_CRACKED is set to "YES") and GOD mode is that GOD mode deletes not only script files, but also all other files from user's website (including other scripts, custom user files, etc.)
define("APL_GOD_MODE", "NO");


//-----------NOTIFICATIONS FOR DEBUGGING PURPOSES ONLY. SHOULD NEVER BE DISPLAYED TO END USER-----------//


define("APL_CORE_NOTIFICATION_INVALID_SALT", "Configuration error: invalid or default encryption salt");
define("APL_CORE_NOTIFICATION_INVALID_ROOT_URL", "Configuration error: invalid root URL of Auto PHP Licenser installation");
define("APL_CORE_NOTIFICATION_INVALID_PRODUCT_ID", "Configuration error: invalid product ID");
define("APL_CORE_NOTIFICATION_INVALID_VERIFICATION_PERIOD", "Configuration error: invalid license verification period");
define("APL_CORE_NOTIFICATION_INVALID_STORAGE", "Configuration error: invalid license storage option");
define("APL_CORE_NOTIFICATION_INVALID_TABLE", "Configuration error: invalid MySQL table name to store license signature");
define("APL_CORE_NOTIFICATION_INVALID_LICENSE_FILE", "Configuration error: invalid license file location (or file not writable)");
define("APL_CORE_NOTIFICATION_INVALID_ROOT_IP", "Configuration error: invalid IP address of your Auto PHP Licenser installation");
define("APL_CORE_NOTIFICATION_INVALID_ROOT_NAMESERVERS", "Configuration error: invalid nameservers of your Auto PHP Licenser installation");
define("APL_CORE_NOTIFICATION_INVALID_DNS", "License error: actual IP address and/or nameservers of your Auto PHP Licenser installation don't match specified IP address and/or nameservers");


//-----------SOME EXTRA STUFF. SHOULD NEVER BE REMOVED OR MODIFIED-----------//
define("APL_DIRECTORY", __DIR__);
