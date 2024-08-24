<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;

use App\Models\apartness;

use App\Models\paper_info;
use App\Models\apartness_x;
use Illuminate\Http\Request;
use App\Models\customer_info;
use App\Models\reservation_data;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\FuncCall;
use App\Models\reservation_payment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\admin_payment;
use App\Models\owner_apartment_report;
use App\Models\reservation_outgoig;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Broadcasting\PrivateChannel;

class Reservation_Ctrl extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }

    //add_new_reservation
    public function add_new_reservation()
    {
        $apartness = apartness::where('statues', 0)->get();
        $customer_data = customer_info::get();
        $customer_data = customer_info::whereDoesntHave('reservation_data', function ($query) {
            $query->where('reservation_status', 0);
        })->get();

        $users_name = User::where('type', 'staff$9Ajdsd!23')->get();
        return view('Admin_pages.add_new_reservation', compact('apartness', 'customer_data', 'users_name'));
    }
    //save the reservation
    //save the customer data
    public function save_customer_data(Request $request)
    {
        try {
            if ($request->customer_status == "exeite_customer") {
                $customer_info = customer_info::find($request->customer_id);
                if ($customer_info) {
                    $validator = Validator::make($request->all(), [
                        'apartment_id' => 'required',
                        'cost_per_night' => 'required|numeric',
                        'days_no' => 'required|numeric',
                        'total' => 'required|numeric',
                        'payed' => 'required|numeric',
                        'remaining' => 'required|numeric',
                        'note' => 'nullable',
                        'payment_method' => 'required',
                        'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
                        'staff_name' => 'required',
                    ], [
                        'apartment_id.required' => 'رقم الشقة مطلوب',
                        'cost_per_night.required' => 'سعر الليلة مطلوب',
                        'cost_per_night.numeric' => 'سعر الليلة يجب أن يكون رقمًا',
                        'days_no.required' => 'عدد الأيام مطلوب',
                        'days_no.numeric' => 'عدد الأيام يجب أن يكون رقمًا',
                        'total.required' => 'المبلغ الإجمالي مطلوب',
                        'total.numeric' => 'المبلغ الإجمالي يجب أن يكون رقمًا',
                        'payed.required' => 'المبلغ المدفوع مطلوب',
                        'payed.numeric' => 'المبلغ المدفوع يجب أن يكون رقمًا',
                        'remaining.required' => 'المبلغ المتبقي مطلوب',
                        'remaining.numeric' => 'المبلغ المتبقي يجب أن يكون رقمًا',
                        'payment_method.required' => 'طريقة الدفع مطلوبة',
                        'end_date.required' => 'تاريخ النهاية مطلوب',
                        'end_date.date_format' => 'تاريخ النهاية يجب أن يكون في التنسيق Y-m-d\TH:i',
                        'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
                        'start_date.after' => 'تاريخ البداية يجب أن يكون في المستقبل',
                    ]);

                    if ($request->start_date != '') {
                        $current_datetime = \Carbon\Carbon::now('Asia/Riyadh');

                        if ($request->start_date < $current_datetime) {
                            return redirect()->back()->with('errorMesg', 'اريخ البداية يجب أن يكون في المستقبل او اليوم');
                        }
                    }
                    if ($validator->fails()) {
                        return back()->withErrors($validator->errors())->withInput();
                    } else {
                        $result = $this->save_reservation_data($request->all(), $customer_info);

                        if ($result === true) {
                            return redirect()->back()->with('success', 'تم حفظ البينات بنجاح');
                        } else {
                            return redirect()->back()->with('errorMesg', '2حدث خطاء اثناء العملية !');
                        }
                    }
                } else {
                    return redirect()->back()->with('errorMesg', '3حدث خطاء اثناء العملية !');
                }
            } elseif ($request->customer_status == "new_customer") {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'phone1' => 'required|numeric',
                    'national_id' => 'required|numeric',

                    'customer_type' => 'required',
                    'phone2' => 'nullable',
                    'national_id' => 'required',
                    'customer_rate' => 'nullable',
                    'apartment_id' => 'required',
                    'cost_per_night' => 'required|numeric',
                    'days_no' => 'required|numeric',
                    'total' => 'required|numeric',
                    'payed' => 'required|numeric',
                    'remaining' => 'required|numeric',
                    'note' => 'nullable',
                    'payment_method' => 'required',
                ], [
                    'name.required' => 'الاسم مطلوب',
                    'phone1.required' => 'رقم الهاتف 1 مطلوب',
                    'phone1.numeric' => 'رقم الهاتف 1 يجب أن يكون رقمًا',
                    'customer_type.required' => 'نوع العميل مطلوب',
                    'phone2.nullable' => 'رقم الهاتف 2 اختياري',
                    'national_id.nullable' => 'رقم الهاتف 3 اختياري',
                    'customer_rate.nullable' => 'تقييم العميل اختياري',
                    'apartment_id.required' => 'رقم الشقة مطلوب',
                    'cost_per_night.required' => 'سعر الليلة مطلوب',
                    'cost_per_night.numeric' => 'سعر الليلة يجب أن يكون رقمًا',
                    'days_no.required' => 'عدد الأيام مطلوب',
                    'days_no.numeric' => 'عدد الأيام يجب أن يكون رقمًا',
                    'total.required' => 'المبلغ الإجمالي مطلوب',
                    'total.numeric' => 'المبلغ الإجمالي يجب أن يكون رقمًا',
                    'payed.required' => 'المبلغ المدفوع مطلوب',
                    'payed.numeric' => 'المبلغ المدفوع يجب أن يكون رقمًا',
                    'remaining.required' => 'المبلغ المتبقي مطلوب',
                    'remaining.numeric' => 'المبلغ المتبقي يجب أن يكون رقمًا',
                    'note.nullable' => 'الملاحظة اختيارية',
                    'payment_method.required' => 'طريقة الدفع مطلوبة',
                    'national_id.required' => 'الرقم القومي',
                ]);
                $validator2 = null;
                $validator3 = null;
                if ($request->input('customer_type') === '0') {
                    $validator2 = Validator::make($request->all(), [
                        'id_face' => 'required|array',
                        'id_face.*' => 'required|file|mimes:png,jpg,jpeg',
                        'id_back' => 'required|array',
                        'id_back.*' => 'file|mimes:png,jpg,jpeg',
                        'marriage_certificate' => 'nullable|array',
                        'marriage_certificate.*' => 'file|mimes:png,jpg,jpeg',
                    ], [
                        'id_face.required' => 'صورة الجانب الأمامي للهوية مطلوبة',
                        'id_face.array' => 'صورة الجانب الأمامي للهوية يجب أن تكون مصفوفة',
                        'id_face.*.required' => 'صورة الجانب الأمامي للهوية مطلوبة',
                        'id_face.*.file' => 'صورة الجانب الأمامي للهوية يجب أن تكون ملف',
                        'id_face.*.mimes' => 'صورة الجانب الأمامي للهوية يجب أن تكون بتنسيق png, jpg, أو jpeg',
                        'id_back.required' => 'صورة الجانب الخلفي للهوية مطلوبة',
                        'id_back.array' => 'صورة الجانب الخلفي للهوية يجب أن تكون مصفوفة',
                        'id_back.*.file' => 'صورة الجانب الخلفي للهوية يجب أن تكون ملف',
                        'id_back.*.mimes' => 'صورة الجانب الخلفي للهوية يجب أن تكون بتنسيق png, jpg, أو jpeg',
                        'marriage_certificate.nullable' => 'شهادة الزواج اختيارية',
                        'marriage_certificate.array' => 'شهادة الزواج يجب أن تكون مصفوفة',
                        'marriage_certificate.*.file' => 'شهادة الزواج يجب أن تكون ملف',
                        'marriage_certificate.*.mimes' => 'شهادة الزواج يجب أن تكون بتنسيق png, jpg, أو jpeg',
                    ]);
                } elseif ($request->input('customer_type') === '1') {
                    $validator3 = Validator::make($request->all(), [
                        'passport' => 'required|array',
                        'passport.*' => 'required|file|mimes:png,jpg,jpeg',
                        'marriageF' => 'nullable|array',
                        'marriageF.*' => 'file|mimes:png,jpg,jpeg',
                    ], [
                        'passport.required' => 'جواز السفر مطلوب',
                        'passport.array' => 'جواز السفر يجب أن يكون مصفوفة',
                        'passport.*.required' => 'جواز السفر مطلوب',
                        'passport.*.file' => 'جواز السفر يجب أن يكون ملف',
                        'passport.*.mimes' => 'جواز السفر يجب أن يكون بتنسيق png, jpg, أو jpeg',
                        'marriageF.nullable' => 'شهادة الزواج اختيارية',
                        'marriageF.array' => 'شهادة الزواج يجب أن تكون مصفوفة',
                        'marriageF.*.file' => 'شهادة الزواج يجب أن تكون ملف',
                        'marriageF.*.mimes' => 'شهادة الزواج يجب أن تكون بتنسيق png, jpg, أو jpeg',
                    ]);
                } else {
                    return redirect()->back()->with('errorMesg', 'الرجاء اختيار نوع العميل');
                }

                if ($validator->fails()) {
                    return back()->withErrors($validator->errors())->withInput();
                } elseif ($validator2 && $validator2->fails()) {
                    return back()->withErrors($validator2->errors())->withInput();
                } elseif ($validator3 && $validator3->fails()) {
                    return back()->withErrors($validator3->errors())->withInput();
                } else {
                    $result = $this->save_customer_info($request->all());
                    if ($result === true) {
                        return redirect()->back()->with('success', 'تم حفظ البينات بنجاح');
                    } else {
                        return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
                    }
                }
            } else {
                return redirect()->back()->with('errorMesg', 'الرجاء اختيار نوع العميل');
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            Log::error('Error in save_reservation_data(): ' . $e);

            return redirect()->back()->with('errorMesg', $err);
        }
    }
    private function save_customer_info($validator)
    {
        try {

            $customer_info = new customer_info();
            $customer_info->name = $validator['name'];
            $customer_info->phone_one = $validator['phone1'];
            $customer_info->phone_two = array_key_exists('phone2', $validator) ? $validator['phone2'] : null;
            $customer_info->national_id = array_key_exists('national_id', $validator) ? $validator['national_id'] : null;
            $customer_info->customer_type = $validator['customer_type'];
            $customer_info->customer_rate = array_key_exists('customer_rate', $validator) ? $validator['customer_rate'] : null;
            $customer_info->save();
            $this->save_reservation_data($validator, $customer_info);
            $this->save_customer_paper($validator, $customer_info);
            return true;
        } catch (\Exception $e) {
            Log::error('Error in save_reservation_data(): ' . $e->getMessage());

            return false;
        }
    }
    private function save_customer_paper($validator, $customer_info)
    {
        try {
            $uploadpath = 'paper_images';

            if ($customer_info->customer_type === '0') {
                $idFaceFiles = $validator['id_face']; // Assuming the name attribute of the ID face file inputs is 'id_face'
                $idBackFiles = $validator['id_back']; // Assuming the name attribute of the ID back file inputs is 'id_back'
                $marriageCertificateFiles = $validator['marriage_certificate'] ?? []; // Assuming the name attribute of the marriage certificate file inputs is 'marriage_certificate'
            } elseif ($customer_info->customer_type === '1') {
                $passportFiles = $validator['passport']; // Assuming the name attribute of the passport file inputs is 'passport'
                $marriageCertificateFiles = $validator['marriageF'] ?? []; // Assuming the name attribute of the marriage certificate file inputs is 'marriageF'
            }

            // Save images in separate rows for each set
            $maxImages = max(count($idFaceFiles ?? []), count($idBackFiles ?? []), count($passportFiles ?? []), count($marriageCertificateFiles));

            for ($index = 0; $index < $maxImages; $index++) {
                $customer_paper = new paper_info();
                $customer_paper->customer_id = $customer_info->id;

                // Save ID face image
                if (isset($idFaceFiles[$index])) {
                    $file = $idFaceFiles[$index];
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_face_' . $index . '.' . $ext;
                    $file->move($uploadpath, $filename);

                    // Store the file path or filename
                    $customer_paper->id_face = $uploadpath . '/' . $filename;
                }

                // Save ID back image
                if (isset($idBackFiles[$index])) {
                    $file = $idBackFiles[$index];
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_back_' . $index . '.' . $ext;
                    $file->move($uploadpath, $filename);

                    // Store the file path or filename
                    $customer_paper->id_back = $uploadpath . '/' . $filename;
                }

                // Save passport image
                if (isset($passportFiles[$index])) {
                    $file = $passportFiles[$index];
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_passport_' . $index . '.' . $ext;
                    $file->move($uploadpath, $filename);

                    // Store the file path or filename
                    $customer_paper->passport = $uploadpath . '/' . $filename;
                }

                // Save marriage certificate image
                if (isset($marriageCertificateFiles[$index])) {
                    $file = $marriageCertificateFiles[$index];
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_marriage_' . $index . '.' . $ext;
                    $file->move($uploadpath, $filename);

                    // Store the file path or filename
                    $customer_paper->marriage_certificate = $uploadpath . '/' . $filename;
                }

                // Save the customer_paper row in the database

                $customer_paper->save();
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error in save_reservation_data(): ' . $e->getMessage());

            return false;
        }
    }
    private function save_reservation_data($validator, $customer_info)
    {
        try {
            $current_user = auth()->user();
            $reservation_data = new reservation_data();
            $reservation_data->customer_id = $customer_info->id;
            $reservation_data->department_id = $validator['apartment_id'];
            $reservation_data->price_per_night = array_key_exists('cost_per_night', $validator) ? $validator['cost_per_night'] : null;
            $reservation_data->days_no = array_key_exists('days_no', $validator) ? $validator['days_no'] : null;

            // Get the current date and time
            $current_datetime = \Carbon\Carbon::now('Asia/Riyadh');

            // Set the start_date to the current datetime if it's not provided or is null/empty
            $reservation_data->start_date = array_key_exists('start_date', $validator) && !empty($validator['start_date']) ? $validator['start_date'] : $current_datetime;
            $reservation_data->end_date = array_key_exists('end_date', $validator) ? $validator['end_date'] : null;
            $reservation_data->notes = array_key_exists('note', $validator) ? $validator['note'] : null;
            // $reservation_data->employ_name = array_key_exists('staff_name', $validator) ? $validator['staff_name'] : null;
            // $reservation_data->data_entry = $current_user->id;
            $reservation_data->save();

            $apartness = apartness::where('id', $validator['apartment_id'])->first();
            $apartness->statues = 1;
            $apartness->save();

            return $this->save_reservation_payment($validator, $reservation_data);
        } catch (\Exception $e) {
            Log::error('Error in save_reservation_data(): ' . $e->getMessage());
            return false;
        }
    }
    private function save_reservation_payment($validator, $reservation_data)
    {
        try {
            $payment_data = new reservation_payment();
            $payment_data->reservation_id = $reservation_data->id;
            $payment_data->customer_id = $reservation_data->customer_id;
            $payment_data->payment_method = array_key_exists('payment_method', $validator) ? $validator['payment_method'] : null;
            $payment_data->total = array_key_exists('total', $validator) ? $validator['total'] : null;
            $payment_data->payed = array_key_exists('payed', $validator) ? $validator['payed'] : null;
            $payment_data->remaining = array_key_exists('remaining', $validator) ? $validator['remaining'] : null;
            $payment_data->save();

            // return view('Admin_pages.reservation_details');
            return true;
        } catch (\Exception $e) {
            Log::error('Error in save_reservation_data(): ' . $e->getMessage());
            return false;
        }
    }
    public function reservation_details($encrypted_id)
    {
        $id = Crypt::decryptString($encrypted_id);
        $reservationData = reservation_data::find($id);
        $customer_info = customer_info::where('id', $reservationData->customer_id)->first();
        $reservation_paper = paper_info::where('customer_id', $customer_info->id)->get();
        $reservation_payment = reservation_payment::where('reservation_id', $id)->first();
        $department_info = apartness::where('id', $reservationData->department_id)->first();
        $reservationOutgoing = reservation_outgoig::where('resvation_id', $id)->get();
        return view('Admin_pages.reservation_details', compact('reservationData', 'customer_info', 'reservation_paper', 'reservation_payment', 'department_info', 'reservationOutgoing'));
    }
    public function reservations_list()
    {
        $resevations = reservation_data::orderBy('end_date', 'asc')->where('reservation_status', 0)->get();
        $apartness_model = apartness::all();
        $reservation_payment = reservation_payment::get();
        return view('Admin_pages.reservations_list', compact('resevations', 'reservation_payment'));
    }
    public function reservations_history()
    {
        $resevations = reservation_data::orderBy('end_date', 'asc')->where('reservation_status', 1)->get();
        $apartness_model = apartness::all();
        return view('Admin_pages.reservations_history', compact('resevations'));
    }
    public function resvation_close($encrypted_id)
    {
        try {
            $user = auth()->user();
            $id = Crypt::decryptString($encrypted_id);
            $reservationData = reservation_data::find($id);
            $reservationData->reservation_status = 1;
            $apartment = apartness::where('id', $reservationData->department_id)->first();
            $reservationPayment = reservation_payment::where('reservation_id', $reservationData->id)->first();
            // Update apartment status
            $apartment->statues = 0;
            $apartment->save();
            // Update reservation payment
            $reservationData->save();
            if ($apartment->sys_type == 0) {
                $apartmentAdmin = apartness_x::where('apartness_id', $reservationData->department_id)->first();
                $apartmentTotal = $reservationPayment->total * ($apartmentAdmin->percentage / 100);
                $ownerNeed = $reservationPayment->total - $apartmentTotal;
                $apartmentAdmin->total_account = $apartmentAdmin->total_account + $ownerNeed;
                $apartmentAdmin->save();

                $apartness_admin_owner = new owner_apartment_report();
                $apartness_admin_owner->apartment_id = $apartment->id;
                $apartness_admin_owner->amount = $ownerNeed;
                $apartness_admin_owner->total_account = $apartmentAdmin->total_account;
                $apartness_admin_owner->data_entry_name = $user->name;
                $apartness_admin_owner->process_type = 0;
                $apartness_admin_owner->data_entry = $user->name;
                $apartness_admin_owner->save();

                $admin_payment = new admin_payment();
                $admin_payment->amount = $apartmentTotal;
                $admin_payment->type = "ايداع حجز";
                $admin_payment->save();
            } else {
                $admin_payment = new admin_payment();
                $admin_payment->amount = $reservationPayment->total;
                $admin_payment->type = "ايداع حجز";
                $admin_payment->save();
            }

            return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMesg', $e->getMessage());
        }
    }
    public function dateFilter(Request $request)
    {
        // Get the start date and end date from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Convert the start date and end date to the datetime range
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';

        // Retrieve the reservations based on the filtered creation dates
        $resevations = reservation_data::whereBetween('end_date', [$startDateTime, $endDateTime])
            ->orderBy('end_date', 'asc')->where('reservation_status', 0)->get();
        $reservation_payment = reservation_payment::get();

        return view('Admin_pages.reservations_list', compact('resevations', 'reservation_payment'));
    }
    public function dateCloseFilter(Request $request)
    {
        // Get the start date and end date from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Convert the start date and end date to the datetime range
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';
        $reservation_payment = reservation_payment::get();
        // Retrieve the reservations based on the filtered creation dates
        $resevations = reservation_data::whereBetween('end_date', [$startDateTime, $endDateTime])
            ->orderBy('end_date', 'asc')->where('reservation_status', 1)->get();
        return view('Admin_pages.reservations_list', compact('resevations', 'reservation_payment'));
    }
    public function editeCustomerInfo($customerId)
    {
        try {
            $id = Crypt::decryptString($customerId);
            $customer_data = customer_info::find($id);
            $reservation_paper = paper_info::where('customer_id', $customer_data->id)->get();
            return view('Admin_pages.edite_customer_info', compact('customer_data', 'reservation_paper'));
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
        }
    }
    public function deleteImage($id, $type)
    {
        try {
            $reservationPaper = paper_info::findOrFail($id); // Assuming you have a ReservationPaper model

            // Determine the image path based on the type
            $imagePath = '';
            switch ($type) {
                case 'idface':
                    $imagePath = $reservationPaper->id_face;
                    $reservationPaper->id_face = null;
                    break;
                case 'idback':
                    $imagePath = $reservationPaper->id_back;
                    $reservationPaper->id_back = null;
                    break;
                case 'marriage':
                    $imagePath = $reservationPaper->marriage_certificate;
                    $reservationPaper->marriage_certificate = null;
                    break;
                case 'passport':
                    $imagePath = $reservationPaper->passport;
                    $reservationPaper->passport = null;
                    break;
            }

            // Delete the image file if it exists
            if (!empty($imagePath) && File::exists(public_path($imagePath))) {
                File::delete(public_path($imagePath));
            }

            // Save the updated reservation paper
            $reservationPaper->save();

            return redirect()->back()->with('success', 'تم حذف الصورة بنجاح');
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
        }
    }
    public function editeCustomerData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone1' => 'required|numeric',
                'customer_type' => 'required',
                'phone2' => 'nullable',
                'national_id' => 'nullable',
                'customer_rate' => 'nullable',
                'id_face.*' => 'file|mimes:png,jpg,jpeg',
                'id_back.*' => 'file|mimes:png,jpg,jpeg',
                'marriage_certificate' => 'nullable|array',
                'marriage_certificate.*' => 'file|mimes:png,jpg,jpeg',
                'passport.*' => 'file|mimes:png,jpg,jpeg',
                'marriageF' => 'nullable|array',
                'marriageF.*' => 'file|mimes:png,jpg,jpeg',
            ], [
                'name.required' => 'الاسم مطلوب',
                'phone1.required' => 'رقم الهاتف 1 مطلوب',
                'phone1.numeric' => 'رقم الهاتف 1 يجب أن يكون رقميًا',
                'customer_type.required' => 'نوع العميل مطلوب',
                'phone2.nullable' => 'رقم الهاتف 2 اختياري',
                'national_id.nullable' => 'رقم الهاتف 3 اختياري',
                'customer_rate.nullable' => 'تقييم العميل اختياري',
                'id_face.*.file' => 'صورة الجانب الأمامي للهوية يجب أن تكون ملف',
                'id_face.*.mimes' => 'صورة الجانب الأمامي للهوية يجب أن تكون بتنسيق png, jpg, أو jpeg',
                'id_back.*.file' => 'صورة الجانب الخلفي للهوية يجب أن تكون ملف',
                'id_back.*.mimes' => 'صورة الجانب الخلفي للهوية يجب أن تكون بتنسيق png, jpg, أو jpeg',
                'marriage_certificate.nullable' => 'شهادة الزواج اختيارية',
                'marriage_certificate.array' => 'شهادة الزواج يجب أن تكون مصفوفة',
                'marriage_certificate.*.file' => 'شهادة الزواج يجب أن تكون ملف',
                'marriage_certificate.*.mimes' => 'شهادة الزواج يجب أن تكون بتنسيق png, jpg, أو jpeg',
                'passport.*.file' => 'جواز السفر يجب أن يكون ملف',
                'passport.*.mimes' => 'جواز السفر يجب أن يكون بتنسيق png, jpg, أو jpeg',
                'marriageF.nullable' => 'شهادة الزواج اختيارية',
                'marriageF.array' => 'شهادة الزواج يجب أن تكون مصفوفة',
                'marriageF.*.file' => 'شهادة الزواج يجب أن تكون ملف',
                'marriageF.*.mimes' => 'شهادة الزواج يجب أن تكون بتنسيق png, jpg, أو jpeg',
            ]);

            if ($validator->fails()) {
                // Handle validation errors
                // You can return the validation errors to the view or handle them as per your application logic
                // For example, you can redirect back with the errors
                foreach ($validator->errors()->all() as $error) {
                    return redirect()->back()->with('errorMesg', $error);
                }
            }

            $customer_info = customer_info::find($request->custome_id);
            $customer_info->name = $request->input('name');
            $customer_info->phone_one = $request->input('phone1');
            $customer_info->phone_two = $request->input('phone2');
            $customer_info->phone_three = $request->input('national_id');
            $customer_info->customer_type = $request->input('customer_type');
            $customer_info->customer_rate = $request->input('customer_rate');
            $customer_info->save();
            $result = $this->save_customer_paper($request, $customer_info);
            if ($result === true) {
                return redirect()->back()->with('success', 'تم حفظ البينات بنجاح');
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', 'حدث خطاء اثناء العملية !');
        }
    }
    public function editeReservation($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $reservation_data = reservation_data::find($id);
            $apartness = apartness::where('apartment_status', 0)->get();
            $reservation_apartment = apartness::where('id', $reservation_data->department_id)->first();
            $resevation_payment = reservation_payment::where('reservation_id', $reservation_data->id)->first();
            return view('Admin_pages.edite_reservation_info', compact('reservation_data', 'apartness', 'reservation_apartment', 'resevation_payment'));
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    public function save_reservation_change(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cost_per_night' => 'required|numeric',
                'days_no' => 'required|numeric',
                'total' => 'required|numeric',
                'payed' => 'required|numeric',
                'remaining' => 'required|numeric',
                'note' => 'nullable',
                'payment_method' => 'required',
            ], [
                'cost_per_night.required' => 'السعر لكل ليلة مطلوب',
                'cost_per_night.numeric' => 'السعر لكل ليلة يجب أن يكون رقمي',
                'days_no.required' => 'عدد الأيام مطلوب',
                'days_no.numeric' => 'عدد الأيام يجب أن يكون رقمي',
                'total.required' => 'الإجمالي مطلوب',
                'total.numeric' => 'الإجمالي يجب أن يكون رقمي',
                'payed.required' => 'المبلغ المدفوع مطلوب',
                'payed.numeric' => 'المبلغ المدفوع يجب أن يكون رقمي',
                'remaining.required' => 'المبلغ المتبقي مطلوب',
                'remaining.numeric' => 'المبلغ المتبقي يجب أن يكون رقمي',
                'note.nullable' => 'الملاحظة اختيارية',
                'payment_method.required' => 'طريقة الدفع مطلوبة',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    return redirect()->back()->with('errorMesg', $error);
                }
            } else {
                $current_user = auth()->user();
                $reservation_data = reservation_data::find($request->id);
                $encryptedId=Crypt::encryptString($request->id);
                $apartness_old = apartness::where('id', $reservation_data->department_id)->first();
                $apartness_old->statues = 0;
                $apartness_old->save();

                $reservation_data->department_id = $request->apartment_id;
                $reservation_data->price_per_night = $request->cost_per_night;
                $reservation_data->days_no = $request->days_no;
                $reservation_data->notes = $request->note;
                $reservation_data->data_entry = $current_user->id;
                $reservation_data->start_date = $request->start_date;
                $reservation_data->end_date = $request->end_date;
                $reservation_data->save();

                // Retrieve the payment data
                $payment_data = reservation_payment::where('reservation_id', $reservation_data->id)->first();

                // Retrieve the reservation expenses
                $reservation_expenses = reservation_outgoig::where('resvation_id', $reservation_data->id)->get();
                $apartness_new = apartness::where('id', $reservation_data->department_id)->first();
                $apartness_new->statues = 1;
                $apartness_new->save();


                // Calculate the total outgoing expenses
                $totalOutgoing = 0;
                foreach ($reservation_expenses as $expense) {
                    $totalOutgoing += $expense->cost;
                }

                // Calculate the new total based on the updated days_no and price_per_night
                $newTotal = ($request->days_no * $request->cost_per_night) + $totalOutgoing;

                // Update the payment data
                $payment_data->reservation_id = $reservation_data->id;
                $payment_data->customer_id = $reservation_data->customer_id;
                $payment_data->payment_method = $request->payment_method;
                $payment_data->total = $newTotal;
                $payment_data->payed = $request->payed;
                $payment_data->remaining = $payment_data->total - $request->payed;
                $payment_data->save();
                return redirect()->route('reservations.details', ['encryptedId' => $encryptedId])->with('success', 'تم حفظ البيانات بنجاح');            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    //delete reservtion
    public function delete_reservation($encryptedId)
    {
        
        try {
            $id = Crypt::decryptString($encryptedId);
            $resrvation = reservation_data::find($id);
            $reservation_outgoig=reservation_outgoig::where('resvation_id',$id)->get();
            if($reservation_outgoig->count() == 0 )
            {
                if ($resrvation) {
                    if ($resrvation->reservation_status != 1) {
                        $resrvation->delete();
                        $apartness_old = apartness::where('id', $resrvation->department_id)->first();
                        $apartness_old->statues = 0;
                        $apartness_old->save();
                        return redirect('/admin/dashboard')->with('success', 'تم حذف البيانات بنجاح');
                    } else {
                        return redirect()->back()->with('errorMesg', 'لا يمكن حذف هذا الحجز');
                    }
                } else {
                    return redirect()->back()->with('errorMesg', 'هذه الحجز غير موجود');
                }
            }else{
                return redirect()->back()->with('errorMesg', 'لا يمكن حذف هذا الحجز , يوجد مصاريف مسجلة ' );
            }
       
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', 'هذه الحجز غير موجود');
        }
    }
}
