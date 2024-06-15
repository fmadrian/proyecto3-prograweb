<?php
require_once("util/RequestParametersHelper.php");
require_once("util/ApiMessages.php");

class UserController extends Controller
{
    private static $table_name = 'users';

    // GET /user
    public function index()
    {
        try {
            $user = Auth::user();
            $name = $user != null ? $user['name'] : "";
            $is_admin = $user != null && $user['is_admin'] == 1;

            if (!$is_admin)
                return ApiMessages::userError("Debe ser administrador");
            else
                return ApiMessages::success(
                    RequestParametersHelper::addUserParameters(
                        $user,
                        [
                            'users' => DB::table(self::$table_name)->get(),
                            'title' => 'Usuarios',
                        ]
                    )
                );
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // GET /users/(:id)
    public function show($id)
    {
        try {
            // No permitir usuarios no autenticados
            if (!Auth::check())
                return ApiMessages::loginRequired();

            // Obtener información del usuario que hace el request.
            $current_user = Auth::user();

            // Solamente permitir al propio usuario o un administrador.
            if ($current_user['id'] != $id && $current_user['is_admin'] == 0)
                return ApiMessages::userError("Debe ser un administrador para ver esta información.");

            // Buscar y regresar información del usuario.
            $user = DB::table(self::$table_name)->find($id)[0];
            // HTTP 404.
            if ($user == null) {
                ApiMessages::notFound();
            }

            // Obtener reservaciones hechas por el usuario.
            $reservations = DB::table("reservations")->where('user_id', "=", $user['id'])->get();

            $reservations = array_map(function ($item) {
                // Busca información de la habitación
                $room_info = DB::table('rooms')->find($item['room_id'])[0];
                // Busca información del hotel
                $hotel_info = DB::table('hotels')->find($room_info['hotel_id'])[0];
                // Coloca información en el objeto.
                $item["reservation_hotel_id"] = $hotel_info['id'];
                $item["reservation_room_id"] = $room_info['id'];

                $item["reservation_hotel_name"] = $hotel_info['name'];
                $item["reservation_room_name"] = $room_info['name'];

                unset($item['room_id']);
                return $item;
            }, $reservations);

            return ApiMessages::success(RequestParametersHelper::addUserParameters($current_user, [
                "reservations" => $reservations,
                'has_reservations' => count($reservations) > 0,
                "title" => 'Usuario',
                "queried_user" => [
                    'is_admin' => $user['is_admin'] == 1,
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'id' => $user['id'],
                ]
            ]));
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // DELETE /users/(:id)
    public function destroy($id)
    {
        try {
            // No permitir eliminar usuario "admin".
            if ($id == 1)
                return ApiMessages::userError("No permitir eliminar admin");

            DB::table(self::$table_name)->delete($id);
            return ApiMessages::deleted();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
};
