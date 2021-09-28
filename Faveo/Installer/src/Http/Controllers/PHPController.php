<?php

namespace Faveo\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PHPController extends Controller
{
    /**
     * Check if exec() function is available
     *
     * @return boolean
     */
    public function execEnabled() {
        try {
            // make a small test
            return function_exists('exec') && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))));
        } catch (\Exception $ex) {
            return false;
        }
    }

    protected function getPHPBinPath()
    {
        $paths = [
            '/usr/bin/php',
            '/usr/local/bin/php',
            '/bin/php',
            '/usr/bin/php7',
            '/usr/bin/php7.3',
            '/usr/bin/php73',
            '/opt/plesk/php/7.3/bin/php',
        ];
        // try to detect system's PHP CLI
        if($this->execEnabled()) {
            try {
                $paths = array_unique(array_merge($paths, explode(" ", exec("whereis php"))));
            } catch (\Exception $e) {
                // @todo: system logging here
                echo $e->getMessage();
            }
        }
        // validate detected / default PHP CLI
        // Because array_filter() preserves keys, you should consider the resulting array to be an associative array even if the original array had integer keys for there may be holes in your sequence of keys. This means that, for example, json_encode() will convert your result array into an object instead of an array. Call array_values() on the result array to guarantee json_encode() gives you an array.
        $paths = array_values(array_filter($paths, function($path) {
            try{
                return is_executable($path) && preg_match("/php[0-9\.a-z]{0,3}$/i", $path);
            } catch(\Exception $e){
                // in case of open_basedir, just throw skip it
                return true;
            }
        }));

        return $paths;
    }

    public function checkPHPExecutablePath(Request $request)
    {
        try{
            $path = $request->get('path');
            $cronCommandCopiedMessage    = trans('lang.cron-command-copied');
            $cronCommandNotCopiedMessage = trans('lang.cron-command-not-copied');

            if (!file_exists($path) || !is_executable($path)) {
                return $cronCommandNotCopiedMessage . ' ' . trans('lang.invalid-php-path');
            }

            if($this->execEnabled()) {
                $execScript = $path . " ".public_path('cron-test.php');
                $version = exec($execScript, $output);
                return (version_compare($version, '7.3', '>=') == 1) ? $cronCommandCopiedMessage . ' ' . trans('lang.valid-php-path'): trans('lang.cron-command-copied') . ' ' . trans('lang.invalid-php-version-or-path');
            }

            return trans('lang.cron-command-copied') . ' ' . trans('lang.please_enable_php_exec_for_cronjob_check');
        } catch(\Exception $e){
            Log::error($e);
            return $e;
        }
    }
}
