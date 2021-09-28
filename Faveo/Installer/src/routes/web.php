<?php


Route::group(['prefix' => 'install', 'as' => 'LaravelInstaller::', 'namespace' => 'Faveo\Installer\Http\Controllers', 'middleware' => ['web', 'install']], function () {

    Route::get('/', [
        'as' => 'requirements',
        'uses' => 'RequirementsController@requirements',
    ]);

    Route::post('license-agreement', [
        'as' => 'license-agreement',
        'uses' => 'LicenseAgreement@licenseAgreement',
    ]);

    Route::get('environment', [
        'as' => 'environment',
        'uses' => 'EnvironmentController@dbSetup',
    ]);

    Route::post('environment', [
        'as' => 'environment',
        'uses' => 'EnvironmentController@saveEnviornmentDetails',
    ]);

    Route::get('database', [
        'as' => 'database',
        'uses' => 'DatabaseController@database',
    ]);

    Route::post('/getting-started', [
        'as' => 'getting-started',
        'uses' => 'AdminRegistrationController@store',
    ]);

    Route::get('/getting-started', [
        'as' => 'register',
        'uses' => 'AdminRegistrationController@create',
    ]);

    Route::get('/license-code', [
        'as' => 'license-code',
        'uses' => 'LicenseCodeController@create',
    ]);

    Route::post('/license-code', [
        'as' => 'license-code',
        'uses' => 'LicenseCodeController@store',
    ]);

    Route::get('final', [
        'as' => 'final',
        'uses' => 'FinalController@finish',
    ]);

//    Route::get('test', function (){
//        dd(csrf_token());
//    });


});

