<?php


namespace Faveo\Installer\Http\Requests;


use Faveo\Installer\Helpers\UserRegistrationManager;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * This is validate the user request params
     * @return array
     */
    public function rules():array{
       return UserRegistrationManager::validationRules();
    }
}
