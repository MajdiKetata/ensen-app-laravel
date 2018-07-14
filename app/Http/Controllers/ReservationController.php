<?php

namespace App\Http\Controllers;

use App\reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
//Create_Reservation *************************************
    function CreateReserv(Request $request)
    {
        if (empty($request->input('id_med')) ||
            (empty($request->input('id_user'))) ||
            empty($request->input('date_time')) ||
            empty($request->input('cause')) ||

            empty($request->input('condition'))   ) {
            $result = array('error' => 'true', 'message' => 'verify required params id_med id_user date_time cause  condition');
            return response()->json($result, 201);

        } else {

                $user = new reservation();
                $user->id_med = $request->input('id_med');
                $user->id_user = $request->input('id_user');
                $user->date_time = $request->input('date_time');
                $user->cause = $request->input('cause');
                $user->status ='1';
                $user->condition = $request->input('condition');
                $user->save();
                $utilisateur = array('error' => 'false', 'message' => 'You are successfully reserved', 'user' => $user);
                return response()->json($utilisateur, 201);

        }
    }

    //update_Reservation************************************

    function Update_Reserv(Request $request)
    {
        $id = $request->input("id_reserv");
        if (!empty($request->input("id_med"))) {
            DB::table('reservations')
                ->where('id', $id)
                ->update(['id_med' => $request->input("id_med")]);
        }
        if (!empty($request->input("id_user"))) {
            DB::table('reservations')
                ->where('id', $id)
                ->update(['id_user' => $request->input("id_user")]);
        }

        if (!empty($request->input("date_time"))) {
            DB::table('reservations')
                ->where('id', $id)
                ->update(['date_time' => $request->input("date_time")]);
        }

        if (!empty($request->input("cause"))) {
            DB::table('reservations')
                ->where('id', $id)
                ->update(['cause' => $request->input("cause")]);
        }



        if (!empty($request->input("condition"))) {
            DB::table('reservations')
                ->where('id', $id)
                ->update(['condition' => $request->input("condition")]);
        }
        $utilisateur = array('error' => 'false', 'reserv' => reservation::find($id));
        return response()->json($utilisateur, 201);

    }
//Get_Reservation_By_Id_user *************************************

    function GetReservU($id_user)
    {
        $reserv = reservation::where('id_user',$id_user)->get();
        $result = array('error' => 'false', 'reservU' => $reserv);
        return response()->json($result, 201);

    }
//Get_Reservation_By_Id_med *************************************

    function GetReservM($id_med)
    {
        $reserv = reservation::where('id_med',$id_med)->get();
        $result = array('error' => 'false', 'reservMed' => $reserv);
        return response()->json($result, 201);

    }

}
