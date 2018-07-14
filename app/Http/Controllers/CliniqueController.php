<?php

namespace App\Http\Controllers;
use App\clinique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CliniqueController extends Controller
{


//nameclinique_Verification *************************************
    public function verifname($name)
    {
        $cl = clinique::where('name', '=', $name)->get();
        if (count($cl) > 0) {
            return false;
        } else {
            return true;
        }
    }
    //Create_Clinique *************************************
    function CreateClinique(Request $request)
    {
        if (empty($request->input('name')) ||
            (empty($request->input('adresse'))) ||
            empty($request->input('lat')) ||
            empty($request->input('long')) ||
            empty($request->input('phone')) ||
            empty($request->input('fax')) ||
            empty($request->input('email'))) {
            $result = array('error' => 'true', 'message' => 'verify required params name adresse lat long phone fax email');
            return response()->json($result, 201);

        } else {
            $res = $this->verifname($request->input('name'));
            if ($res) {
                $clin = new clinique();
                $clin->name = $request->input('name');
                $clin->adresse = $request->input('adresse');
                $clin->lat = $request->input('lat');
                $clin->long = $request->input('long');
                $clin->phone = $request->input('phone');
                $clin->fax = $request->input('fax');
                $clin->email = $request->input('email');
                $clin->save();
                $utilisateur = array('error' => 'false', 'message' => 'You are successfully added this  clinique', 'clinique' => $clin);
                return response()->json($utilisateur, 201);
            } else {
                $utilisateur = array('error' => 'true', 'message' => 'this name used by another clinique');
                return response()->json($utilisateur, 201);
            }
        }
    }

//Get_Clinique_By_Id *************************************

    function GetClinique($id)
    {
        $clinique = clinique::find($id);
        $result = array('error' => 'false', 'clinique' => $clinique);
        return response()->json($result, 201);

    }
    //Get_Clinique_By_Id *************************************

    function allClinique()
    {
        $clinique = clinique::all();
        $result = array('error' => 'false', 'clinique' => $clinique[0]);
        return response()->json($result, 201);

    }
    //Update_Clinique************************************

    function UpdateClinique(Request $request)
    {
        $id = $request->input("id_clinique");
        if (!empty($request->input("name"))) {
            DB::table('cliniques')
                ->where('id', $id)
                ->update(['name' => $request->input("name")]);
        }
        if (!empty($request->input("phone"))) {
            DB::table('cliniques')
                ->where('id', $id)
                ->update(['phone' => $request->input("phone")]);
        }
        if (!empty($request->input("fax"))) {
            DB::table('cliniques')
                ->where('id', $id)
                ->update(['fax' => $request->input("fax")]);
        }
        if (!empty($request->input("email"))) {
            DB::table('cliniques')
                ->where('id', $id)
                ->update(['email' => $request->input("email")]);
        }
        if (!empty($request->input("adresse"))) {
            DB::table('cliniques')
                ->where('id', $id)
                ->update(['adresse' => $request->input("adresse")]);
        }
        $clin = array('error' => 'false', 'Clinique' => Clinique::find($id));
        return response()->json($clin, 201);

    }


}
