<?php


namespace Faveo\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use Faveo\Installer\Helpers\UserRegistrationManager;
use Faveo\Installer\Http\Requests\UserRegisterRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminRegistrationController extends Controller
{

    protected $UserRegistrationManager;

    /**
     * AdminRegistrationController constructor.
     * @param UserRegistrationManager $userRegistrationManager
     *
     */
    public function __construct(UserRegistrationManager $userRegistrationManager)
    {
        $this->UserRegistrationManager = $userRegistrationManager;
    }

    /**
     * show the user registration view
     * @return Application|Factory|View|RedirectResponse
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function create(Request $request)
    {
        if (!\config('installer.is_user_registration_enabled')) {
            /* this feature is not enabled */
            return redirect()->route('LaravelInstaller::license-code');
        }
        /* return view if this feature is enabled */
        return $this->UserRegistrationManager->showRegisterView();

    }

    /**
     * store user data
     * @param UserRegisterRequest $request
     * @return RedirectResponse|void
     * @author Hitesh Kumar <hitesh.kumar@ladybirdweb.com>
     */
    public function store(UserRegisterRequest $request)
    {
        return $this->UserRegistrationManager->createNewUser($request);
    }


}
