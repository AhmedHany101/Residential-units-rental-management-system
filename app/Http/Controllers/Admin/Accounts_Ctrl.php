<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\withdraw;
use Illuminate\Http\Request;
use App\Models\admin_payment;
use App\Models\customer_info;
use App\Models\reservation_data;
use Illuminate\Support\Facades\DB;
use App\Models\reservation_payment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class Accounts_Ctrl extends Controller
{
    //index
    public function index()
    {
        // Get the number of reservations for the current month
        $reservation_this_month = reservation_data::whereMonth('created_at', date('m'))->where('reservation_status', 1)
            ->whereYear('created_at', date('Y'))
            ->count();
        $total_reservation = reservation_data::where('reservation_status', 1)
            ->count();
        $customer_number = customer_info::count();
        $total = 0;
        $main_total = reservation_payment::sum('payed');
        $adminPayment = admin_payment::sum('amount');
        $withdraw = withdraw::sum('withdraw_amunt');
        $total = $adminPayment - $withdraw;
        $resrvation = reservation_data::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))->get();

        return view('Admin_pages.index', compact('reservation_this_month', 'total_reservation', 'customer_number', 'total', 'resrvation', 'main_total'));
    }
    //login page
    public function login()
    {
        return view('Admin_pages.login');
    }
    //sinup_form

    public function create_account(Request $req)
    {
        try {
            $validatedData = $req->validate([
                'name' => 'required',
                'phone' => 'required|unique:users',
                'password' => 'required',
                'user_type' => 'required|in:admin,user',
            ], [
                'name.required' => 'الاسم مطلوب',
                'phone.required' => 'رقم الهاتف مطلوب',
                'phone.unique' => 'رقم الهاتف مسجل مسبقًا',
                'password.required' => 'كلمة المرور مطلوبة',
                'user_type.required' => 'نوع المستخدم مطلوب',
            ]);

            $user = new User();
            $admin=env('ROLE_AS_ADMIN');
            $Admintype = env('Admintype');
            $userType=env('userType');
            $user->name = $validatedData['name'];
            $user->phone = $validatedData['phone'];
            $user->password = Hash::make($validatedData['password']);
            $user->role_as=$admin;
            if($req->user_type == "admin")
            {
                $user->type=$Admintype;
            }elseif($req->user_type == "user"){
                $user->type=$userType;
            }
            else{
                return redirect()->back()->with('errorMesg', 'رقم الهاتف أو كلمة المرور غير صالحة.');

            }
            $user->save();

            return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    //login function
    public function signin_form(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required',
                'password' => 'required',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب',
                'password.required' => 'كلمة المرور مطلوبة',
            ]);

            $credentials = $request->only('phone', 'password');

            if (Auth::attempt($credentials)) {
                if (Auth::user()) {
                    $roleAsAdmin = env('ROLE_AS_ADMIN');

                    if (Auth::user()->role_as == "0") {
                        abort(403);
                    } elseif (Auth::user()->role_as == $roleAsAdmin) {
                        $request->session()->regenerate();
                        return redirect('/admin/dashboard');
                    } else {
                        return redirect()->back()->with('errorMesg', 'فشلت المصادقة');
                    }
                }
            } else {

                return redirect()->back()->with('errorMesg', 'رقم الهاتف أو كلمة المرور غير صالحة.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->with('errorMesg', $errorMessage);
        }
    }

    //logout
    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect('/');
    }
    //add new user 
    public function add_stuff()
    {
        $user = auth()->user();
        $adminRole = env('ROLE_AS_ADMIN');


        if ($user->type === $adminRole) {
            return view('Admin_pages.add_users');
        }
        abort(403);
    }
    public function admin_add_cash_from()
    {
        $user = auth()->user();
        $adminRole = env('ROLE_AS_ADMIN');

        if ($user->type === $adminRole) {
            return view('Admin_pages.admin_add_mony');
        }
        abort(403);
    }
    public function sendMony(Request $request)
    {
        try {
            $user = auth()->user();
            $adminRole = env('ROLE_AS_ADMIN');
            if ($user->type == $adminRole) {
                $admin_mony = new admin_payment();
                $admin_mony->amount = $request->amount;
                $admin_mony->type = $request->note;

                $admin_mony->save();
                return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMesg', $e->getMessage());
        }
    }
    public function cash_report()
    {
        try {
            $user = auth()->user();
            $adminRole = env('ROLE_AS_ADMIN');
    
            if ($user->type === $adminRole) {
                $withdrawRecords = withdraw::select(
                    'withdraw_amunt as amount',
                    'type',
                    'created_at',
                    'expenses_id',
                    'apartment_id',
                    DB::raw('NULL as payment_id')
                )->get();
    
                $adminPaymentRecords = admin_payment::select(
                    'amount',
                    'type',
                    'created_at',
                    'id as payment_id'
                )->get();
    
                $all_transactions = collect();
                $all_transactions = $all_transactions->merge($withdrawRecords);
                $all_transactions = $all_transactions->merge($adminPaymentRecords);
                $all_transactions = $all_transactions->sortByDesc('created_at')->values()->all();
    
                $adminPayment = admin_payment::sum('amount');
                $withdraw = withdraw::sum('withdraw_amunt');
                $total = $adminPayment - $withdraw;
    
                return view('Admin_pages.admin_cash_reports', compact('all_transactions', 'total'));
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMesg', $e->getMessage());
        }
    }
   
}
