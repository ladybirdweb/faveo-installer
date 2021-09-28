<?php

namespace Faveo\Installer\Http\Controllers;

use Exception;
use Faveo\Installer\Helpers\RequirementsChecker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class RequirementsController extends Controller
{
    /**
     * getting the system requirement
     * @return Application|Factory|\Illuminate\Contracts\View\View
     * @throws Exception
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function requirements()
    {
        $errorCount = 0;

        $requirements = new RequirementsChecker("probe");

        /* permission block  */
        $permissionBlock = $requirements->validateDirectory(base_path(), $errorCount);

        /* requirement block */
        $requisites = $requirements->validateRequisites($errorCount);

        /* PHP extension check block */
        $phpExtension = $requirements->validatePHPExtensions($errorCount);

        /* mod rewrite status block */
        $apacheModules = function_exists('apache_get_modules') ? (int)in_array('mod_rewrite', apache_get_modules()) : 2;

        if ($apacheModules == 2) {
            $rewriteStatusString = "Unable to detect";
        } elseif (!$apacheModules) {
            $rewriteStatusString = "OFF";
        } else {
            $rewriteStatusString = "ON";
        }

        /* safe-url status */
        $safeUrl = $this->checkUserFriendlyUrl();

        if ($safeUrl === true) {
            $safeUrlString = 'ON';
        } elseif ($safeUrl === false) {
            $safeUrlString = "OFF (If you are using apache, make sure 'AllowOverride' is set to 'All' in apache configuration)";
        } else {
            $safeUrlString = "Unable to Detect";
        }

        $modRewrite = ['rewriteEngine' => $rewriteStatusString, 'safeUrl' => $safeUrlString];

        return View::make('installer::server-requirement',compact('permissionBlock', 'requisites', 'phpExtension', 'modRewrite', 'apacheModules', 'errorCount'));
    }


    /**
     * Checks if user friendly url is on.
     * @return bool|null
     * @internal it curls for pre-license page, if it gets a 404, it returns false.
     * If any exception happens or curl is not found, it returns null
     */
    private function checkUserFriendlyUrl()
    {
        if (function_exists('curl_init') === true) {
            try {
                $ch = curl_init($this->getLicenseUrl());
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
                curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return $httpcode != 404;
            } catch (Exception $e) {
                Log::error($e);
                return null;
            }
        }
        return null;
    }

    private function getLicenseUrl()
    {
        if (env('APP_ENV') == 'testing') {
            return 'https://localhost/package/public/';
        }

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https://";
        else
            $url = "http://";
        /*  Append the host(domain name, ip) to the URL. */
        $url .= $_SERVER['HTTP_HOST'];
        /* Append the requested resource location to the URL   */
        $url .= $_SERVER['REQUEST_URI'];
        return $url;
    }


}
