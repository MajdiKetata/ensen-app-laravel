<?php
Route::get('uploadd/{filename}', function ($filename) {
    $path = storage_path('public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/', function () {
    return view('welcome');
});
///UserController
Route::post('/CreateUser', ['uses' => 'UsersController@CreateU']);
Route::post('/login', ['uses' => 'UsersController@login']);
Route::post('/UpdateUser', ['uses' => 'UsersController@UpdateUser']);
Route::post('/DeleteUser/{id}', ['uses' => 'UsersController@DeleteUser']);
Route::get('/GetUser/{id}', ['uses' => 'UsersController@GetUser']);
Route::get('/AllUser', ['uses' => 'UsersController@AllUser']);

///CliniqueController
Route::get('/GetClinique/{id}', ['uses' => 'CliniqueController@GetClinique']);
Route::get('/allClinique', ['uses' => 'CliniqueController@allClinique']);
Route::post('/UpdateClinique', ['uses' => 'CliniqueController@UpdateClinique']);
Route::post('/CreateClinique', ['uses' => 'CliniqueController@CreateClinique']);

///MedecinController
Route::post('/CreateMed', ['uses' => 'MedecinController@CreateMed']);
Route::post('/UpdateMed', ['uses' => 'MedecinController@UpdateMed']);
Route::get('/GetMed/{id}', ['uses' => 'MedecinController@GetMed']);
Route::get('/MedBySpe/{id_spe}', ['uses' => 'MedecinController@MedBySpe']);
Route::get('/DeleteMed/{id}', ['uses' => 'MedecinController@DeleteMed']);

///SpecialiteController
Route::post('/CreateSpe', ['uses' => 'SpecialiteController@CreateSpe']);
Route::get('/GetAllSpec', ['uses' => 'SpecialiteController@AllSpec']);
Route::post('/UpdateUSpec', ['uses' => 'SpecialiteController@UpdateUSpec']);
Route::get('/DeleteSpe/{id}', ['uses' => 'SpecialiteController@DeleteSpe']);

///RatingController
Route::post('/CreateRating', ['uses' => 'RatingController@CreateRating']);
Route::get('/GetRating/{id_med}', ['uses' => 'RatingController@GetRating']);

///ReservationController
Route::post('/CreateReserv', ['uses' => 'ReservationController@CreateReserv']);
Route::post('/Update_Reserv', ['uses' => 'ReservationController@Update_Reserv']);
Route::get('/GetReservU/{id_user}', ['uses' => 'ReservationController@GetReservU']);
Route::get('/GetReservM/{id_med}', ['uses' => 'ReservationController@GetReservM']);

///AdminController
Route::post('/Createadmin', ['uses' => 'AdminController@Createadmin']);
Route::post('/loginAdmin', ['uses' => 'AdminController@loginAdmin']);
Route::post('/UpdateAdmin', ['uses' => 'AdminController@UpdateAdmin']);
Route::get('/statistic', ['uses' => 'AdminController@statistic']);

///MsgReservationController
Route::post('/CreateMsgR', ['uses' => 'MsgReservationController@CreateMsgR']);
Route::get('/listMsgR/{id_reservation}', ['uses' => 'MsgReservationController@listMsgR']);

///RapportController
Route::post('/CreateRapport', ['uses' => 'RapportController@CreateRapport']);
Route::get('/GetRapID/{id}', ['uses' => 'RapportController@GetRapID']);
Route::get('/GetALLRap', ['uses' => 'RapportController@GetALLRap']);
