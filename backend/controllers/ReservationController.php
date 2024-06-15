<?php
require_once("util/RequestParametersHelper.php");
require_once("util/ApiMessages.php");

class ReservationController extends Controller
{
    private static $table_name = 'reservations';

    // POST /reservations
    public function store($_ = null)
    {
        try {
            // Solo usuarios autenticados pueden crear una reservación.
            if (!Auth::check()) {
                return ApiMessages::loginRequired();
            }
            $user = Auth::user();
            $room = DB::table('rooms')->find(Input::get('room_id'))[0];

            $duration = Input::get('duration');
            $reservation = [
                'room_id' => $room['id'],
                'user_id' => $user['id'],
                'duration' => $duration,
                'total_price' => $duration * $room['price'],
            ];

            DB::table(self::$table_name)->insert($reservation);
            return ApiMessages::created();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }

    // DELETE /reservations/(:id)
    public function destroy($id)
    {
        try {
            $reservation = DB::table(self::$table_name)->find($id)[0];
            $user = Auth::user();

            // Solo usuario que hizo la reservación o un administrador puede eliminarla.
            if ($reservation['user_id'] != $user['id'] && !$user['is_admin'])
                return ApiMessages::userError("Solo usuario que hizo la reservación o un administrador puede eliminarla.");

            DB::table(self::$table_name)->delete($id);
            return ApiMessages::deleted();
        } catch (Exception $e) {
            return ApiMessages::internalServerError($e);
        }
    }
};
