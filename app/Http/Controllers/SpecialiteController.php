<?php

namespace App\Http\Controllers;

use App\specialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SpecialiteController extends Controller
{
    //Upload_icone *************************************
    function UplodFile($filename)
    {
        $path = public_path() . '/../uploadd/';
        if (isset($_FILES[$filename])) {
            $originalName = $_FILES[$filename]['name'];
            $ext = '.' . pathinfo($originalName, PATHINFO_EXTENSION);
            $generatedName = md5($_FILES[$filename]['tmp_name']) . $ext;
            $filePath = $path . $generatedName;
            if (!is_writable($path)) {
                return false;
            }

            if (move_uploaded_file($_FILES[$filename]['tmp_name'], $filePath)) {
                return $generatedName;
            }
        } else {
            return false;
        }
    }

    //Spec_Verification *************************************
    public function verifSpec($name)
    {
        $spec = specialite::where('name', '=', $name)->get();
        if (count($spec) > 0) {
            return false;
        } else {
            return true;
        }
    }

//Create_ Specialite *************************************
    function CreateSpe(Request $request)
    {
        if (empty($request->input('name'))
        ) {
            $result = array('error' => 'true', 'message' => 'verify required params name ');
            return response()->json($result, 201);

        } else {
            $res = $this->verifSpec($request->input('name'));
            if ($res) {
                $spec = new specialite();
                $spec->name = $request->input('name');
                $spec->status = '1';
                $res1 = $this->UplodFile('icone');
                if ($res1) {
                    $spec->icone = $res1;
                } else {
                    $spec->icone = "";
                }

                $spec->save();
                $specialite = array('error' => 'false', 'message' => 'You are successfully added this specialty', 'specialité' => $spec);
                return response()->json($specialite, 201);
            } else {
                $specialite = array('error' => 'true', 'message' => 'this name used by another specialty');
                return response()->json($specialite, 201);
            }
        }
    }

//Get_All_Specialite *************************************
    function AllSpec()
    {
        $spec = specialite::where('status','=','1')->get();
        $result = array('error' => 'false', 'specialites' => $spec);
        return response()->json($result, 201);

    }

    //Update_Specialite ************************************

    function UpdateUSpec(Request $request)
    {
        $id = $request->input("id_Spec");
        if (!empty($request->input("name"))) {
            DB::table('specialites')
                ->where('id', $id)
                ->update(['name' => $request->input("name")]);
        }
        $res = $this->UplodFile('icone');
        if ($res) {
            DB::table('specialites')
                ->where('id', $id)
                ->update(['icone' => $res]);

        }

        $specialites = array('error' => 'false', 'spec' => specialite::find($id));
        return response()->json($specialites, 201);

    }

    //Delete_Specialite *************************************
    function DeleteSpe($id)
    {
        DB::table('specialites')
            ->where('id', $id)
            ->update(['status' => '-1']);
        $result = array('error' => 'false', 'message' => "specialty deleted");
        return response()->json($result, 201);
    }

}
