<?php
require_once("util/RequestParametersHelper.php");
require_once("util/ApiMessages.php");

class HotelController extends Controller
{
    private static $table_name = 'hotels';

    // GET /hotels
    public function index()
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                // return ApiMessages::loginRequired();
            }
            $hotels = DB::table(self::$table_name)->get();
            return ApiMessages::success(
                RequestParametersHelper::addUserParameters(
                    $user,
                    [
                        'hotels' => $hotels,
                        'title' => 'Hoteles',
                        'has_hotels' => count($hotels) > 0
                    ]
                )
            );
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // GET /hotels/(:id)
    public function show($id)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                // return ApiMessages::loginRequired();
            }
            // Buscar hotel.
            $hotel = DB::table(self::$table_name)->find($id)[0];
            // HTTP 404.
            if ($hotel == null) {
                return ApiMessages::notFound();
            }
            // Buscar habitaciones para hotel
            $rooms = DB::table('rooms')->where('hotel_id', "=", $hotel['id'])->get();
            $rooms = array_map(function ($item) {
                $item["room_id"] = $item['id'];
                $item["room_name"] = $item['name'];
                unset($item['id']);
                unset($item['name']);
                return $item;
            }, $rooms);

            return ApiMessages::success(
                RequestParametersHelper::addUserParameters(
                    $user,
                    [
                        'title' => 'Hotel',
                        'hotel' => $hotel,
                        'rooms' => $rooms,
                        'has_rooms' => count($rooms) > 0
                    ]
                )
            );
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // POST /hotels
    public function store($_ = null)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                return ApiMessages::loginRequired();
            }
            // Solo administradores pueden crear/editar hoteles.
            if ($user['is_admin'] == 0) {
                return ApiMessages::onlyAdmin("Crear hoteles.");
            }

            $hotel = [
                'name' => strtoupper(Input::get('name')),
                'stars' => Input::get('stars'),
                'description' => Input::get('description'),
                'location' => Input::get('location'),
            ];

            DB::table(self::$table_name)->insert($hotel);
            return ApiMessages::created();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // PUT /hotels/(:id)
    public function update($_, $id = null)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                return ApiMessages::loginRequired();
            }
            // Solo administradores pueden crear/editar hoteles.
            if ($user['is_admin'] == 0) {
                return ApiMessages::onlyAdmin("Editar hoteles.");
            }

            $updated_hotel = [
                'name' => strtoupper(Input::get('name')),
                'stars' => Input::get('stars'),
                'description' => Input::get('description'),
                'location' => Input::get('location'),
            ];

            DB::table(self::$table_name)->update($id, $updated_hotel);
            return ApiMessages::updated();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // DELETE /hotels/(:id)
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                return ApiMessages::loginRequired();
            }
            // Solo administradores pueden crear/editar hoteles.
            if ($user['is_admin'] == 0) {
                return ApiMessages::onlyAdmin("Eliminar hoteles.");
            }
            DB::table(self::$table_name)->delete($id);
            return ApiMessages::deleted();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
};
