<?php

namespace App\Http\Controllers;

use App\medecin;
use App\rating;
use App\specialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MedecinController extends Controller
{
    //Medecin_Verification *************************************
    public function verifMed($name)
    {
        $med = medecin::where('name', '=', $name)->get();
        if (count($med) > 0) {
            return false;
        } else {
            return true;
        }
    }

//Create_Medecin *************************************
    function CreateMed(Request $request)
    {
        if (empty($request->input('name')) ||
            empty($request->input('phone')) ||
            empty($request->input('email')) ||
            empty($request->input('id_spe'))
        ) {
            $result = array('error' => 'true', 'message' => 'verify required params name phone email time id_spe ');
            return response()->json($result, 201);

        } else {
            $res = $this->verifMed($request->input('name'));
            if ($res) {
                $med = new medecin();
                $med->name = $request->input('name');
                $med->phone = $request->input('phone');
                $med->email = $request->input('email');
                $med->time = "[{'id':'7','name':'Sunday','name_ar':'الاحد','status':true,'start_hour':'8:00 am','end_hours':'5:00 pm'},{'id':'1','name':'Monday','name_ar':'الاثنين','status':true,'start_hour':'8:00 am','end_hours':'5:00 pm'},{'id':'2','name':'Tuesday','name_ar':'الثلاثاء','status':true,'start_hour':'8:00 am','end_hours':'5:00 pm'},{'id':'3','name':'Wednesday','name_ar':'الاربعاء','status':true,'start_hour':'8:00 am','end_hours':'5:00 pm'},{'id':'4','name':'Thursday','name_ar':'الخميس','status':false,'start_hour':'8:00 am','end_hours':'5:00 pm'},{'id':'5','name':'Friday','name_ar':'الجمعة','status':false,'start_hour':'8:00 am','end_hours':'5:00 pm'},{'id':'6','name':'Saturday','name_ar':'السبت','status':true,'start_hour':'8:00 am','end_hours':'5:00 pm'}]
";
                $med->id_spe = $request->input('id_spe');
                $med->status = '1';
                $med->save();


                $x = str_replace("'", "\"", $med->time);
                $med->time = json_decode($x, TRUE);

                $medcin = array('error' => 'false', 'message' => 'You are successfully registered', 'med' => $med);
                return response()->json($medcin, 201);
            } else {
                $medcin = array('error' => 'true', 'message' => 'this name used by another doctor');
                return response()->json($medcin, 201);
            }
        }
    }

    //Update_Medecin************************************

    function UpdateMed(Request $request)
    {
        if (empty($request->input('id_med'))) {
            $result = array('error' => 'true', 'message' => 'verify required param id_med');
            return response()->json($result, 201);

        } else {
            $id = $request->input("id_med");

            if (!empty($request->input("name"))) {
                DB::table('medecins')
                    ->where('id', $id)
                    ->update(['name' => $request->input("name")]);
            }
            if (!empty($request->input("phone"))) {
                DB::table('medecins')
                    ->where('id', $id)
                    ->update(['phone' => $request->input("phone")]);
            }

            if (!empty($request->input("time"))) {
                DB::table('medecins')
                    ->where('id', $id)
                    ->update(['time' => $request->input("time")]);
            }




        }
        $utilisateur = array('error' => 'false', 'medecin' => medecin::find($id));
        return response()->json($utilisateur, 201);
    }

    //GET_Medecin_By_Id************************************

    function GetMed($id)
    {
        $med = medecin::find($id);
      if( $med->status=='1'){
        $x = str_replace("'", "\"", $med->time);
          $med->time = json_decode($x, TRUE);

          $med->specialite =specialite::find($med->id_spe);
        $result = array('error' => 'false', 'medecin' => $med);
        return response()->json($result, 201);
    }
    else {
        $result = array('error' => 'true', 'medecin' =>'this doctor is notfound');
        return response()->json($result, 201);
    }
    }
function MedBySpe($id_spe){

    $user = medecin::where('id_spe',$id_spe)->get();
    for($i=0;$i<sizeof($user);$i++){
     //  $avg=rating::avg('score')->where('id_med',  $user[$i]->id)->get();
        $avg = DB::table('ratings')
            ->where('id_med', $user[$i]->id)
            ->avg('score');
        if ($avg == null){
            $user[$i]->rating = 0  ;
        }
       else{
           $user[$i]->rating =$avg ;
       }
        $user[$i]->specialite =specialite::find($user[$i]->id_spe);
        $x = str_replace("'", "\"",$user[$i]->time);
        $user[$i]->time = json_decode($x, TRUE);

    }

    $result = array('error' => 'false', 'medecin' => $user);
    return response()->json($result, 201);



}
    //Delete_medecin *************************************
    function DeleteMed($id)
    {
        DB::table('medecins')
            ->where('id', $id)
            ->update(['status' => '-1']);
        $result = array('error' => 'false', 'message' => "doctor deleted");
        return response()->json($result, 201);
    }

}
