<?php

class RequestParametersHelper
{
    // Devuelve arreglo donde añade parametros necesarios para al navbar
    // Necesario para el navbar.
    public static function addUserParameters($user, $param = [])
    {
        $userParam = [
            'login' => Auth::check(),
            'user' => [
                'is_admin'=> $user != null ? $user['is_admin'] == 1 : false,
                'name'=> $user != null ? $user['name']: "",
                'id' => $user != null ? $user['id'] : -1,
            ]
            // 'user_is_admin' => $user['is_admin'] == 1,
            // 'user_name' => $user['name'],
            // 'user_id' => $user['id'],
        ];
        return array_merge($param, $userParam);
    }
}
