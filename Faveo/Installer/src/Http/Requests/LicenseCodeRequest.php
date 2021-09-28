<?php


namespace Faveo\Installer\Http\Requests;


use Faveo\Installer\Helpers\LicenseCodeManager;

class LicenseCodeRequest
{
    /**
     * This is validate the user request params
     * @return array
     */
    public function rules():array{
        LicenseCodeManager::validationRules();
    }
}
