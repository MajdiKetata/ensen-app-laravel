<?php

namespace App\Http\Controllers;

use App\admin;
use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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

//Emails_Verification *************************************
    public function verifmail($mail)
    {
        $user = users::where('email', '=', $mail)->get();
        $admin=admin::where('email', '=', $mail)->get();
        if (count($user) + count($admin)> 0) {
            return false;
        } else {
            return true;
        }
    }

//Login*************************************
    function login(Request $request)
    {
        if (empty($request->input('email')) || empty($request->input('password'))) {
            $result = array('error' => 'true', 'message' => 'verify required params email password ');
            return response()->json($result, 201);

        } else {
            $user = users::where('email', '=', $request->input('email'))->orWhere('username', '=', $request->input('email'))->get();
            if (count($user) > 0) {

                if (password_verify($request->input('password'), $user[0]->password)) {
                    $utilisateur = array('error' => 'false', 'user' => $user[0]);
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

//Create_User *************************************
function CreateU(Request $request)
{
    if (empty($request->input('username')) ||
        (empty($request->input('email'))) ||
        empty($request->input('password')) ||
        empty($request->input('gsm')) ||
        empty($request->input('phone'))) {
        $result = array('error' => 'true', 'message' => 'verify required params username email password gsm phone');
        return response()->json($result, 201);

    } else {
        $res = $this->verifmail($request->input('username'));
        if ($res) {
            $user = new users();
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
            $user->phone = $request->input('phone');
            $user->gsm = $request->input('gsm');

            $user->status = '1';
            $user->photo = "";
            $user->save();
            $utilisateur = array('error' => 'false', 'message' => 'You are successfully registered', 'user' => $user);
            return response()->json($utilisateur, 201);
        } else {
            $utilisateur = array('error' => 'true', 'message' => 'this mail used by another user');
            return response()->json($utilisateur, 201);
        }
    }
}
//Delete_User *************************************
    function DeleteUser($id)
    {
        DB::table('users')
            ->where('id', $id)
        ->update(['status' => '-1']);

        $result = array('error' => 'false', 'message' => "user deleted");
        return response()->json($result, 201);
    }
//Get_User_By_Id *************************************

    function GetUser($id)
    {
        $user = users::where('id',$id)->where('status','>','0')->get();
        $result = array('error' => 'false', 'user' => $user);
        return response()->json($result, 201);

    }

    //Get_All_User*************************************

    function AllUser()
    {
        $user = users::all();
        $result = array('error' => 'false', 'users' => $user);
        return response()->json($result, 201);

    }

//Update_User************************************

    function UpdateUser(Request $request)
    {
        $id = $request->input("id_user");
        if (!empty($request->input("username"))) {
            DB::table('users')
                ->where('id', $id)
                ->update(['username' => $request->input("username")]);
        }
        if (!empty($request->input("password"))) {
            DB::table('users')
                ->where('id', $id)
                ->update(['password' => $request->input("password")]);
        }
        if (!empty($request->input("phone"))) {
            DB::table('users')
                ->where('id', $id)
                ->update(['phone' => $request->input("phone")]);
        }

        $res = $this->UplodFile('photo');
        if ($res) {
            DB::table('users')
                ->where('id', $id)
                ->update(['photo' => $res]);

        }

        $utilisateur = array('error' => 'false', 'user' => users::find($id));
        return response()->json($utilisateur, 201);

    }

    //Forget_Password*************************************

    function Forget_Password(Request $request){

    }



}