<?php
//MAIN CONFIG FILE OF PHP AUTO UPDATE SCRIPT. CAN BE EDITED MANUALLY OR GENERATED USING Extra Tools > Configuration Generator TAB IN PHP AUTO UPDATE SCRIPT DASHBOARD. THE FILE MUST BE INCLUDED IN YOUR SCRIPT BEFORE YOU PROVIDE IT TO USER.


//-----------BASIC SETTINGS-----------//

//The URL (without / at the end) where PHP Auto Update Script from /WEB directory is installed on your server. No matter how many products you want to install and/or update, a single installation is enough.
setAusInfo();
function setAusInfo()
{
	$autoUpdateDetails = parse_ini_file(dirname(__DIR__,2).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'faveoconfig.ini', false, INI_SCANNER_RAW);

	define("AUS_PRODUCT_KEY", $autoUpdateDetails['PRODUCT_KEY']);

	define("AUS_PRODUCT_ID", $autoUpdateDetails['PRODUCT_ID']);
}

define("AUS_ROOT_URL", "https://updates.faveohelpdesk.com");


//Connection timeout in seconds. If product can't connect to and/or receive data from updates server after this period, connection will be dropped. Rule of thumb: 1 second for each MB to download. For example, if ZIP archives with your products are 50 MB in size, set this value to 50. As most compressed products only contain several MB of data, the default value of 30 should be enough. Increasing connection timeout will also slightly increase server resources usage.
define("AUS_CONNECTION_TIMEOUT", 300);

//Notification to be displayed when connection to server can't be established. Other notifications will be automatically fetched from server.
define("AUS_NOTIFICATION_NO_CONNECTION", "Can't connect to updates server.");

//Notification to be displayed when ZipArchive class is missing on user's machine.
define("AUS_NOTIFICATION_ZIPARCHIVE_CLASS_MISSING", "ZipArchive class is missing on this server.");

//Notification to be displayed when extracting downloaded ZIP archive fails.
define("AUS_NOTIFICATION_ZIP_EXTRACT_ERROR", "Can't extract downloaded ZIP archive or write files.");

//Notification to be displayed when removing downloaded ZIP archive fails.
define("AUS_NOTIFICATION_ZIP_DELETE_ERROR", "Can't delete downloaded ZIP archive.");


//-----------ADVANCED SETTINGS-----------//


//When option set to "YES", downloaded ZIP archive will be automatically deleted after extracting files.
define("AUS_DELETE_EXTRACTED", "YES");


//-----------NOTIFICATIONS FOR DEBUGGING PURPOSES ONLY. SHOULD NEVER BE DISPLAYED TO END USER-----------//


define("AUS_CORE_NOTIFICATION_INVALID_ROOT_URL", "Configuration error: invalid root URL of PHP Auto Update Script installation");
define("AUS_CORE_NOTIFICATION_INVALID_PRODUCT_ID", "Configuration error: invalid product ID");
define("AUS_CORE_NOTIFICATION_INVALID_PRODUCT_KEY", "Configuration error: invalid product key");
define("APL_CORE_NOTIFICATION_INVALID_PERMISSIONS", "Configuration error: invalid root directory permissions");
define("AUS_CORE_NOTIFICATION_INACCESSIBLE_ROOT_URL", "Server error: impossible to establish connection to your PHP Auto Update Script installation");


//-----------SOME EXTRA STUFF. SHOULD NEVER BE REMOVED OR MODIFIED-----------//
define("AUS_DIRECTORY", __DIR__);
