<?php

class ApiMessages
{
    // HTTP 200: Creado.
    public static function created($message = "Creado.", $object = null)
    {
        if ($object == null) {
            return [
                "status" => 200,
                "message" => $message
            ];
        }
        return [
            "status" => 200,
            "message" => $message,
            "object" => $object
        ];
    }

    // HTTP 200: Actualizado.
    public static function updated($message = "Actualizado.", $object = null)
    {
        if ($object == null) {
            return [
                "status" => 200,
                "message" => $message
            ];
        }
        return [
            "status" => 200,
            "message" => $message,
            "object" => $object
        ];
    }
    // HTTP 200: Eliminado.
    public static function deleted($message = "Eliminado.")
    {

        return [
            "status" => 200,
            "message" => $message,
        ];
    }
    // HTTP 200: 
    public static function success($body = null, $message = null)
    {
        $response = [
            "status" => 200
        ];
        // Agregar cuerpo a respuesta (si hay uno).
        if ($body != null) {
            $response = array_merge($response, ["body" => $body]);
        }
        // Agregar mensaje a respuesta (si hay uno).
        if ($message != null) {
            $response = array_merge($response, ["message" => $message]);
        }
        return $response;
    }

    // HTTP 404: No encontrado.
    public static function notFound()
    {
        return [
            "status" => 404,
            "message" => " Not found"
        ];
    }
    // HTTP 403: Errores de usuario
    public static function userError($message = "User error.")
    {
        return [
            "status" => 403,
            "message" => $message
        ];
    }

    // HTTP 403: Login requerido.
    public static function loginRequired($message = "Login requerido.")
    {
        return [
            "status" => 403,
            "message" => $message
        ];
    }

    // HTTP 401: Solo administradores.
    public static function onlyAdmin($message)
    {
        return [
            "status" => 401,
            "message" => "Solo administradores pueden:" . ' ' . $message
        ];
    }

    // HTTP 500: Error interno
    public static function internalServerError($e, $message = null)
    {
        $response = [
            "status" => 500
        ];
        // Agregar mensaje a respuesta (si hay uno).
        // De lo contrario, agregar excepción.
        if ($message != null) {
            $response = array_merge($response, ["error" => $message]);
        } else {
            $response = array_merge($response, ["error" => $e->getMessage()]);
        }

        return $response;
    }
};
