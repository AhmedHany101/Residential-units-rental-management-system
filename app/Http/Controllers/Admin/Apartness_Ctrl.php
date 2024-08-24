<?php

namespace App\Http\Controllers\Admin;

use App\Models\owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\apartment_images;
use App\Models\apartness;
use App\Models\apartness_x;
use App\Models\apartness_y;
use App\Models\owner_private_account;
use App\Models\withdraw;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Apartness_Ctrl extends Controller
{
    //add_apartness_form
    public function add_apartness_form()
    {
        $owner_info = owner::get();
        return view('Admin_pages.add_new_apartment', compact('owner_info'));
    }
    //save_apartment_info
    public function save_apartment_info(Request $request)
    {
        try {
            $isNewOwner = $request->owner_type === 'new';
            $ownerInfo = $this->validateAndSaveOwner($request, $isNewOwner);

            $apartmentType = $request->input('apartment_type');
            if ($apartmentType === 'private') {
                $this->validateAndSavePrivateApartment($request, $ownerInfo);
            } elseif ($apartmentType === 'admin') {
                $this->validateAndSaveAdminApartment($request, $ownerInfo);
            } else {
                return redirect()->back()->with('errorMesg', 'حدث خطأ أثناء العملية!');
            }

            return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMesg', $e->getMessage());
        }
    }

    private function validateAndSaveOwner(Request $request, bool $isNewOwner): owner
    {
        if ($isNewOwner) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|unique:owners,phone',
            ], [
                'name.required' => 'الاسم مطلوب',
                'phone.required' => 'رقم الهاتف مطلوب',
                'phone.unique' => 'رقم الهاتف مسجل مسبقًا',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $ownerInfo = new owner();
            $ownerInfo->name = $request->name;
            $ownerInfo->phone = $request->phone;
            $ownerInfo->save();
        } else {
            $ownerInfo = owner::findOrFail($request->owner_id);
        }

        return $ownerInfo;
    }

    private function validateAndSavePrivateApartment(Request $request, Owner $ownerInfo)
    {
        try {
            $validator = Validator::make($request->all(), [
                'apartment_noP' => 'required',
                'floor_no_P' => 'required',
                'building_no_P' => 'required',
                'room_no_P' => 'required',
                'price_per_day_P' => 'required',
                'total_season_P' => 'required',
                'payed_P' => 'required',
                'reamaning_P' => 'required',
                'note' => 'nullable',
                'images' => 'array|min:1',
                'images.*' => 'image|mimes:png,jpg,jpeg|max:3072', // Validate each image file, max size 3MB, and only accept .png, .jpg, and .jpeg    
            ], [
                'apartment_noP.required' => 'رقم الشقة الخاصة مطلوب',
                'floor_no_P.required' => 'رقم الطابق الخاص مطلوب',
                'building_no_P.required' => 'رقم المبنى الخاص مطلوب',
                'room_no_P.required' => 'رقم الغرفة الخاصة مطلوب',
                'price_per_day_P.required' => 'السعر اليومي الخاص مطلوب',
                'total_season_P.required' => 'إجمالي الموسم الخاص مطلوب',
                'payed_P.required' => 'المبلغ المدفوع الخاص مطلوب',
                'reamaning_P.required' => 'المبلغ المتبقي الخاص مطلوب',
                'note.nullable' => 'الملاحظة اختيارية',
                'images.*.image' => 'الملفات المرفقة يجب أن تكون صور',
                'images.*.mimes' => 'يجب أن تكون الصور بصيغة png, jpg أو jpeg',
                'images.*.max' => 'حجم الصورة يجب ألا يتجاوز 3 ميجابايت',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $this->save_apartnessy($validator->validated(), $ownerInfo);
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return false;
        }
    }

    private function validateAndSaveAdminApartment(Request $request, Owner $ownerInfo)
    {
        try {
            $validator = Validator::make($request->all(), [
                'apartment_no_A' => 'required',
                'floor_no' => 'required',
                'building_no' => 'required',
                'room_no' => 'required',
                'price_per_day' => 'required',
                'percentage' => 'required',
                'note' => 'nullable',
                'admin_images' => 'array|min:1',
                'admin_images.*' => 'image|mimes:png,jpg,jpeg|max:3072', // Validate each image file, max size 3MB, and only accept .png, .jpg, and .jpeg   
            ], [
                'apartment_no_A.required' => 'رقم الشقة مطلوب',
                'floor_no.required' => 'رقم الطابق مطلوب',
                'building_no.required' => 'رقم المبنى مطلوب',
                'room_no.required' => 'رقم الغرفة مطلوب',
                'price_per_day.required' => 'السعر اليومي مطلوب',
                'percentage.required' => 'النسبة المئوية مطلوبة',
                'note.nullable' => 'الملاحظة اختيارية',
                'admin_images.*.image' => 'الملفات المرفقة يجب أن تكون صور',
                'admin_images.*.mimes' => 'يجب أن تكون الصور بصيغة png, jpg أو jpeg',
                'admin_images.*.max' => 'حجم الصورة يجب ألا يتجاوز 3 ميجابايت',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $result = $this->save_apartnessx($validator->validated(), $ownerInfo);
            if ($result !== true) {
                throw new \Exception('حدث خطأ أثناء العملية!');
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return false;
        }
    }

    private function save_apartnessy($validator1, $owner_info)
    {
        try {
            $apartness = new apartness();
            $apartness->apartness_no = $validator1['apartment_noP'];
            $apartness->buliding_no = $validator1['floor_no_P'];
            $apartness->floor_no = $validator1['floor_no_P'];
            $apartness->room_no = $validator1['room_no_P'];
            $apartness->cost_per_night = $validator1['price_per_day_P'];
            $apartness->sys_type = 1;
            $apartness->owner_id = $owner_info->id;
            $apartness->not = array_key_exists('note', $validator1) ? $validator1['note'] : null;
            $apartness->save();

            if (array_key_exists('images', $validator1) && is_array($validator1['images'])) {
                $uploadpath = 'apartment_images';
                foreach ($validator1['images'] as $image) {
                    $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    $image->move($uploadpath, $filename);

                    $apartmentImage = new apartment_images();
                    $apartmentImage->apartment_id = $apartness->id;
                    $apartmentImage->image = $uploadpath . '/' . $filename;
                    $apartmentImage->save();
                }
            }

            $this->apartness_privaty($apartness, $validator1);
            return true;
        } catch (\Exception $e) {
            $err = $e->getMessage();
            // Log the error or handle it in some other way
            //Log::error('Error saving apartment: ' . $err);
            return false;
        }
    }
    private function save_apartnessx($validator2, $owner_info)
    {
        try {
            $apartness = new apartness();
            $apartness->apartness_no = $validator2['apartment_no_A'];
            $apartness->buliding_no = $validator2['building_no'];
            $apartness->floor_no = $validator2['floor_no'];
            $apartness->room_no = $validator2['room_no'];
            $apartness->cost_per_night = $validator2['price_per_day'];
            $apartness->sys_type = 0;
            $apartness->owner_id = $owner_info->id;
            $apartness->not = array_key_exists('note', $validator2) ? $validator2['note'] : null;
            $apartness->save();
            if (array_key_exists('admin_images', $validator2) && is_array($validator2['admin_images'])) {
                $uploadpath = 'apartment_images';
                foreach ($validator2['admin_images'] as $image) {
                    $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    $image->move($uploadpath, $filename);

                    $apartmentImage = new apartment_images();
                    $apartmentImage->apartment_id = $apartness->id;
                    $apartmentImage->image = $uploadpath . '/' . $filename;
                    $apartmentImage->save();
                }
            }


            $this->apartness_adminx($apartness, $validator2);
            return true;
        } catch (\Exception $e) {
            $err = $e->getMessage();
            //Log::error('Error saving apartment: ' . $err);

            return false;
        }
    }
    private function apartness_adminx($apartness, $validator2)
    {
        try {
            $apartness_private = new apartness_x();
            $apartness_private->owner_id = $apartness->owner_id;
            $apartness_private->apartness_id = $apartness->id;
            $apartness_private->percentage = $validator2['percentage'];
            $apartness_private->save();
            return true;
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return false;
        }
    }
    private function apartness_privaty($apartness, $validator1)
    {
        try {
            $apartness_private = new apartness_y();
            $apartness_private->owner_id = $apartness->owner_id;
            $apartness_private->apartness_id = $apartness->id;
            $apartness_private->total_account = $validator1['total_season_P'];
            $apartness_private->payed = $validator1['payed_P'];
            $apartness_private->remaining = $validator1['reamaning_P'];
            $apartness_private->save();
            $owner_info=owner::find($apartness->owner_id);
            $withdraw_process = new withdraw();
            $withdraw_process->withdraw_amunt = $validator1['payed_P'];
            $withdraw_process->type = "سدد نقدي, المالك: " . $owner_info->name;
            $withdraw_process->save();
            return true;
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return false;
        }
    }
    public function apartness_data()
    {
        $apartness_data = apartness::orderBy('statues', 'asc')
            ->orderBy('statues', 'asc')
            ->get();
        return view('Admin_pages.apartness_list', compact('apartness_data'));
    }
    public function aprtment_details($apart_encryptedId)
    {
        $id = Crypt::decryptString($apart_encryptedId);
        $apartment_data = apartness::find($id);
        $apartness_admin = apartness_x::where('apartness_id', $id)->first();
        $apartness_private = apartness_y::where('apartness_id', $id)->first();
        $apartment_images = apartment_images::where('apartment_id', $id)->get();
        return view('Admin_pages.apartness_details', compact('apartment_data', 'apartness_admin', 'apartness_private', 'apartment_images'));
    }
    public function apartness_edite(Request $request)
    {
        try {
            $apartment_data = apartness::find($request->apartmentID);
            $owner_info = owner::find($apartment_data->owner_id);

            $validator0 = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
            ]);

            if ($validator0->fails()) {
                foreach ($validator0->errors()->all() as $error) {
                    return redirect()->back()->with('errorMesg', $error);
                }
            } else {
                $owner_info->name = $request->name;
                $owner_info->phone = $request->phone;
            }

            if ($request->input('apartment_type') === 'private') {
                $validator1 = Validator::make($request->all(), [
                    'apartment_noP' => 'required',
                    'floor_no_P' => 'required',
                    'building_no_P' => 'required',
                    'room_no_P' => 'required',
                    'price_per_day_P' => 'required',
                    'total_season_P' => 'required',
                    'payed_P' => 'required',
                    'reamaning_P' => 'required',
                    'note',
                ]);

                if ($validator1->fails()) {
                    foreach ($validator1->errors()->all() as $error) {
                        return redirect()->back()->with('errorMesg', $error);
                    }
                } else {
                    $apartment_data->apartness_no = $request->input('apartment_noP');
                    $apartment_data->buliding_no = $request->input('building_no_P');
                    $apartment_data->floor_no = $request->input('floor_no_P');
                    $apartment_data->room_no = $request->input('room_no_P');
                    $apartment_data->cost_per_night = $request->input('price_per_day_P');
                    $apartment_data->not = $request->input('note');

                    $apartment_data_P = apartness_y::where('apartness_id', $apartment_data->id)->first();
                    $existing_payed = $apartment_data_P->payed;
                    
                    $apartment_data_P->total_account = $request->input('total_season_P');
                    $apartment_data_P->payed = $request->input('payed_P');
                    $apartment_data_P->remaining = $request->input('total_season_P') - $request->input('payed_P');
                    
                    // Check if the "payed" value is different from the existing value
                    if ($request->input('payed_P') != $existing_payed) {
                        $owner_private = new owner_private_account();
                        $owner_private->apartment_id = $apartment_data->id;
                        $owner_private->amount = $request->input('payed_P');
                        $owner_private->total_account = $request->input('total_season_P');
                        $owner_private->remainging = $request->input('total_season_P') - $request->input('payed_P');
                        $owner_private->process_type = 0;
                        $owner_private->save();
                        
                        //====
                        $withdraw_process = new withdraw();
                        $withdraw_process->withdraw_amunt = $request->input('payed_P') - $existing_payed;
                        $withdraw_process->type = "سدد نقدي, المالك: " . $owner_info->name;
                        $withdraw_process->save();
                    }
                    
                    $apartment_data->save();
                    $apartment_data_P->save();
                    $owner_info->save();

                    if ($request->hasFile('images')) {
                        $uploadpath = 'images';
                        foreach ($request->file('images') as $image) {
                            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                            $filename = time() . '_' . uniqid() . '.' . $ext;
                            $image->move($uploadpath, $filename);

                            $apartmentImage = new apartment_images();
                            $apartmentImage->apartment_id = $apartment_data->id;
                            $apartmentImage->image = $uploadpath . '/' . $filename;
                            $apartmentImage->save();
                        }
                    }

                    return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
                }
            } elseif ($request->input('apartment_type') === 'admin') {
                $validator2 = Validator::make($request->all(), [
                    'apartment_no_A' => 'required',
                    'floor_no' => 'required',
                    'building_no' => 'required',
                    'room_no' => 'required',
                    'price_per_day' => 'required',
                    'percentage' => 'required',
                    'note',
                ]);

                if ($validator2->fails()) {
                    foreach ($validator2->errors()->all() as $error) {
                        return redirect()->back()->with('errorMesg', $error);
                    }
                } else {
                    $apartment_data->apartness_no = $request->input('apartment_no_A');
                    $apartment_data->buliding_no = $request->input('building_no');
                    $apartment_data->floor_no = $request->input('floor_no');
                    $apartment_data->room_no = $request->input('room_no');
                    $apartment_data->cost_per_night = $request->input('price_per_day');
                    $apartment_data->not = $request->input('note');

                    $apartment_data_x = apartness_x::where('apartness_id', $apartment_data->id)->first();
                    $apartment_data_x->percentage = $request->input('percentage');
                    $apartment_data_x->save();
                    $owner_info->save();
                    $apartment_data->save();
                    if ($request->hasFile('images')) {
                        $uploadpath = 'images';
                        foreach ($request->file('images') as $image) {
                            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                            $filename = time() . '_' . uniqid() . '.' . $ext;
                            $image->move($uploadpath, $filename);

                            $apartmentImage = new apartment_images();
                            $apartmentImage->apartment_id = $apartment_data->id;
                            $apartmentImage->image = $uploadpath . '/' . $filename;
                            $apartmentImage->save();
                        }
                    }
                    return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
                }
            } else {
                return redirect()->back()->with('errorMesg', $error);
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
    public function delete_apartment_image($image_id)
    {
        try {
            $id = Crypt::decryptString($image_id);
            $apartment_image = apartment_images::find($id);

            // Delete the image file from the folder
            $imagePath = public_path($apartment_image->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the database record
            $apartment_image->delete();

            return redirect()->back()->with('success', 'تم تعديل البينات بنجاح');
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return redirect()->back()->with('errorMesg', $err);
        }
    }
}
