<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'app_url' => 'http://localhost/package/public',
    'timezone' => 'Asia/Kolkata',
    'core' => [
        'minPhpVersion' => '7.3.1',
    ],
    'final' => [
        'key' => true,
        'publish' => false,
    ],
    'requirements' => [
        'php' => [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'JSON',
            'cURL',
        ],
        'apache' => [
            'mod_rewrite',
        ],
    ],

    'is_user_registration_enabled' => env('IS_USER_REGISTRATION_ENABLED', false),

    'is_license_code_enabled' => env('IS_LICENSE_CODE_ENABLED', false),


    /*
   |--------------------------------------------------------------------------
   | License Code Permissions
   |--------------------------------------------------------------------------
   |
   | This is the license code validation check , if your application
   | requires license code just enable or disable is.
   |
   */

    'license_code' => [
        'is_required' => env('LICENSE_CODE_IS_REQUIRED', true)
    ],


    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'storage/framework/' => '775',
        'storage/logs/' => '775',
        'bootstrap/cache/' => '775',
    ],


    /*
   |--------------------------------------------------------------------------
   | Getting Started With Admin Form Wizard Validation Rules & Messages
   |--------------------------------------------------------------------------
   |
   | This are the default form field validation rules. Available Rules:
   | https://laravel.com/docs/5.4/validation#available-validation-rules
   |
   */
    'getting-started' => [
        'form' => [
            'rules' => [
                'firstname' => 'required|max:20',
                'Lastname' => 'required|max:20',
                'email' => 'required|max:50|email',
                'username' => [
                    'required', 'required',
                    'regex:/^(?:[@A-Z\d][A-Z\d.@_-]{2,20}|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,5})$/i',
                    'unique:users,user_name,'
                ],
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
                'driver' => 'required|string',
                'aws_access_key_id' => 'sometimes|nullable|required_if:driver,==,s3',
                'aws_access_key' => 'sometimes|nullable|required_if:driver,==,s3',
                'aws_default_region' => 'sometimes|nullable|required_if:driver,==,s3',
                'aws_bucket' => 'sometimes|nullable|required_if:driver,==,s3',
                'aws_endpoint' => 'sometimes|nullable|required_if:driver,==,s3'
            ],
            [
                'username.regex' => 'Username should have minimum 3 and maximum 20 characters and can have only alphanumeric characters, spaces, underscores, hyphens, periods and @ symbol.',
                '*.required_if' => ':attribute is required when the default storage driver is S3',
                'driver.required' => 'Default storage driver is required field',
            ]
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Installed Middleware Options
    |--------------------------------------------------------------------------
    | Different available status switch configuration for the
    | canInstall middleware located in `canInstall.php`.
    |
    */
    'installed' => [
        'redirectOptions' => [
            'route' => [
                'name' => 'welcome',
                'data' => [],
            ],
            'abort' => [
                'type' => '404',
            ],
            'dump' => [
                'data' => 'Dumping a not found message.',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Selected Installed Middleware Option
    |--------------------------------------------------------------------------
    | The selected option fo what happens when an installer instance has been
    | Default output is to `/resources/views/error/404.blade.php` if none.
    | The available middleware options include:
    | route, abort, dump, 404, default, ''
    |
    */
    'installedAlreadyAction' => '',

    /*
    |--------------------------------------------------------------------------
    | Updater Enabled
    |--------------------------------------------------------------------------
    | Can the application run the '/update' route with the migrations.
    | The default option is set to False if none is present.
    | Boolean value
    |
    */
    'updaterEnabled' => 'true',

    'image' => [
        'logo' => 'installer/themes/default/common/images/logo.png',
        'favicon' => 'installer/themes/default/common/images/favicon.ico',
        'whitefavicon' => 'installer/themes/default/common/images/whitefavicon.png',
        'loading' => 'installer/themes/default/agent/images/loading.gif',
        'gifloader' => 'installer/themes/default/common/images/gifloader.gif',
        'gifloader3' => 'installer/themes/default/common/images/gifloader3.gif',
        'whitelabel' => 'installer/themes/default/common/images/whitelabel.png',
        'nodatafound' => 'installer/themes/default/agent/images/nodatafound.png',
        'faveo' => 'installer/themes/default/common/images/installer/faveo.png',
        'team' => 'installer/themes/default/admin/images/team.jpg',
        'department' => 'installer/themes/default/admin/images/department.jpg',
        '25' => 'installer/themes/default/admin/images/25.gif',
        'system' => 'installer/themes/default/common/images/system.png',
        'text' => 'installer/themes/default/agent/images/text.png',
        'common' => 'installer/themes/default/agent/images/common.png',
        'flag' => 'installer/themes/default/common/images/flags',
        'knowledgebase' => 'installer/themes/default/client/images/knowledgebase.png',
        'register' => 'installer/themes/default/client/images/register.png',
        'news' => 'installer/themes/default/client/images/news.png',
        'submitticket' => 'installer/themes/default/client/images/submitticket.png',
        'video' => 'installer/themes/default/common/images/video.png',
        'audio' => 'installer/themes/default/common/images/audio.png',
        'contacthead' => 'installer/themes/default/common/images/contacthead.png',
    ],

    'css' => [
        'all-skins' => 'installer/themes/default/common/css/_all-skins.min.css',
        'admin-common' => 'installer/themes/default/admin/css/admin-common.css',
        'AdminLTE' => 'installer/themes/default/common/css/AdminLTE.min.css',
        'AdminLTEsemi' => 'installer/themes/default/common/css/AdminLTEsemi.min.css',
        'bootstrap' => 'installer/themes/default/common/css/bootstrap.min.css',
        'bootstrap-latest' => 'installer/themes/default/common/css/bootstrap-v3.4.min.css',
        'bootstrap-datetimepicker4' => 'installer/themes/default/common/css/bootstrap-datetimepicker4.7.14.min.css',
        'bootstrap-multiselect' => 'installer/themes/default/common/css/bootstrap-multiselect.css',
        'bootstrap-select' => 'installer/themes/default/common/css/bootstrap-select.css',
        'bootstrap-toggle' => 'installer/themes/default/admin/css/bootstrap-toggle.min.css',
        'bootstrap-select-min' => 'installer/themes/default/common/css/min/bootstrap-select.css',
        'font-awesome' => 'installer/themes/default/common/css/font-awesome.min.css',
        'jquerysctipttop' => 'installer/themes/default/client/css/jquerysctipttop.min.css',
        'ionicons' => 'installer/themes/default/common/css/ionicons.min.css',
        'tabby' => 'installer/themes/default/common/css/tabby.min.css',
        'editor' => 'installer/themes/default/common/css/editor.css',
        'new-common' => 'installer/themes/default/agent/css/new-common.min.css',
        'nprogress' => 'installer/themes/default/common/css/loader/nprogress.min.css',
        'widgetbox' => 'installer/themes/default/client/css/widgetbox.min.css',
        'jquery-rating' => 'installer/themes/default/common/css/jquery.rating.min.css',
        'jquery-ui' => 'installer/themes/default/common/css/jquery.ui.css',
        'flags' => 'installer/themes/default/client/css/flags.min.css',
        'client-custom-css' => 'installer/themes/default/client/css/client.min.css',

        'faveo-css' => 'installer/themes/default/common/css/faveo-css.css',
        'select2' => 'installer/themes/default/common/plugins/select2/select2.min.css',
        'bootstrap-switch' => 'installer/themes/default/common/plugins/bootstrap_switch/bootstrap-switch.css',
        'bootstrap-switch-min' => 'installer/themes/default/admin/plugins/bootstrap_switch/bootstrap-switch.min.css',
        'daterangepicker' => 'installer/themes/default/common/plugins/daterangepicker/daterangepicker.css',
        'daterangepicker' => 'installer/themes/default/common/plugins/daterangepicker/daterangepicker.css',
        'dataTables-bootstrap' => 'installer/themes/default/common/plugins/datatables/dataTables.bootstrap.min.css',
        'blue' => 'installer/themes/default/common/plugins/iCheck/flat/blue.css',

        'bootstrap-colorpicker' => 'installer/themes/default/admin/plugins/colorpicker/bootstrap-colorpicker.min.css',
        'jquery-ui-base-1' => 'installer/themes/default/common/plugins/hailhood-tag/demo/css/jquery-ui-base-1.8.20.css',
        'tagit-stylish-yellow' => 'installer/themes/default/common/plugins/hailhood-tag/css/tagit-stylish-yellow.css',
        'intlTelInput' => 'installer/themes/default/common/css/intlTelInput.min.css',

        'app' => 'installer/themes/default/admin/css/app.css',
        'app-form' => 'installer/themes/default/admin/css/form/app.css',
        'angular-ui-tree' => 'installer/themes/default/admin/css/angular-ui-tree.css',
        'load-styles' => 'installer/themes/default/common/css/load-styles.css',
        'css' => 'installer/themes/default/common/css/css.css',
        'admin' => 'installer/themes/default/common/css/admin.css',
        'setup' => 'installer/themes/default/common/css/setup.css',
        'activation' => 'installer/themes/default/common/css/activation.css',
        'style' => 'installer/themes/default/common/css/style.css',
        'ggpopover' => 'installer/themes/default/common/css/ggpopover.css',
        'prism' => 'installer/themes/default/common/css/prism.css',
        'chosen' => 'installer/themes/default/common/css/chosen.css',
        'fullcalendar' => 'installer/themes/default/agent/plugins/fullcalendar/fullcalendar.min.css',
        'tw-currency-select' => 'installer/themes/default/common/plugins/currency-picker/tw-currency-select.css',
        'widgetbox' => 'installer/themes/default/client/css/widgetbox.min.css',
        'faveo-css' => 'installer/themes/default/common/css/faveo-css.css',
        'star' => 'installer/themes/default/agent/images/star.png',

        'ckeditor-css' => 'installer/themes/default/common/plugins/ckeditor5/ckeditor.css',

        //===================================================
        //servicedesk plugin
        //=====================================================
        'perfect-scrollbar' => 'installer/themes/default/common/css/perfect-scrollbar.css',
        'table-style' => 'installer/themes/default/agent/css/perfect-scrollbar.css',
        'bootstrap-rtl' => 'installer/themes/default/common/css/rtl/css/bootstrap-rtl.min.css',
        //=====================================================

        // LATEST VERSIONS
        // Font Awesome && Bootstrap
        'bootstrap-4' => 'installer/themes/default/common/css/bootstrap4.min.css',
        'font-awesome-5' => 'installer/themes/default/common/css/font-awesome5.min.css',
        'new-overlay' => 'installer/themes/default/common/adminlte3/plugins/overlayScrollbars/overlayScrollbars.min.css',
        'adminlte-3' => 'installer/themes/default/common/adminlte3/css/adminlte3.min.css',
        'adminlte-3-rtl' => 'installer/themes/default/common/adminlte3/rtl/adminlte3.min.css',
        'pagination' => 'installer/themes/default/common/css/pagination.min.css',
        'glyphicon' => 'installer/themes/default/common/css/glyphicon.css',

    ],
    'js' => [
        'moment' => 'installer/themes/default/common/js/min/moment.min.js',
        'chart' => 'installer/themes/default/agent/js/min/Chart.min.js',
        'bootstrap' => 'installer/themes/default/common/js/min/bootstrap.min.js',
        'bootstrap-latest' => 'installer/themes/default/common/js/min/bootstrap-v3.4.min.js',
        'bootstrap-toggle' => 'installer/themes/default/common/js/min/bootstrap-toggle.min.js',
        'bootstrap-select' => 'installer/themes/default/common/js/bootstrap-select.js',
        'ui-bootstrap-tpls' => 'installer/themes/default/common/js/ui-bootstrap-tpls-1.2.5.js',
        'polyfill' => 'installer/themes/default/client/js/min/polyfill.min.js',
        'jquery-2' => 'installer/themes/default/common/js/jquery-2.1.4.min.js',
        'angular' => 'installer/themes/default/common/js/angular/angular.min.js',
        'angular-moment' => 'installer/themes/default/common/js/angular/angular-moment.min.js',
        'bsSwitch' => 'installer/themes/default/common/js/angular/bsSwitch.js',
        'angular-desktop-notification' => 'installer/themes/default/common/js/angular/angular-desktop-notification.min.js',
        'ui-bootstrap-tpls' => 'installer/themes/default/common/js/form/ui-bootstrap-tpls.js',
        'main' => 'installer/themes/default/admin/js/form/main.js',
        'handleCtrl' => 'installer/themes/default/admin/js/form/handleCtrl.js',
        'nodeCtrl' => 'installer/themes/default/admin/js/form/nodeCtrl.js',
        'nodesCtrl' => 'installer/themes/default/admin/js/form/nodesCtrl.js',
        'treeCtrl' => 'installer/themes/default/admin/js/form/treeCtrl.js',
        'uiTree' => 'installer/themes/default/admin/js/form/uiTree.js',
        'uiTreeHandle' => 'installer/themes/default/admin/js/form/uiTreeHandle.js',
        'uiTreeNode' => 'installer/themes/default/admin/js/form/uiTreeNode.js',
        'uiTreeNodes' => 'installer/themes/default/admin/js/form/uiTreeNodes.js',
        'helper' => 'installer/themes/default/admin/js/form/helper.min.js',
        'ng-flow-standalone' => 'installer/themes/default/common/js/angular/ng-flow-standalone.js',
        'fusty-flow' => 'installer/themes/default/common/js/angular/fusty-flow.min.js',
        'fusty-flow-factory' => 'installer/themes/default/common/js/angular/fusty-flow-factory.min.js',
        'ng-file-upload' => 'installer/themes/default/common/js/angular/ng-file-upload.min.js',
        'ng-file-upload-shim' => 'installer/themes/default/common/js/angular/ng-file-upload-shim.min.js',
        'superfish' => 'installer/themes/default/common/js/min/superfish.min.js',
        'mobilemenu' => 'installer/themes/default/common/js/min/mobilemenu.min.js',
        'know' => 'installer/themes/default/common/js/min/know.min.js',
        'app-min' => 'installer/themes/default/common/js/min/app.min.js',
        'intlTelInput' => 'installer/themes/default/common/js/intlTelInput.js',
        'bootstrap-datetimepicker4' => 'installer/themes/default/common/js/min/bootstrap-datetimepicker4.7.14.min.js',
        'jquery-ui' => 'installer/themes/default/common/js/jquery.ui.js',
        'bootstrap-multiselect' => 'installer/themes/default/admin/js/bootstrap-multiselect.js',
        'angular-admin-script' => 'installer/themes/default/admin/js/angular-admin-script.js',
        'tabby' => 'installer/themes/default/agent/js/min/tabby.min.js',
        'angular-recaptcha' => 'installer/themes/default/common/js/angular/angular-recaptcha.min.js',
        'select2' => 'installer/themes/default/common/plugins/select2/select2.min.js',
        'jquery-dataTables' => 'installer/themes/default/common/plugins/datatables/jquery.dataTables.min.js',
        'bootstrap-switch' => 'installer/themes/default/admin/plugins/bootstrap_switch/bootstrap-switch.js',
        'bootstrap-switch-min' => 'installer/themes/default/admin/plugins/bootstrap_switch/bootstrap-switch.min.js',
        'chart-min' => 'installer/themes/default/agent/plugins/chartjs/Chart.min.js',
        'jquery' => 'installer/themes/default/common/plugins/jQuery/jquery-3.1.1.min.js',
        'jquery-3' => 'installer/themes/default/common/plugins/jQuery/jquery-3.4.1.min.js',
        'daterangepicker' => 'installer/themes/default/common/plugins/daterangepicker/daterangepicker.js',
        'daterangepicker-min' => 'installer/themes/default/common/plugins/daterangepicker/daterangepicker.min.js',
        'moment-timezone' => 'installer/themes/default/agent/plugins/moment/moment-timezone.min.js',
        'moment-timezone-with-data' => 'installer/themes/default/agent/plugins/moment/moment-timezone-with-data-2012-2022.min.js',
        'ckeditor' => 'installer/themes/default/common/plugins/ckeditor4/ckeditor.js',
        'vue-ckeditor' => 'installer/themes/default/common/plugins/ckeditor5/ckeditor.js',
        'app' => 'installer/themes/default/client/js/app.js',
        'nprogress' => 'installer/themes/default/common/plugins/loader/nprogress.min.js',
        'tw-currency-select' => 'installer/themes/default/common/plugins/currency-picker/tw-currency-select.min.js',
        'browser-detect' => 'installer/themes/default/common/js/min/browser-detect.min.js',
        'dataTables-bootstrap' => 'installer/themes/default/common/plugins/datatables/dataTables.bootstrap.min.js',
        'iCheck' => 'installer/themes/default/common/plugins/iCheck/icheck.min.js',
        'bootstrap-colorpicker' => 'installer/themes/default/admin/plugins/colorpicker/bootstrap-colorpicker.min.js',
        'tagit' => 'installer/themes/default/admin/plugins/hailhood-tag/js/tagit.js',
        'bootstrap3-wysihtml5' => 'installer/themes/default/common/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js',
        'angular-agent-scripts' => 'installer/themes/default/agent/js/angular-agent-scripts.js',
        'ggpopover' => 'installer/themes/default/common/js/ggpopover.js',
        'chosen-jquery' => 'installer/themes/default/common/js/chosen.jquery.js',
        'prism' => 'installer/themes/default/common/js/prism.js',
        'jquery-sticky' => 'installer/themes/default/admin/js/jquery.sticky.js',
        'utils' => 'installer/themes/default/client/js/min/utils.min.js',
        'select2-full-min' => 'installer/themes/default/common/plugins/select2/select2.full.min.js',
        'fullcalendar' => 'installer/themes/default/agent/plugins/fullcalendar/fullcalendar.min.js',
        'ajax-jquery' => 'installer/themes/default/admin/min/js/ajax-jquery.min.js',
        'jquery-rating-pack' => 'installer/themes/default/common/js/jquery.rating.pack.min.js',
        'utils' => 'installer/themes/default/common/js/utils.js',
        'angular2' => 'installer/themes/default/common/js/angular/angular2.js',


        //===================================================
        //servicedesk plugin
        //=====================================================
        'perfect-scrollbar' => 'installer/themes/default/common/js/perfect-scrollbar.js',
        'jquery-twbsPagination' => 'installer/themes/default/agent/js/jquery.twbsPagination.js',
        'jquery-slimscroll' => 'installer/themes/default/admin/js/min/jquery.slimscroll.min.js',
        'fastclick' => 'installer/themes/default/admin/js/min/fastclick.min.js',
        'plugin' => 'installer/themes/default/admin/js/plugin.js',
        //=====================================================

        // BOOTSTRAP LATEST
        'bootstrap-4' => 'installer/themes/default/common/js/min/bootstrap4.min.js',

        'client-custom-js' => 'installer/themes/default/client/js/min/client.min.js',
        'popper' => 'installer/themes/default/common/js/min/popper.min.js',
        'laravel-echo' => 'installer/themes/default/common/js/min/echo.min.js',
        'pusher' => 'installer/themes/default/common/js/min/pusher.js',
        'new-overlay' => 'installer/themes/default/common/adminlte3/plugins/overlayScrollbars/overlayScrollbars.min.js',
        'adminlte-3' => 'installer/themes/default/common/adminlte3/js/adminlte3.min.js',
    ],

    /* server requirement's topics */
    "serverRequirements" => [
        "optional" => [
            "ldap",
            "redis",
            "ionCube Loader",
            "soap"
        ],
        "required" => [
            "curl",
            "ctype",
            "imap",
            "mbstring",
            "openssl",
            "tokenizer",
            "pdo_mysql",
            "zip",
            "pdo",
            "mysqli",
            "bcmath",
            "iconv",
            "XML",
            "json",
            "fileinfo",
            "gd"
        ],

        "requisites" => [
            "Establish connection to Helpdesk License Manager",
            "PHP Version",
            "PHP exec function",
            "env",
            "max_execution_time",
            "allow_url_fopen",
            "cURL",
            "app_url"
        ]
    ],
    'base_path' => base_path()

];
