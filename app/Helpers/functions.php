<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (!function_exists('isActive')) {
    /**
     * Set the active class to the current opened menu.
     *
     * @param string|array $route
     * @param string $className
     * @return string
     */
    function isActive($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }
        if (Route::currentRouteName() == $route) {
            return $className;
        }
        if (strpos(URL::current(), $route)) {
            return $className;
        }
    }
}


/**
 * This function return asset link based on link.php settings
 * @param string $type
 * @param string $key
 * @return type
 */
function assetLink(string $type, string $key)
{
    // if request if language, it should append & language to it
    return asset(config('installer' . $type . '.' . $key));
}


/**
 * Check if white label plugin is enabled
 * @return boolean
 */
function isWhiteLabelEnabled()
{
    return is_dir(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Whitelabel');
}


/**
 * customize validation rule for user registration with installer
 * @return array
 * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
 */
function validationForCreateUserInstaller()
{
    /* defined all the validation rules here to validate user registration form*/
    return [
        'f_name' => 'required',
        'l_name' => 'required'
    ];
}

/**
 * make sure this feature is enabled or not in env
 * customize user registration code according to application
 * @param $request
 * @return RedirectResponse|void
 */
function createUserForInstaller($request)
{
    try {
        $user = new \App\Models\User();
        $user->name = $request->f_name . ' ' . $request->l_name;
        $user->email = 'hiteshkr87570@gmail.com';
        $user->password = bcrypt('123456');
        $user->save();
        /* return true if user registration process is done or successfully registered */
        return redirect()->route('LaravelInstaller::license-code');
    } catch (Exception $exception) {
        Log::error($exception);
        /* return false if user registration process is failed to registered */
        return redirect()->back()->with('errors', 'Please entered right details');
    }
}

/**
 * write your form validation to validate the request for license code
 * make sure you enabled this feature in env
 * @return array
 * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
 */
function validationRulesForLicenseCode()
{
    /* defined all the validation rules here to validate license-code form*/
    return [
        'first_key' => 'required',
        'second_key' => 'required',
        'third_key' => 'required',
        'fourth_key' => 'required'
    ];
}

/**
 * write your custom logic here to validate license-code or serial key
 * make sure you enabled this feature in env
 * @return RedirectResponse
 */
function validateLicenseCodeOfUser()
{
    try {
        /* return true if user license code  is valid */
        return redirect()->route('LaravelInstaller::final');
    } catch (Exception $exception) {
        Log::error($exception);
        /* return false if user license code process is failed */
        return redirect()->back()->with('errors', 'Please entered right details');
    }
}

