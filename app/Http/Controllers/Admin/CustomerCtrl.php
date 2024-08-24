<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\customer_info;
use App\Models\reservation_data;
use App\Http\Controllers\Controller;
use App\Models\apartness;
use App\Models\paper_info;
use App\Models\reservation_payment;
use Illuminate\Support\Facades\Crypt;

class CustomerCtrl extends Controller
{
    public function customer_list()
    {
        $customer_data=customer_info::get();
        $reservation_data=reservation_data::get();
        
        return view('Admin_pages.customer_list',compact('customer_data','reservation_data'));
    }
    public function customer_resevation($encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $customer=customer_info::find($id);
        $customerReservations = reservation_data::join('apartnesses', 'reservation_datas.department_id', '=', 'apartnesses.id')
            ->join('reservation_payments', 'reservation_datas.id', '=', 'reservation_payments.reservation_id')
            ->where('reservation_datas.customer_id', $id)
            ->select(
                'reservation_datas.*',
                'apartnesses.apartness_no as apartness_no',
                'apartnesses.sys_type as apartment_type',
                'reservation_payments.total',
                'reservation_payments.payed',
                'reservation_payments.remaining',
                'reservation_payments.payment_method',

            )
            ->get();
    
        if (!$customerReservations->isEmpty()) {
            return view('Admin_pages.customer_resevations', compact('customerReservations','customer'));
        } else {
            return redirect()->back()->with('error', 'No reservations found for the customer.');
        }
    }
    public function customer_info($encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $customer_info=customer_info::find($id);
        $reservation_paper = paper_info::where('customer_id', $customer_info->id)->get();
        return view('Admin_pages.customer_info', compact('customer_info', 'reservation_paper'));

    }
}
