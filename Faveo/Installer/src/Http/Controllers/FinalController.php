<?php

namespace Faveo\Installer\Http\Controllers;

use Faveo\Installer\Events\LaravelInstallerFinished;
use Faveo\Installer\Helpers\EnvironmentManager;
use Faveo\Installer\Helpers\FinalInstallManager;
use Faveo\Installer\Helpers\InstalledFileManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;


class FinalController extends Controller
{
    /**
     * @param InstalledFileManager $fileManager
     * @param FinalInstallManager $finalInstall
     * @param EnvironmentManager $environment
     * @return Application|Factory|View|RedirectResponse
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        try {
            $finalMessages = $finalInstall->runFinal();
            $finalStatusMessage = $fileManager->update();
            $finalEnvFile = $environment->getEnvContent();

            $this->setEnvironmentValue('APP_ENV', 'production');

            event(new LaravelInstallerFinished());

            return view('installer::finalise-installer', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));

        } catch (\Exception $exception) {
            Log::error($exception);
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @param $envKey
     * @param $envValue
     */
    private function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

}
