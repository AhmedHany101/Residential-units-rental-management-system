<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\apartness;
use App\Models\apartness_x;
use App\Models\expense_data;
use Illuminate\Http\Request;
use App\Models\reservation_data;
use App\Http\Controllers\Controller;
use App\Models\admin_payment;
use App\Models\owner_apartment_report;
use App\Models\reservation_outgoig;
use App\Models\reservation_payment;
use App\Models\User;
use App\Models\withdraw;
use Illuminate\Support\Facades\Crypt;

class Expenses_Ctrl extends Controller
{
    public function add_expenses()
    {
        $privat_apartness = apartness::where('sys_type', 1)->get();
        $admin_apartness = apartness::where('sys_type', 0)->get();
        $adminRole = env('ROLE_AS_ADMIN');
        $users_name=User::where('role_as', $adminRole)->get();
        return view('Admin_pages.add_new_outgoing', compact('privat_apartness', 'admin_apartness', 'users_name'));
    }
    public function save_epenses_data(Request $request)
    {
        try {
            $user = auth()->user();
            $request->validate([
                'expense_type' => 'required|in:office,labor,private,admin',
                'note1' => 'required_if:expense_type,office',
                'note2' => 'required_if:expense_type,labor',
                'note3' => 'required_if:expense_type,private',
                'note4' => 'required_if:expense_type,admin',
                'cost1' => 'required_if:expense_type,office',
                'cost3' => 'required_if:expense_type,private',
                'cost4' => 'required_if:expense_type,admin',
                'Private_apartment_id' => 'required_if:expense_type,private',
                'Admin_apartment_id' => 'required_if:expense_type,admin',
            ], [
                'expense_type.required' => 'نوع المصروف مطلوب',
                'expense_type.in' => 'نوع المصروف يجب أن يكون من بين: office, labor, private, admin',
                'note1.required_if' => 'الملاحظة 1 مطلوبة للمصروفات المكتبية',
                'note2.required_if' => 'الملاحظة 2 مطلوبة للمصروفات العمالية',
                'note3.required_if' => 'الملاحظة 3 مطلوبة للمصروفات الخاصة',
                'note4.required_if' => 'الملاحظة 4 مطلوبة للمصروفات الإدارية',
                'cost1.required_if' => 'التكلفة 1 مطلوبة للمصروفات المكتبية',
                'cost3.required_if' => 'التكلفة 3 مطلوبة للمصروفات الخاصة',
                'cost4.required_if' => 'التكلفة 4 مطلوبة للمصروفات الإدارية',
                'Private_apartment_id.required_if' => 'معرف الشقة الخاصة مطلوب للمصروفات الخاصة',
                'Admin_apartment_id.required_if' => 'معرف الشقة الإدارية مطلوب للمصروفات الإدارية',
            ]);
            $admin_payment = new withdraw();

            $expenese_data = new expense_data();
            if ($request->expense_type == "office") {
                $expenese_data->expense_type = 1;
                $expenese_data->note = $request->note1;
                $expenese_data->cost = $request->cost1;
                $expenese_data->data_entry_name    = $request->staff_name;
                $admin_payment->withdraw_amunt = $request->cost1;
                $admin_payment->type = "سحب مصورفات";
                $expenese_data->date = Carbon::now()->format('Y-m-d');
                $expenese_data->save();
                $admin_payment->expenses_id = $expenese_data->id;
                $admin_payment->save();
            } elseif ($request->expense_type == "labor") {
                $expenese_data->expense_type = 2;
                $expenese_data->note = $request->note2;
                $expenese_data->cost = $request->cost2;
                $expenese_data->data_entry_name    = $request->staff_name;
                $expenese_data->date = Carbon::now()->format('Y-m-d');
                $admin_payment->withdraw_amunt = $request->cost2;
                $admin_payment->type = "سحب مصورفات";
                $expenese_data->save();
                $admin_payment->expenses_id = $expenese_data->id;

                $admin_payment->save();
            } elseif ($request->expense_type == "private") {
                $expenese_data->note = $request->note3;
                $expenese_data->expense_type = 3;
                $expenese_data->apartment_id = $request->Private_apartment_id;
                $expenese_data->cost = $request->cost3;
                $expenese_data->data_entry_name    = $request->staff_name;
                $expenese_data->date = Carbon::now()->format('Y-m-d');
                $admin_payment->withdraw_amunt = $request->cost3;
                $admin_payment->type = "سحب مصورفات";
                $expenese_data->save();
                $admin_payment->expenses_id = $expenese_data->id;
                $admin_payment->apartment_id = $expenese_data->apartment_id;

                $admin_payment->save();
            } elseif ($request->expense_type == "admin") {
                $expenese_data->note = $request->note4;
                $expenese_data->expense_type = 4;
                $expenese_data->cost = $request->cost4;
                $expenese_data->data_entry_name = $request->staff_name;
                $expenese_data->date = Carbon::now()->format('Y-m-d');
                $expenese_data->apartment_id = $request->Admin_apartment_id;

                $expenese_data->save();
            }

            //apartness_x
            if ($expenese_data->expense_type == 4) {
                $apartness_admin_owner = apartness_x::where('apartness_id', $request->Admin_apartment_id)->first();
                if ($apartness_admin_owner) {
                    $apartness_admin_owner->total_outgoings = $apartness_admin_owner->total_outgoings + $expenese_data->cost;
                    $apartness_admin_owner->total_account = $apartness_admin_owner->total_account - $expenese_data->cost;
                    $apartness_admin_owner->save();
                    $expenese_data->save();
                    $owner_report = new owner_apartment_report();
                    $owner_report->apartment_id = $request->Admin_apartment_id;
                    $owner_report->amount = $request->cost4;
                    $owner_report->total_account = $apartness_admin_owner->total_account; // Use the updated value from the 'apartness_x' table
                    $owner_report->data_entry_name = $request->staff_name;
                    $owner_report->process_type = 2;
                    $owner_report->save();
                } else {
                    return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
                }
            }


            return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    public function expeness_report()
    {
        try {
            $expenese_data = expense_data::latest()->get();
            return view('Admin_pages.expnses_report', compact('expenese_data'));
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    public function add_resrvation_expenses($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $resvation = reservation_data::find($id);
            $users_name = User::where('type', 'staff$9Ajdsd!23')->get();

            return view('Admin_pages.add_resrvation_expnses', compact('resvation', 'users_name'));
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    public function save_resrvation_expenses(Request $request)
    {
        try {
            $reservation = reservation_data::find($request->id);
            $encryptedId=Crypt::encryptString($request->id);

            if ($reservation) {
                $reservation_outgoing = reservation_payment::where('reservation_id', $request->id)->first();
                if ($reservation_outgoing) {
                    $reservation_outgoing->outgoing += $request->cost;
                    $reservation_outgoing->total += $request->cost;
                    $reservation_outgoing->remaining = $reservation_outgoing->total - $reservation_outgoing->payed;
                    $reservation_outgoing->save();
                    $resrevation_expenese = new reservation_outgoig();
                    $resrevation_expenese->resvation_id = $request->id;
                    $resrevation_expenese->cost = $request->cost;
                    $resrevation_expenese->descrbtion = $request->note;
                    $resrevation_expenese->data_entry = $request->staff_name;
                    $resrevation_expenese->save();
                    //return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
                    return redirect()->route('reservations.details', ['encryptedId' => $encryptedId])->with('success', 'تم حفظ البيانات بنجاح');            


                } else {
                    return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
                }
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    public function delete_resrvation_expenses($encryptedExpeneseId)
    {
        try {
            $id = Crypt::decryptString($encryptedExpeneseId);
            $reservation_outgoing = reservation_outgoig::find($id);
            if ($reservation_outgoing) {
                $reservation_payment = reservation_payment::where('reservation_id', $reservation_outgoing->resvation_id)->first();
                if ($reservation_payment) {
                    $reservation_payment->outgoing -= $reservation_outgoing->cost;
                    $reservation_payment->total -= $reservation_outgoing->cost;
                    $reservation_payment->remaining = $reservation_payment->total - $reservation_payment->payed;
                    $reservation_payment->save();
                    $reservation_outgoing->delete();
                    return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
                } else {
                    return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
                }
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    //delete expancess
    public function delete_expenses($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $expenese_data = expense_data::find($id);
            if ($expenese_data) {
                if($expenese_data->expense_type != 4)
                {
                    $withdraw = withdraw::where('expenses_id', $expenese_data->id)->first();
                    if($withdraw)
                    {
                        $withdraw->delete();
                        $expenese_data->delete();
                        return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');

                    }
                    else
                    {
                        return redirect()->back()->with('errorMesg', '1حدث خطاء اثناء العملية !');
                    }

                }else{
                    $apartness_admin_owner = apartness_x::where('apartness_id', $expenese_data->apartment_id)->first();
                    if($apartness_admin_owner)
                    {
                        $apartness_admin_owner->total_outgoings = $apartness_admin_owner->total_outgoings - $expenese_data->cost;
                        $apartness_admin_owner->total_account = $apartness_admin_owner->total_account + $expenese_data->cost;
                        $apartness_admin_owner->save();
                        $owner_apartment_report=owner_apartment_report::where('apartment_id',$expenese_data->apartment_id)->first();
                        $owner_apartment_report->delete();
                        $expenese_data->delete();
                        return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');

                    }else{
                        return redirect()->back()->with('errorMesg', '2حدث خطاء اثناء العملية !');

                    }

                }

            }
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            return redirect()->back()->with('errorMesg', $error);
        }
    }
}
