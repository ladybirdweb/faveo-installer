<?php

namespace Tests\Unit;

use Faveo\Installer\FaveoInstallerServiceProvider;
use Orchestra\Testbench\TestCase;

class InstallerTest extends TestCase
{
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

    /**
     * test view of server requirement
     * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
     */
    public function test_server_requirement_for_application()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('install');

        $response->assertStatus(200)
            ->assertViewIs('installer::server-requirement')
            ->assertViewHas(['permissionBlock', 'requisites', 'phpExtension', 'modRewrite', 'apacheModules', 'errorCount']);
    }

    /**
     * test while some requirement doesn't matched of user system
     * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
     */
//    public function test_server_requirement_doesnt_match()
//    {
//        $this->withoutExceptionHandling();
//        $response = $this->call('post', route('LaravelInstaller::license-agreement'), [
//            'server_requirement_error' => 1
//        ]);
//
//        $response->assertStatus(302)
//            ->assertRedirect('/')
//            ->assertSessionHas('error');
//    }

    /**
     * test while all requirement matched of user and no error found
     * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
     */
    public function test_server_requirement_fulfill()
    {
        $response = $this->call('post', route('LaravelInstaller::license-agreement'), [
            'server_requirement_error' => 0
        ]);

        $response->assertStatus(200)
            ->assertViewIs('installer::license-agreement')
            ->assertViewHas('errors');
    }

    /**
     * test while request param validation failed in the license agreement
     * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
     */
    public function test_license_agreement_conditions_is_accepted_validation_error()
    {
        $response = $this->call('get', route('LaravelInstaller::environment'), [
            'is_accept' => 3 /* required only boolean type value*/
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('error');
    }

    /**
     * test while user not accepted the license agreement
     * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
     */
    public function test_license_agreement_condition_is_not_accepted()
    {
        $response = $this->call('get', route('LaravelInstaller::environment'), [
            'is_accept' => false
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error');
    }

    /**
     * test while user successfully accepted the license agreement
     * @author Hitesh Kumar <Hitesh.kumar@ladybirdweb.com>
     */
    public function test_successfully_accepted_the_license_agreement()
    {
        $response = $this->call('get', route('LaravelInstaller::environment'), [
            'is_accept' => true
        ]);

        $response->assertStatus(200)
            ->assertViewIs('installer::database-setup');
    }

}
