<?php

namespace Tests\Unit;

use Faveo\Installer\FaveoInstallerServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Orchestra\Testbench\TestCase;

class DataBaseSetupEnviournmentTest extends TestCase
{

    protected $application;

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            FaveoInstallerServiceProvider::class,
        ];
    }

    public function test_db_environment_validation_errors()
    {
        $requiredFields = 3;
        $response = $this->post(route('LaravelInstaller::environment'))
            ->assertStatus(302);
        $response->assertSessionHas('error');
        $errors = Session::get('error');
        $this->assertEquals($requiredFields, count($errors));
    }

    public function test_db_host_name_validation_not_a_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_hostname' => 1233
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_hostname']);
        $this->assertEquals('The database hostname must be a string.', $errorMessage);
    }

    public function test_db_host_name_validation_length_is_more_then_50_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_hostname' => 'fmdsnfdsfndsfndnfdskfndskfndfkndskfndsnfkdsnfndskfndsnfkdsnfndsnfdfndkfndskfndkfndknfkdnfkdnkfndnfdknfkdnfkdfndkfndkfndknfkdnfkdnfkdnn vdnvkdnfijfeirurerierhefndfjndjfnjdnfiefnekfnekfneifne'
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_hostname']);
        $this->assertEquals('The database hostname must not be greater than 50 characters.', $errorMessage);
    }

    public function test_db_name_validation_not_a_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_name' => 1233,
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_name']);
        $this->assertEquals('The database name must be a string.', $errorMessage);
    }

    public function test_db_name_validation_length_is_more_then_50_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_name' => 'fmdsnfdsfndsfndnfdskfndskfndfkndskfndsnfkdsnfndskfndsnfkdsnfndsnfdfndkfndskfndkfndknfkdnfkdnkfndnfdknfkdnfkdfndkfndkfndknfkdnfkdnfkdnn vdnvkdnfijfeirurerierhefndfjndjfnjdnfiefnekfnekfneifne'
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_name']);
        $this->assertEquals('The database name must not be greater than 50 characters.', $errorMessage);
    }

    public function test_user_name_validation_not_a_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_username' => 1233
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_username']);
        $this->assertEquals('The database username must be a string.', $errorMessage);
    }

    public function test_user_name_validation_length_is_more_then_50_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_username' => 'fmdsnfdsfndsfndnfdskfndskfndfkndskfndsnfkdsnfndskfndsnfkdsnfndsnfdfndkfndskfndkfndknfkdnfkdnkfndnfdknfkdnfkdfndkfndkfndknfkdnfkdnfkdnn vdnvkdnfijfeirurerierhefndfjndjfnjdnfiefnekfnekfneifne'
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_username']);
        $this->assertEquals('The database username must not be greater than 50 characters.', $errorMessage);
    }

    public function test_password_validation_not_a_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_password' => 1233
        ])->assertStatus(302)->assertRedirect('/');
        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_password']);
        $this->assertEquals('The database password must be a string.', $errorMessage);
    }

    public function test_password_validation_length_is_more_then_50_string_error()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_password' => 'fmdsnfdsfndsfndnfdskfndskfndfkndskfndsnfkdsnfndskfndsnfkdsnfndsnfdfndkfndskfndkfndknfkdnfkdnkfndnfdknfkdnfkdfndkfndkfndknfkdnfkdnfkdnn vdnvkdnfijfeirurerierhefndfjndjfnjdnfiefnekfnekfneifne'
        ])->assertStatus(302)->assertRedirect('/');

        /* compare the test message with validation error */
        $errors = Session::get('error');
        $messages = $errors->getMessages();
        $errorMessage = array_shift($messages['database_password']);
        $this->assertEquals('The database password must not be greater than 50 characters.', $errorMessage);
    }

    public function test_database_connection_failure()
    {
        $this->post(route('LaravelInstaller::environment'), [
            'database_hostname' => 'localhost',
            'database_name' => 'package_test_ok',
            'database_username' => 'root',
            'database_password' => '',
        ])->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error');
    }

    public function test_successfully_environment_setup()
    {
        $response = $this->post(route('LaravelInstaller::environment'), [
            'database_hostname' => 'localhost',
            'database_name' => 'package_test',
            'database_username' => 'root',
            'database_password' => '',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('LaravelInstaller::register'))
            ->assertSessionHas('message');
    }

    public function test_is_user_registration_disable()
    {
        Config::set('installer.is_user_registration_enabled', false);
        $response = $this->get(route('LaravelInstaller::getting-started'));
        $response->assertStatus(302)
            ->assertRedirect(route('LaravelInstaller::license-code'));
    }

    public function test_user_registration_is_enabled()
    {
        Config::set('installer.is_user_registration_enabled', true);
        $response = $this->get(route('LaravelInstaller::getting-started'));
        $response->assertStatus(200)
            ->assertViewIs('installer::registration');
    }

    public function test_is_license_code_disable()
    {
        Config::set('installer.is_license_code_enabled', false);
        $response = $this->get(route('LaravelInstaller::license-code'));
        $response->assertStatus(302)
            ->assertRedirect(route('LaravelInstaller::final'));
    }

    public function test_license_code_is_enabled()
    {
        Config::set('installer.is_license_code_enabled', true);
        $response = $this->get(route('LaravelInstaller::license-code'));
        $response->assertStatus(200)
            ->assertViewIs('installer::license-code');
    }

}
