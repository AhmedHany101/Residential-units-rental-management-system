<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Accounts_Ctrl;
use App\Http\Controllers\Admin\Apartness_Ctrl;
use App\Http\Controllers\Admin\CustomerCtrl;
use App\Http\Controllers\Admin\Expenses_Ctrl;
use App\Http\Controllers\Admin\Owner_ctrl;
use App\Http\Controllers\Admin\Reservation_Ctrl;
use App\Models\customer_info;
use App\Models\expense_data;

//Admin routes
//login page
Route::get('/',[Accounts_Ctrl::class,'login']);
Route::post('signin',[Accounts_Ctrl::class,'signin_form']);
Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard',[Accounts_Ctrl::class,'index']);
    Route::get('/add/new/apartment',[Apartness_Ctrl::class,'add_apartness_form']);
    Route::post('/save/apartment',[Apartness_Ctrl::class,'save_apartment_info'])->name('save');
    Route::get('/add/new/reservation',[Reservation_Ctrl::class,'add_new_reservation']);
    Route::post('/save/reservation', [Reservation_Ctrl::class, 'save_customer_data'])->name('save/reservation');
    Route::get('/reservations/{encryptedId}/details', [Reservation_Ctrl::class, 'reservation_details'])->name('reservations.details');
    Route::get('/reservations/data',[Reservation_Ctrl::class,'reservations_list']);
    Route::get('/close/{encryptedId}/reservation',[Reservation_Ctrl::class,'resvation_close']);
    Route::get('/history/reservations',[Reservation_Ctrl::class,'reservations_history']);
    Route::get('/apartness',[Apartness_Ctrl::class,'apartness_data']);
    Route::get('/apartness/edite/{apart_encryptedId}',[Apartness_Ctrl::class,'aprtment_details']);
    Route::post('/apartness/edite/data',[Apartness_Ctrl::class,'apartness_edite'])->name('editeApartness');
    Route::post('/reservations/filter', [Reservation_Ctrl::class,'dateFilter'])->name('apartness_edite');
    Route::post('/reservations/close/filter', [Reservation_Ctrl::class,'dateCloseFilter']);
    Route::get('/edite/{customerId}/information',[Reservation_Ctrl::class,'editeCustomerInfo']);
    Route::get('/delete/image/{id}/{type}', [Reservation_Ctrl::class, 'deleteImage'])->name('delete.image');
    Route::post('/edite/customer/information', [Reservation_Ctrl::class, 'editeCustomerData'])->name('edite/customer/data');
    Route::get('/edite/reservation/{encryptedId}/data', [Reservation_Ctrl::class, 'editeReservation'])->name('editeReservation');
    Route::post('/edite/data/reservation',[Reservation_Ctrl::class,'save_reservation_change'])->name('edite/data/reservation');
    Route::get('/add/new/expenses',[Expenses_Ctrl::class,'add_expenses']);
    Route::post('/save/epenses/data',[Expenses_Ctrl::class,'save_epenses_data'])->name('save/epenses/data');//************************* */
    Route::get('/expnses/report',[Expenses_Ctrl::class,'expeness_report']);
    Route::get('/aprtment/{encryptedId}/report',[Apartness_Ctrl::class,'apartment_reprt']);
    Route::get('/logout', [Accounts_Ctrl::class, 'logout'])->name('logout');
    Route::get('/owners',[Owner_ctrl::class,'ownerList']);
    Route::get('/Customers',[CustomerCtrl::class,'customer_list']);
    Route::get('/apartment/{encryptedId}/reports',[Owner_ctrl::class,'ownerApartness']);
    Route::get('/customer/reservation/{encryptedId}',[CustomerCtrl::class,'customer_resevation']);
    Route::get('/customer/info/{encryptedId}',[CustomerCtrl::class,'customer_info']);
    Route::get('/edite/owner/{ownerID}',[Owner_ctrl::class,'edite_owner_info']);
    Route::post('/edite/info/owner',[Owner_ctrl::class,'edite_owner_data'])->name('editeownerData');
    Route::get('/add/expness/{encryptedId}',[Expenses_Ctrl::class,'add_resrvation_expenses']);
    Route::post('/save/reservation/expness',[Expenses_Ctrl::class,'save_resrvation_expenses'])->name('add/resrvation/expenses');
    Route::post('/add/payment',[Owner_ctrl::class,'add_payment_to_admin_apartment']);
    Route::get('/delete/apartment/image/{image_id}',[Apartness_Ctrl::class,'delete_apartment_image']);
    Route::get('/add/staff',[Accounts_Ctrl::class,'add_stuff']);
    Route::post('add/new/user',[Accounts_Ctrl::class,'create_account']);
    Route::get('/add/cash',[Accounts_Ctrl::class,'admin_add_cash_from']);
    Route::post('/send/mony',[Accounts_Ctrl::class,'sendMony']);
    Route::get('/cash/report',[Accounts_Ctrl::class,'cash_report']);
    Route::get('/delete/reservation/{encryptedId}',[Reservation_Ctrl::class,'delete_reservation']);
    Route::get('/delete/expenses/{encryptedId}',[Expenses_Ctrl::class,'delete_expenses']);
    Route::get('/delete/reservation/expenese/{encryptedExpeneseId}',[Expenses_Ctrl::class,'delete_resrvation_expenses']);
});