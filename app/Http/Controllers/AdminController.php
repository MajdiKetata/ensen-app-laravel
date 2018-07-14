<?php

namespace App\Http\Controllers;

use App\admin;
use App\medecin;
use App\rating;
use App\reservation;
use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //Upload_photo *************************************
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
//varify_Email_Admin***********************************

    public function verifmail($mail)
    {
        $admin = DB::table('admins')->where('email', '=', $mail)->get();
        if (count($admin) > 0) {
            return false;
        } else {
            return true;
        }
    }
//Login_Admin***********************************
    function loginAdmin(Request $request)
    {
        if (empty($request->input('email')) || empty($request->input('password'))) {
            $result = array('error' => 'true', 'message' => 'verify required params email password ');
            return response()->json($result, 201);

        } else {
            $admin = admin::where('email', '=', $request->input('email'))->orWhere('username', '=', $request->input('email'))->get();
            if (count($admin) > 0) {

                if (password_verify($request->input('password'), $admin[0]->password)) {
                    $utilisateur = array('error' => 'false', 'admin' => $admin[0]);
                    return response()->json($utilisateur, 201);
                } else {
                    $utilisateur = array('error' => 'true', 'message' => 'Sorry, this password incorrect');
                    return response()->json($utilisateur, 201);
                }
            } else {
                $utilisateur = array('error' => 'true', 'message' => 'Sorry, email or password are incorrect');
                return response()->json($utilisateur, 201);
            }
        }

    }
//Apdate_Admin***********************************

    function UpdateAdmin(Request $request)
    {
        $id = $request->input("id_admin");
        if (!empty($request->input("username"))) {
            DB::table('admins')
                ->where('id', $id)
                ->update(['username' => $request->input("username")]);
        }
        if (!empty($request->input("password"))) {
            DB::table('admins')
                ->where('id', $id)
                ->update(['password' => $request->input("password")]);
        }

        $res = $this->UplodFile('photo');
        if ($res) {
            DB::table('admins')
                ->where('id', $id)
                ->update(['photo' => $res]);

        }

        $utilisateur = array('error' => 'false', 'user' => admin::find($id));
        return response()->json($utilisateur, 201);

    }


// Create_Admin***********************************

    function Createadmin(Request $request)
    {
        if (empty($request->input('username')) ||
            (empty($request->input('email'))) ||
            empty($request->input('password'))) {
            $result = array('error' => 'true', 'message' => 'verify required params username email password phone');
            return response()->json($result, 201);

        } else {
            $res = $this->verifmail($request->input('username'));
            if ($res) {
                $admin = new admin();
                $admin->username = $request->input('username');
                $admin->email = $request->input('email');
                $admin->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
                $admin->photo = "";
                $admin->save();
                $utilisateur = array('error' => 'false', 'message' => 'You are successfully registered', 'Admin' => $admin);
                return response()->json($utilisateur, 201);
            } else {
                $utilisateur = array('error' => 'true', 'message' => 'this mail used by another Admin');
                return response()->json($utilisateur, 201);
            }
        }
    }




    function statistic()
    {
        $med = medecin::all();
        $user = users::all();
        $rat = rating::all();
        $reserv = reservation::all();
        $utilisateur = array('error' => 'false', 'user' => sizeof($user),
            'medecin' => sizeof($med),
            'rating' => sizeof($rat),
            'reservation' => sizeof($reserv));
        return response()->json($utilisateur, 201);


    }
}
