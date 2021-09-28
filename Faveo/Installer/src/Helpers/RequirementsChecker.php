<?php

namespace Faveo\Installer\Helpers;

use Exception;
use Faveo\Installer\Http\Controllers\PHPController;
use Illuminate\Support\Facades\Log;

require_once __DIR__.'/../script/apl_core_configuration.php';;

class RequirementsChecker
{
    protected $extensionCheckForm;

    public function __construct($extensionCheckForm)
    {
        $this->extensionCheckForm = $extensionCheckForm;
    }


    public static function checkForFaveoDependenciesFile()
    {
        if (!file_exists(storage_path('faveo-dependencies.json'))) {
//            throw new \Exception(\Lang::get('lang.dependency_file_missing'));
            return false;
        }
        if (!is_readable(storage_path('faveo-dependencies.json')) || !is_writable(storage_path('faveo-dependencies.json'))) {
//            throw new \Exception(\Lang::get('lang.give_rwx_permission_to_dependency_file'));
            return false;
        }
        return true;
    }

    /**
     * Get the json content of dependencies
     */
    private function getDependenciesJson()
    {
        /*
         * storage_path is not working while you test with custom package or service provider
         *
         * */
        return file_get_contents(storage_path('faveo-dependencies.json'))??null;
    }


    /**
     * Validate PHP extentions for probe page and auto-update module
     * @param string $extensionCheckFrom Whether the request is from probe page or auto-update module
     * @return array
     */
    public function validatePHPExtensions(&$errorCount = 0)
    {
        try {
            $error = [];
            $requiredExtensions = json_decode($this->getDependenciesJson())->extensions;
            $this->validateRequiredExtensions($requiredExtensions->required, $error, $errorCount);
            $this->validateOptionalExtensions($requiredExtensions->optional, $error);

            return $error;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

    }

    /**
     * Extension that are required for Faveo to run
     * @param array $requiredExtensions Array of required extensions
     * @param array &$error Array of errors
     */
    private function validateRequiredExtensions(array $requiredExtensions, array &$error, int &$errorCount)
    {
        try {
            foreach ($requiredExtensions as $extension) {
                if (!extension_loaded($extension)) {
                    if ($this->extensionCheckForm == 'probe') {
                        $errorCount += 1;
                        array_push($error, ['extensionName' => $extension, 'key' => "required"]);
                    } else {
                        $extString = "$extension is not enabled<p>To enable this, please install the extension on your server and  update '" . php_ini_loaded_file() . "' to enable $extension </p>"
                            . '<a href="https://support.faveohelpdesk.com/show/how-to-enable-required-php-extension-on-different-servers-for-faveo-installation" target="_blank">How to install PHP extensions on my server?</a>';
                        throw new \Exception($extString);
                    }

                } else {
                    if ($this->extensionCheckForm == 'probe') {
                        array_push($error, ['extensionName' => $extension, 'key' => "no-error"]);
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * Extension that are optional for Faveo to run
     * @param array $requiredExtensions Array of required extensions
     * @param array &$error Array of errors
     */
    private function validateOptionalExtensions(array $requiredExtensions, array &$error)
    {
        try {
            foreach ($requiredExtensions as $extension) {
                if (!extension_loaded($extension)) {
                    if ($this->extensionCheckForm == 'probe') {
                        array_push($error, ['extensionName' => $extension, 'key' => "optional"]);
                    }

                } else {
                    if ($this->extensionCheckForm == 'probe') {
                        array_push($error, ['extensionName' => $extension, 'key' => "no-error"]);
                    }
                }
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function validateRequisites(&$errorCount = 0)
    {
        try {
            $requiredRequisites = json_decode($this->getDependenciesJson())->requisites;
            $arrayOfRequisites = [];
            foreach ($requiredRequisites as $requisite) {
                $requisiteDetails = $this->requisitesWithTheirStatus($arrayOfRequisites, $requisite, $errorCount);
            }
            return $requisiteDetails;

        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Gets the Name and status of the requisites for Faveo
     * @param array &$arrayOfRequisites Array with name and status
     * @param string $requisite The name of the requisite to be checked
     */
    private function requisitesWithTheirStatus(array &$arrayOfRequisites, $requisite, int &$errorCount)
    {
        try {
            switch ($requisite) {
                case 'Establish connection to Helpdesk License Manager':
                    $this->faveoLicenseManagerCheck($arrayOfRequisites, $errorCount);
                    break;

                case 'PHP Version':
                    $minPhpVersionRequired = json_decode($this->getDependenciesJson())->min_php_version;
                    $this->PhpVersionCheck($arrayOfRequisites, $errorCount, $minPhpVersionRequired);
                    break;

                case 'PHP exec function':
                    $this->execFunctionCheck($arrayOfRequisites, $errorCount);
                    break;

                case 'env':
                    if ($this->extensionCheckForm == 'probe') {
                        $this->dotEnvFileCheck($arrayOfRequisites, $errorCount);
                    }
                    break;

                case 'max_execution_time':
                    if ($this->extensionCheckForm == 'probe') {
                        $this->maxExecutionTimeCheck($arrayOfRequisites, $errorCount);
                    }
                    break;

                case 'allow_url_fopen':
                    if ($this->extensionCheckForm == 'probe') {
                        $this->allowUrlFopen($arrayOfRequisites, $errorCount);
                    }
                    break;

                case 'cURL':
                    $this->cURLcheck($arrayOfRequisites, $errorCount);
                    break;

                case 'app_url':
                    $this->appUrlcheck($arrayOfRequisites, $errorCount);

                    break;
                default:

                    break;
            }
            return $arrayOfRequisites;
        } catch (\Exception $e) {
            Log::error($e);
            throw new Exception($e->getMessage());
        }

    }

    /**
     * Check wheteher the connection with License Manager is successful or not
     *
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function faveoLicenseManagerCheck(array &$arrayOfRequisites, int &$errorCount)
    {
        if (function_exists('curl_init') === true) {
            $apl_connection_notifications = aplCheckConnection();
            $connectionString = 'Connection Successful';
            $connectionColor = 'green';
            if (!empty($apl_connection_notifications)) {

                $connectionString = "Connection Failed. {$apl_connection_notifications['notification_text']}Connection could not be established with Licensing server.";
                $connectionColor = 'red';
                $errorCount += 1;
                if ($this->extensionCheckForm == 'auto-update') {//
                    throw new \Exception($connectionString);
                }
            }
        } else {
            $connectionString = 'Connection Failed. cURL extension is not enabled on your server';
            $errorCount += 1;
            if ($this->extensionCheckForm == 'auto-update') {
                throw new \Exception($connectionString);
            }
        }

        array_push($arrayOfRequisites, ['extensionName' => 'Establish connection to Helpdesk License Manager', 'connection' => $connectionString, 'color' => $connectionColor, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    /**
     * Check the current PHP version is compatible or not for running Faveo
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function PhpVersionCheck(array &$arrayOfRequisites, int &$errorCount, $minPhpVersionRequired)
    {
        try {
            $versionColor = 'green';
            $versionString = phpversion();
            if (version_compare(phpversion(), $minPhpVersionRequired, '>=') != 1) {
                $versionColor = 'red';
                $errorCount += 1;
                $versionString = phpversion() . '. Please upgrade PHP Version to' . $minPhpVersionRequired . ' or greater version';
                if ($this->extensionCheckForm == 'auto-update') {//
                    throw new \Exception($versionString);
                }
            }
            array_push($arrayOfRequisites, ['extensionName' => 'PHP Version', 'connection' => $versionString, 'color' => $versionColor, 'errorCount' => $errorCount]);
            return $arrayOfRequisites;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * Check PHP exec function is enabled or not
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function execFunctionCheck(array &$arrayOfRequisites, int &$errorCount)
    {
        $execColor = 'green';
        $execString = 'exec function is enabled';
        if (!(new PHPController)->execEnabled()) {
            $execColor = '#F89C0D';
            $execString = 'exec function is not enabled. This is required for taking system backup. Please note system backup functionality will not work without it.';
            if ($this->extensionCheckForm == 'auto-update') {//
                throw new \Exception($execString);
            }
        }
        array_push($arrayOfRequisites, ['extensionName' => 'PHP exec function', 'connection' => $execString, 'color' => $execColor, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    private function checkCurl(&$curlError)
    {
        if (function_exists('curl_init') === true) {
            $ch = curl_init("https://billing.faveohelpdesk.com");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($ch);
            if (curl_error($ch)) {
                array_push($curlError, curl_error($ch));
            }
        } else {
            array_push($curlError, 'cURL is not executable');
        }
        return $curlError;
    }

    /**
     * Check .env exists or not
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function dotEnvFileCheck(array &$arrayOfRequisites, int &$errorCount)
    {
        $env = '../.env';
        $envFound = is_file($env);
        $envColor = 'green';
        $envString = 'Not found';
        if ($envFound) {
            $errorCount += 1;
            $envColor = 'red';
            $envString = "Yes Found. <p>Please delete .env file from your root directory.</p>";

        }
        array_push($arrayOfRequisites, ['extensionName' => '.env file', 'connection' => $envString, 'color' => $envColor, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    /**
     * Check maximum execution time
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function maxExecutionTimeCheck(array &$arrayOfRequisites, int &$errorCount)
    {
        $executionColor = 'green';
        $executionString = ini_get('max_execution_time') . " (Maximum execution time is as per requirement)";
        if ((int)ini_get('max_execution_time') < 120) {
            $executionColor = '#F89C0D';
            $executionString = ini_get('max_execution_time') . " (Maximum execution time is too low. Recommended execution time is 120 seconds)";
        }
        array_push($arrayOfRequisites, ['extensionName' => 'Maximum execution time', 'connection' => $executionString, 'color' => $executionColor, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    /**
     * Checks allow_url_enabled directive is enabled or not
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function allowUrlFopen(array &$arrayOfRequisites, int &$errorCount)
    {
        $color = 'green';
        $messsage = "Directive is enabled";
        if (!(int)ini_get('allow_url_fopen')) {
            $color = '#F89C0D';
            $messsage = "Directive is disabled (It is recommended to keep this ON as few features in the system are dependent on this)";
        }
        array_push($arrayOfRequisites, ['extensionName' => 'Allow url fopen', 'connection' => $messsage, 'color' => $color, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    /**
     * Checks cURL is enabled or not
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function cURLcheck(array &$arrayOfRequisites, int &$errorCount)
    {
        $curlColor = 'green';
        $curlString = 'Working fine';
        $curlError = [];
        $hasCurlError = $this->checkCurl($curlError);
        if (count($curlError) > 0) {
            $errorCount += 1;
            $curlColor = "red";
            $curlString = $curlError[0];
            if ($this->extensionCheckForm == 'auto-update') {//
                throw new \Exception($curlString);
            }
        }
        array_push($arrayOfRequisites, ['extensionName' => 'cURL exceution', 'connection' => $curlString, 'color' => $curlColor, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    /**
     * Checks URL is valid or invalid
     * @param array $arrayOfRequisites Requisite details
     * @param int $errorCount The count of errors occured
     */
    private function appUrlcheck(array &$arrayOfRequisites, int &$errorCount)
    {
        $color = 'green';
        $infoString = 'Valid';
        if (!filter_var("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], FILTER_VALIDATE_URL)) {
            $errorCount += 1;
            $color = "red";
            $infoString = "Invalid URL found <p>Make sure your domain/IP doesn't contain any special character other than dash( '-' ) and dot ( '.' )<p>";
            if ($this->extensionCheckForm == 'auto-update') {//
                throw new \Exception($infoString);
            }
        }
        array_push($arrayOfRequisites, ['extensionName' => 'App URL', 'connection' => $infoString, 'color' => $color, 'errorCount' => $errorCount]);

        return $arrayOfRequisites;
    }

    /**
     * Validate directory permissions
     * @param string $basePath The base path of Faveo
     * @param int    &$errorCount Count of errors
     */
    public function validateDirectory($basePath, &$errorCount = 0)
    {
        try {
            $error = [];
            $this->validateStorageDirectory($basePath, $errorCount, $error);
            $this->validateBootstrapDirectory($basePath, $errorCount, $error);

            return $error;
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * Validate storage directory
     */
    private function validateStorageDirectory($basePath, &$errorCount, &$error)
    {
        try {
            $storagePermission = is_readable($basePath . DIRECTORY_SEPARATOR . 'storage') && is_writeable($basePath . DIRECTORY_SEPARATOR . 'storage');
            $storagePermissionColor = 'green';
            $storageMessage = "Read/Write";
            if (!$storagePermission) {
                $storagePermissionColor = 'red';
                $errorCount += 1;
                $storageMessage = "Directory should be readable and writable by your web server. Give preferred permissions as 755 for directory and 644 for files and owner as your web server user";
                if ($this->extensionCheckForm == 'auto-update') {//
                    throw new \Exception($storageMessage);
                }
            }
            array_push($error, ['extensionName' => $basePath .DIRECTORY_SEPARATOR. 'storage', 'color' => $storagePermissionColor, 'message' => $storageMessage, 'errorCount' => $errorCount]);
            return $error;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Validate bootstrap directory
     */
    private function validateBootstrapDirectory($basePath, &$errorCount, &$error)
    {
        try {
            $bootstrapPermission = is_readable($basePath . DIRECTORY_SEPARATOR . 'bootstrap') && is_writeable($basePath . DIRECTORY_SEPARATOR . 'bootstrap');
            $bootStrapPermissionColor = 'green';
            $bootStrapMessage = "Read/Write";
            if (!$bootstrapPermission) {
                $bootStrapPermissionColor = 'red';
                $errorCount += 1;
                $bootStrapMessage = "This directory should be readable and writable by your web server. Give preferred permissions as 755 for directory and 644 for files and owner as your web server user";
                if ($this->extensionCheckForm == 'auto-update') {//
                    throw new \Exception($bootStrapMessage);
                }
            }
            array_push($error, ['extensionName' => $basePath .DIRECTORY_SEPARATOR. 'bootstrap', 'color' => $bootStrapPermissionColor, 'message' => $bootStrapMessage, 'errorCount' => $errorCount]);
            return $error;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }


}
