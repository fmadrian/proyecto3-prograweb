<?php
class InputHelpers
{

    public static function prepareHotelSelect($hotels, $hotel_id)
    {

        // Buscar elemento seleccionado en el select.
        $hotels = array_map(function ($item) use ($hotel_id) {
            if ($item['id'] == $hotel_id)
                $item['is_selected'] = "selected";
            else
                $item['is_selected'] = "";

            return $item;
        }, $hotels);

        // Cambiar name por hotel_name
        $hotels = array_map(function ($item) {
            $item["hotel_name_select"] = $item["name"];
            $item["hotel_id_select"] = $item["id"];
            unset($item["id"]);
            unset($item["name"]);
            return $item;
        }, $hotels);


        return $hotels;
    }
}

class RoomHelpers
{
    // Añade nombre de habitaciones a los hoteles.
    public static function addHotelNames($rooms)
    {
        return array_map(function ($item) {
            // Busca por el ID del hotel el nombre de cada hotel, lo regresa como parte del objeto (rooms).
            $hotel = DB::table("hotels")->find($item['hotel_id'])[0];
            if ($hotel != null)
                $item["hotel_name"] = $hotel['name'];
            return $item;
        }, $rooms);
    }
    // Añade nombre de hotel a una habitacion.
    public static function addHotelName($room)
    {
        // Busca por el ID del hotel el nombre de cada hotel, lo regresa como parte del objeto (rooms).
        $hotel = DB::table("hotels")->find($room['hotel_id'])[0];
        if ($hotel != null)
            $room["hotel_name"] = $hotel['name'];
        return $room;
    }
}
