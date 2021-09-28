<?php


namespace Faveo\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Faveo\Installer\Helpers\LicenseCodeManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class LicenseCodeController extends Controller
{
    protected $LicenseCodeManager;

    /**
     * LicenseCodeController constructor.
     * @param LicenseCodeManager $licenseCodeManager
     */
    public function __construct(LicenseCodeManager $licenseCodeManager)
    {
        $this->LicenseCodeManager = $licenseCodeManager;
    }

    /**
     * redirect to final step if license code verification is not enabled or return license code view
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function create()
    {
        if (!\config('installer.is_license_code_enabled')) {
            /* this feature is not enabled */
            return redirect()->route('LaravelInstaller::final');
        }
        /* return view if this feature is enabled */
        return $this->LicenseCodeManager->showRegisterView();
    }

    /**
     * @param LicenseCodeManager $request
     * @return Exception|\Faveo\Installer\Helpers\Exception
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function store(LicenseCodeManager $request)
    {
        /* main logic to validate license code of your application */
        $this->LicenseCodeManager->checkLicenseCode($request);
    }
}
