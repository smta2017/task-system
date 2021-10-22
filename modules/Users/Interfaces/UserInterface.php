<?php
namespace modules\Users\Interfaces;


interface UserInterface {

    public function register($request);
    public function login($request);
    public function logout();
    public function refresh();

    public function updatePassword($request);
    public function allUsers();
    public function userDetails($request);
    public function userDetailsById($request);
    public function UserUpdateById($request);
    public function updateUser($request);
    public function softDeleteUser($request);
    public function restoreUser($request);

}
