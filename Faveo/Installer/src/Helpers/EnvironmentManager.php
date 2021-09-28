<?php

namespace Faveo\Installer\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (!file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath()
    {
        return $this->envPath;
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath()
    {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request
     * @return string
     */
    public function saveFileWizard($default, $host, $port, $database, $dbusername, $dbpassword, $appUrl = null, $environment = null, $envParams)
    {
        $results = trans('installer_messages.environment.success');

        if (empty($host) || trim($host) == 'localhost' || trim($host) == 'localhost:8000') {
            $host = '127.0.0.1';
        }

        if (empty($appUrl)) {
            $appUrl = config('installer.app_url');
        }

        if (empty($port)) {
            $port = 3306;
        }

        $envFileData =
            'APP_NAME=\'' . 'Faveo:' . md5(uniqid()) . "'\n" .
            'APP_ENV=' . $envParams['environment'] . "\n" .
            'APP_KEY=' . 'base64:' . base64_encode(Str::random(32)) . "\n" .
            'APP_DEBUG=' . 'false' . "\n" .
            'APP_URL=' . $appUrl . "\n" .
            'APP_LOG_LEVEL=' . 'true' . "\n" .
            'APP_BUGSNAG=' . 'true' . "\n" .
            'DB_TYPE=' . $default . "\n" .
            'DB_HOST=' . $host . "\n" .
            'DB_PORT=' . $port . "\n" .
            'DB_DATABASE=' . $database . "\n" .
            'DB_USERNAME=' . $dbusername . "\n" .
            'DB_PASSWORD=' . str_replace('"', '\"', $dbpassword) . "\n\n" .
            'DB_ENGINE=' . 'MyISAM' . "\n" .
            'REDIS_HOST=' . 'null' . "\n" .
            'REDIS_PASSWORD=' . 'null' . "\n" .
            'REDIS_PORT=' . 'null' . "\n\n" .
            'MAIL_DRIVER=' . 'smtp' . "\n" .
            'MAIL_HOST=' . 'mailtrap.io' . "\n" .
            'MAIL_PORT=' . '2525' . "\n" .
            'MAIL_USERNAME=' . 'null' . "\n" .
            'MAIL_PASSWORD=' . 'null' . "\n" .
            'CACHE_DRIVER=' . 'file' . "\n" .
            'SESSION_DRIVER=' . 'file' . "\n" .
            'SESSION_COOKIE_NAME=' . 'faveo_' . rand(0, 10000) . "\n" .
            'QUEUE_DRIVER=' . 'sync' . "\n" .
            'FCM_SERVER_KEY=' . 'AIzaSyBJNRvyub-_-DnOAiIJfuNOYMnffO2sfw4' . "\n" .
            'FCM_SENDER_ID=' . '505298756081' . "\n" .
            'PROBE_PASS_PHRASE=' . md5(uniqid()) . "\n" .
            'REDIS_DATABASE=' . '0' . "\n" .
            'BROADCAST_DRIVER=' . 'pusher' . "\n" .
            'LARAVEL_WEBSOCKETS_ENABLED=' . 'false' . "\n" .
            'LARAVEL_WEBSOCKETS_PORT=' . '6001' . "\n" .
            'LARAVEL_WEBSOCKETS_HOST=' . '127.0.0.1' . "\n" .
            'LARAVEL_WEBSOCKETS_SCHEME=' . 'http' . "\n" .
            'PUSHER_APP_ID=' . Str::random(16) . "\n" .
            'PUSHER_APP_KEY=' . md5(uniqid()) . "\n" .
            'PUSHER_APP_SECRET=' . md5(uniqid()) . "\n" .
            'PUSHER_APP_CLUSTER=' . 'mt1' . "\n" .
            'MIX_PUSHER_APP_KEY=' . '"${PUSHER_APP_KEY}"' . "\n" .
            'MIX_PUSHER_APP_CLUSTER=' . '"${PUSHER_APP_CLUSTER}"' . "\n" .
            'SOCKET_CLIENT_SSL_ENFORCEMENT=' . 'false' . "\n" .
            'LARAVEL_WEBSOCKETS_SSL_PASSPHRASE=' . 'null' . "\n" .
            'LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT=' . 'null' . "\n" .
            'LARAVEL_WEBSOCKETS_SSL_LOCAL_PK=' . 'null' . "\n" . "\n" .
            'AWS_ACCESS_KEY_ID=' . $envParams['aws_access_key_id'] . "\n" .
            'AWS_ACCESS_KEY=' . $envParams['aws_access_key'] . "\n" .
            'AWS_DEFAULT_REGION=' . $envParams['aws_default_region'] . "\n" .
            'AWS_BUCKET=' . $envParams['aws_bucket'] . "\n" .
            'AWS_ENDPOINT=' . $envParams['aws_endpoint'] . "\n" .
            'DEFAULT_LANGUAGE=' . $envParams['language'] . "\n";

        try {
            file_put_contents($this->envPath, $envFileData);
//            if (!empty($_SERVER['APP_ENV'] ?? null) && $_SERVER['APP_ENV'] != 'testing') {
            Artisan::call('config:cache');
//            }
        } catch (Exception $e) {
            Log::error($e);
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
