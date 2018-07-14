<?php

namespace App\Http\Controllers;

use App\rapport;
use App\users;
use Illuminate\Http\Request;

class RapportController extends Controller
{
//Create_Rapport*************************************
    function CreateRapport(Request $request)
    {
        if (empty($request->input('id_user')) ||
            empty($request->input('message')) ||
            empty($request->input('title')) ) {
            $result = array('error' => 'true', 'message' => 'verify required params id_user message title');
            return response()->json($result, 201);

        } else {
            $rapp = new rapport();
            $rapp->id_user = $request->input('id_user');
            $rapp->message = $request->input('message');
            $rapp->title = $request->input('title');
            $rapp->save();
            $Rapport = array('error' => 'false', 'message' => 'You are successfully added this rapport', 'rapport' => $rapp);
            return response()->json($Rapport, 201);
        }
    }


    //Get_Rapport_ByID*************************************
    function GetRapID($id)
    {
        $rap = rapport::find($id);
            $rap->user=users::find ($rap->id_user);
        $result = array('error' => 'false', 'rating' => $rap);
            return response()->json($result, 201);
    }
    //Get_All_Rapport*************************************
    function GetALLRap()
    {
        $rap = rapport::all();
        for ($i=0;$i<sizeof($rap);$i++){
            $rap[$i]->user=users::find ($rap[$i]->id_user);
        }
        $result = array('error' => 'false', 'rating' => $rap);
        return response()->json($result, 201);

    }
}
