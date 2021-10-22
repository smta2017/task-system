<?php
namespace modules\Users\Controllers;

use modules\BaseController;
use Illuminate\Http\Request;
use App\Http\Traits\ApiDesignTrait;
use modules\Users\Requests\UserRequest;
use modules\Users\Interfaces\UserInterface;
use modules\Users\Requests\UserLoginRequest;
use modules\Users\Requests\UpdatePasswordRequest;


class UserController extends BaseController
{
    use ApiDesignTrait;

    private $userInterface;

    public function __construct(UserInterface $user)
    {
//        $this->middleware(['auth', 'verified']);
        $this->userInterface = $user;
    }

    public function register(Request $request)
    {
        return $this->userInterface->register($request);
    }

    public function login(Request $request) {

        return $this->userInterface->login($request);
    }
    public function logout() {

        return $this->userInterface->logout();
    }

    public function refresh() {

        return $this->userInterface->refresh();
    }


    public function updatePassword(Request $request)
    {
        return $this->userInterface->updatePassword($request);
    }

    public function allUsers() {

        return $this->userInterface->allUsers();
    }
    public function userDetails(Request $request) {

        return $this->userInterface->userDetails($request);
    }

    public function userDetailsById(Request $request) {

        return $this->userInterface->userDetailsById($request);
    }

    public function UserUpdateById(Request $request) {

        return $this->userInterface->UserUpdateById($request);
    }

    public function updateUser(Request $request) {

        return $this->userInterface->updateUser($request);
    }

    public function softDeleteUser(Request $request) {

        return $this->userInterface->softDeleteUser($request);
    }

    public function restoreUser(Request $request) {

        return $this->userInterface->restoreUser($request);
    }




}
