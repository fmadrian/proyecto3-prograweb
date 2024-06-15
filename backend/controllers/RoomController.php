<?php
require_once("util/RequestParametersHelper.php");
require_once("util/OtherHelpers.php");
require_once("util/ApiMessages.php");
class RoomController extends Controller
{
    private static $table_name = 'rooms';

    // GET /rooms
    public function index()
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                // return ApiMessages::loginRequired();
            }
            $rooms = RoomHelpers::addHotelNames(DB::table(self::$table_name)->get());
            return ApiMessages::success(
                RequestParametersHelper::addUserParameters(
                    $user,
                    [
                        'rooms' => $rooms,
                        'has_rooms' => count($rooms) > 0,
                        'title' => 'Habitaciones'
                    ]
                )
            );
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // GET /rooms/(:id)
    public function show($id)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                // return ApiMessages::loginRequired();
            }

            $room = DB::table(self::$table_name)->find($id)[0];
            // HTTP 404.
            if ($room == null) {
                return ApiMessages::notFound();
            }

            // Obtener reservaciones
            $reservations = DB::table("reservations")->where('room_id', "=", $room['id'])->get();
            $reservations = array_map(function ($item) {
                $reservation_user = DB::table('users')->find($item['user_id'])[0];
                $item["reservation_user_id"] = $item['user_id'];
                $item["reservation_user_name"] = $reservation_user['name'];
                $item["reservation_id"] = $item['id'];
                unset($item['user_id']);
                return $item;
            }, $reservations);

            return ApiMessages::success(
                RequestParametersHelper::addUserParameters(
                    $user,
                    [
                        'title' => 'Habitación',
                        'room' => RoomHelpers::addHotelName($room),
                        'reservations' => $reservations,
                        'has_reservations' => count($reservations) > 0
                    ]
                )
            );
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // GET /room/create
    public function create()
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                return ApiMessages::loginRequired();
            }
            $hotels = DB::table('hotels')->get();

            return ApiMessages::success(RequestParametersHelper::addUserParameters(
                $user,
                [
                    "title" => "Añadir una habitación",
                    "is_update" => false,
                    "hotels" => InputHelpers::prepareHotelSelect($hotels, 0)
                ]
            ));
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // GET /rooms/(:id)/edit
    public function edit($id)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                return ApiMessages::loginRequired();
            }

            // Obtener lista de hoteles.
            $hotels = DB::table('hotels')->get();

            $room = DB::table(self::$table_name)->find($id)[0];
            // HTTP 404.
            if ($room == null) {
                return ApiMessages::notFound();
            }

            // Return the view.
            return ApiMessages::success(RequestParametersHelper::addUserParameters(
                $user,
                [
                    "title" => "Modificar una habitación",
                    "is_update" => true,
                    "room" => $room,
                    "hotels" => InputHelpers::prepareHotelSelect($hotels, $room['hotel_id'])
                ]
            ));
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // POST /rooms
    public function store($_ = null)
    {
        try {
            $room = [
                'hotel_id' => Input::get('hotel_id'),
                'name' => strtoupper(Input::get('name')),
                'price' => Input::get('price'),
                'beds' => Input::get('beds'),
                'bathrooms' => Input::get('bathrooms'),
                'additional' => Input::get('additional'),
            ];

            DB::table(self::$table_name)->insert($room);
            return ApiMessages::created();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // PUT /rooms/(:id)
    public function update($_, $id = null)
    {
        try {
            $updated_room = [
                'hotel_id' => Input::get('hotel_id'),
                'name' => strtoupper(Input::get('name')),
                'price' => Input::get('price'),
                'beds' => Input::get('beds'),
                'bathrooms' => Input::get('bathrooms'),
                'additional' => Input::get('additional'),
            ];

            DB::table(self::$table_name)->update($id, $updated_room);
            return ApiMessages::updated();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
    // DELETE /rooms/(:id)
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                return ApiMessages::loginRequired();
            }
            // Solo permitir a administradores eliminar habitaciones.
            if ($user['is_admin'] == 0) {
                return ApiMessages::onlyAdmin("Editar hoteles.");
            }

            DB::table(self::$table_name)->delete($id);
            return ApiMessages::deleted();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
};
