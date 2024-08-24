<?php

namespace App\Http\Controllers\Admin;

use App\Models\owner;
use App\Models\apartness;
use App\Models\expense_data;
use Illuminate\Http\Request;
use App\Models\reservation_data;
use App\Models\reservation_payment;
use App\Http\Controllers\Controller;
use App\Models\apartness_x;
use App\Models\owner_apartment_report;
use App\Models\owner_private_account;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class Owner_ctrl extends Controller
{
    public function ownerList()
    {
        $owners = owner::get();
        $apartment = apartness::get();
        return view('Admin_pages.owners_list', compact('owners', 'apartment'));
    }
    public function ownerApartness($encrypted_id)
    {
        $id = Crypt::decryptString($encrypted_id);
        $apartness = apartness::where('owner_id', $id)->get();
        if ($apartness) {
            $resevations = reservation_data::get();
            $expness = expense_data::whereIn('expense_type', [3, 4])->get();
            $resevationsPaymentInfo = reservation_payment::get();
            $users_name = User::where('type', 'staff$9Ajdsd!23')->get();
            $general_report=owner_apartment_report::get();
            $apartness_payment = apartness_x::get();
            $privat_apartness_report=owner_private_account::get();
            return view('Admin_pages.owner_apartment', compact('apartness', 'resevations', 'expness', 'resevationsPaymentInfo', 'apartness_payment', 'users_name','general_report','privat_apartness_report'));
        } else {
            return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
        }
    }
    public function edite_owner_info($ownerID)
    {
        $id = Crypt::decryptString($ownerID);
        $ownerinfo = owner::find($id);
        return view('Admin_pages.owner_info_edite', compact('ownerinfo'));
    }
    public function edite_owner_data(Request $request)
    {
        $validator0 = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $ownerinfo = owner::find($request->id);
        if ($ownerinfo) {
            if ($validator0->fails()) {
                foreach ($validator0->errors()->all() as $error) {
                    return redirect()->back()->with('errorMesg', $error);
                }
            } else {
                $ownerinfo->name = $request->name;
                $ownerinfo->phone = $request->phone;
                $ownerinfo->save();
                return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
            }
        } else {
            return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
        }
    }
    public function add_payment_to_admin_apartment(Request $request)
    {
        try {
            $payment = apartness_x::find($request->id);
            $apartment = apartness::where('id', $payment->apartness_id)->first();
            $apartness_admin_owner = new owner_apartment_report();
            if ($payment) {
                $payment->total_account = $payment->total_account - $request->payed_cost;
                $payment->save();
                $apartness_admin_owner->apartment_id = $apartment->id;
                $apartness_admin_owner->amount = $request->payed_cost;
                $apartness_admin_owner->total_account = $payment->total_account;
                $apartness_admin_owner->data_entry_name = $request->staff_name;
                $apartness_admin_owner->process_type=1;
                $apartness_admin_owner->save();
                return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
}
