<?php

// file: controllers/LoginController.php
require_once("util/RequestParametersHelper.php");
require_once("util/ApiMessages.php");

class LoginController extends Controller
{

    public function login()
    {
        try {
            $email = Input::get('email');
            $password = Input::get('password');
            if (Auth::attempt([
                'email' => $email,
                'password' => $password
            ])) {
                return ApiMessages::success();
            }
            return ApiMessages::userError("Contraseña/usuario incorrectos.");
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return ApiMessages::success();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    public function info()
    {
        try {
            if(Auth::check())
                return ApiMessages::success(Auth::user());
            else
                return ApiMessages::loginRequired();
            
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
}
