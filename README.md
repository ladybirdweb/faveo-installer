<h1 style="color: #0d6aad">Faveo Installer</h1>

<h3>About</h3>

<p>Do you want your client to be able to install a Laravel project with great User interface and user experience.
This installer allows users to create a wizard for user to show and take requirement to create a environment setup on user machine.
</p>

<h3>How it works :</h3>

1. Check Server Requirements
2. License Agreement
3. Setup Database (setup .env , migrations and seed the tables).
4. User Registration (Optional) You can handle it according to application requirement you can make it mandatory step
   through config/installer.php
5. License Code (optional)
6. Finalise Step (Setting up things for you local machine).

<h3>Installation Process :</h3>

1. <code>composer require faveo/installer</code>

2. Register the package
    - You have to register the package service provider into the “config/app.php” under providers with the following:

     <code> ‘providers’=>[
      Faveo\Installer\FaveoInstallerServiceProvider::class
      ]
   </code>

3. Publish the packages views, config file, assets, and language files by running the following from your projects root
   folders.

    <code>
   php artisan vendor:publish —tag=faveo-installer
    </code>

<h3>Config Changes for optional features</h3>
<p>We have user registration and license code validation steps is optional you can 
make it required by change environment variables values to true or define these values in <code>.env</code> file.
</p>

<p>Go to <code>config/installer.php</code> file and change default values to true</p>

Using config changes:

<code> 

    'is_user_registration_enabled' => env('IS_USER_REGISTRATION_ENABLED',true)
    'is_license_code_enabled' => env('IS_LICENSE_CODE_ENABLED', true)
</code>

or, You can add these variable in .env

<code>
IS_USER_REGISTRATION_ENABLED=true

IS_LICENSE_CODE_ENABLED=true
</code>

<h3>Routes</h3>

Just start your application with <code>{{base-url}}/install</code> routes.

- In order to install your application, go to the install route and follow the instruction
- Once the installation has ran the empty file installed will be placed into the /storage directory if this file is
  present the route /install will abort to the page 404.
  
<h3>Custom Code Implementation Of User Registration and License Code</h3>

<p>After installation you can create your own user registration process and also form validation in it. While your
installation part completed you can found the helper class in<code>app/Helpers/function.php</code>  where you can find user
registration validation function you can just pass the array of validation to it for validate each request form. The
name of function is “validationForCreateUserInstaller” and same “createUserForInstaller” function for user registration
process logic.</p>
<code>

	// user registration form validation 
	function validationForCreateUserInstaller(){

		// write user registration form validation in array type 
	return [];
	}

	// User registration logic
	function createUserForInstaller(){

		// write user registration logic here  
	return [];
	}

</code>

<p>Now, the same for license code of application we have also two function for validate license key and activate the
license code in application.</p>
<code>

    // license code validation check
    function validationRulesForLicenseCode(){
		// write license code form validation in array type 
	return [];
	}

	// Validate license code  logic 
	function validateLicenseCodeOfUser(){
		// write license code  to check valid or not  license key
	return [];
	}

</code>


<h3>Change View of Installation process</h3>

<p>You can easily change the Blade file each and every step has its own blade file of Laravel so you can customise the view
according to your application in <code>resources/views/vendor/installer</code> directory.
</p>


