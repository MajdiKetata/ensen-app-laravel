<?php

namespace App\Http\Controllers;

use App\admin;
use App\msg_reservation;
use App\users;
use Illuminate\Http\Request;

class MsgReservationController extends Controller
{
//Create_Message_Reservation*************************************
    function CreateMsgR(Request $request)
    {
        if (empty($request->input('id_reservation')) ||
            empty($request->input('id_user')) ||
            empty($request->input('message')) ||
            empty($request->input('is_user')) ) {
            $result = array('error' => 'true', 'message' => 'verify required params id_reserv id_user message is_user');
            return response()->json($result, 201);

        } else {

                $msgr = new msg_reservation();
                $msgr->id_reservation = $request->input('id_reservation');
                $msgr->id_user = $request->input('id_user');
                $msgr->message = $request->input('message');
                $msgr->is_user = $request->input('is_user');
                 $msgr->save();
                $messageR = array('error' => 'false', 'message' => 'You are successfully added this msg', 'msgr' => $msgr);
                return response()->json($messageR, 201);
            }
        }
//Get_MsgReserv_By_Idreserv *************************************

    function listMsgR($id_reservation)
    {
        $msgr = msg_reservation::where('id_reservation',$id_reservation)->get();
        for ($i=0;$i<sizeof($msgr);$i++){
            if($msgr[$i]->is_user==('true')){

                $msgr[$i]->user=users::find ($msgr[$i]->id_user);
            }
        else {
            $msgr[$i]->user=admin::find ($msgr[$i]->id_user);
        }

        }
        $result = array('error' => 'false', 'listMsgR' => $msgr);
        return response()->json($result, 201);

    }

}
