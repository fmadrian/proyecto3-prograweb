<?php
require_once("util/RequestParametersHelper.php");
require_once("util/ApiMessages.php");
require_once("util/OtherHelpers.php");
class SearchController extends Controller
{
    public function search()
    {
        try {
            $query_type = strtolower(Input::get('query_type'));
            // Obtiene query en mayúscula (necesario para comparar).
            $query = strtoupper(Input::get('query'));


            // Buscar por hotel
            if ($query_type == 'hotels') {
                // Forma de búsqueda 1: Devolver resultados por ORM.
                // $hotels = DB::table('hotels')->where('name ', "=", strtoupper($query))->get();
                
                // Forma de búsqueda 2: Devuelve todos los hoteles y filtrar los que no tienen el query.
                $hotels = DB::table('hotels')->get();
                $hotels = array_filter($hotels, function ($hotel) use ($query) {
                    // PHP 8+
                    // return str_contains($hotel['name'], $query) == true;

                    // PHP 7
                    return strpos($hotel['name'],$query) !== false;
                });
                return ApiMessages::success(RequestParametersHelper::addUserParameters(Auth::user(), [
                    "title" => "Resultado de la búsqueda",
                    "hotels" => $hotels,
                    "has_hotels" => count($hotels) > 0
                ]));
            } elseif ($query_type == 'rooms') {
                // Forma de búsqueda 1: Devolver habitaciones solo usando ORM.
                // $rooms = DB::table('rooms')->where('name', "=", strtoupper($query))->get();
                
                // Forma de búsqueda 2: Devuelve todos las habitaciones y filtrar los que no tienen el query.
                // $rooms = DB::table('rooms')->where('name', "=", strtoupper($query))->get();
                $rooms = DB::table('rooms')->get();
                $rooms = array_filter($rooms, function ($room) use ($query) {
                    // PHP 8+
                    // return str_contains($room['name'], $query) == true;
                    
                    // PHP 7
                    return strpos($room['name'],$query) !== false ;
                });
                
                // Añade información de hoteles.
                $rooms = RoomHelpers::addHotelNames($rooms);

                return ApiMessages::success(RequestParametersHelper::addUserParameters(Auth::user(), [
                    "title" => "Resultado de la búsqueda",
                    "rooms" => array_merge($rooms,[]),
                    "has_rooms" => count($rooms) > 0
                ]));
            } else {
                return ApiMessages::userError("No se introdujo tipo de consulta");
            }
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
}
