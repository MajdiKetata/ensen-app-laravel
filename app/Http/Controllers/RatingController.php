<?php

namespace App\Http\Controllers;

use App\rating;
use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{



//Create_Rating *************************************
    function CreateRating(Request $request)
    {
        if (empty($request->input('id_med')) ||
            empty($request->input('id_user')) ||
            empty($request->input('score')) ||
            empty($request->input('comment'))) {
            $result = array('error' => 'true', 'message' => 'verify required params id_med id_user comment id_spe status');
            return response()->json($result, 201);

        } else {

                $rat = new rating();
                $rat->id_med = $request->input('id_med');
                $rat->id_user = $request->input('id_user');
                $rat->score = $request->input('score');
                $rat->comment = $request->input('comment');
                $rat->save();
                $rating = array('error' => 'false', 'message' => 'rating with successful', 'ratting' => $rat);
                return response()->json($rating, 201);
            }
        }


//GetRating_By_Id medecin *************************************

    function GetRating($id_med)
    {
        $rating = rating::where('id_med',$id_med)->get();
        for ($i=0;$i<sizeof($rating);$i++){
            $rating[$i]->user=users::find ($rating[$i]->id_user);
        }
        $result = array('error' => 'false', 'rating' => $rating);
        return response()->json($result, 201);

    }


}
