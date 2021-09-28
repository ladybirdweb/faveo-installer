<?php


namespace Faveo\Installer\Helpers;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class LicenseCodeManager
{

    /**
     * validate the user params with specified rules
     * @return string[]
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public static function validationRules(): array
    {
        return validationRulesForLicenseCode();
    }

    /**
     * return the user registration view
     * @return Application|Factory|View
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function showRegisterView()
    {
        return view('installer::license-code');
    }

    /**
     * write your logic here to get User
     * @param $request
     * @return Exception
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function checkLicenseCode($request)
    {
        /* implementation of this function in helper to customize your code*/
        return validateLicenseCodeOfUser($request);
    }

}
