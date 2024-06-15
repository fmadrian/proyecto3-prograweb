<?php

require_once("util/RequestParametersHelper.php");
require_once("util/ApiMessages.php");

class RegisterController extends Controller
{


  public function register()
  {
    try {
      $name = Input::get('name');
      $email = Input::get('email');
      $password = Input::get('password');

      $user = [
        'name' => $name, 'email' => $email,
        'password' => $password, 'is_admin' => 0
      ];

      DB::table("users")->insert($user);
      return ApiMessages::created();
    } catch (Exception $e) {
      return ApiMessages::internalServerError($e);
    }
  }


  public function update($_, $user_id = NULL)
  {
    try {
      $name = Input::get('name');
      $email = Input::get('email');
      $password = Input::get('password');
      $is_admin = Input::get('is_admin') != null ? Input::get('is_admin') : 0;

      $user = Auth::user();
      if ($user == null)
        return ApiMessages::loginRequired();

      // Determinar si usuario se está actualizando así mismo o está actualizando a otros.
      $self_update = $user['id'] == $user_id;
      // No permitir a no administrador actualizar otros usuarios.
      if ($user['is_admin'] == "0" && !$self_update)
        return ApiMessages::userError("Actualizar otros usuarios");

      // No permitir modificar usuario administrador.
      if ($user_id == 1) {
        $name = 'admin';
        $email = 'admin@mail.com';
        $is_admin = 1;
      }

      DB::table('users')->update($user_id, [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'is_admin' => $is_admin
      ]);

      return ApiMessages::updated();
    } catch (Exception $e) {
      return ApiMessages::internalServerError($e);
    }
  }
}
