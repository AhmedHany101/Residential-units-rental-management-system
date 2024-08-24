@extends('layout.layout')
@section('layout')
<style>
    p {
        font-size: large;
        font-weight: bold;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    h2 {
        font-size: larger !important;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important;
        font-weight: bolder !important;
    }

    #image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        overflow: auto;
        text-align: center;
    }

    #modal-image {
        display: block;
        max-width: 80%;
        max-height: 80%;
        margin: 10% auto;
    }

    .close-btn {
        color: red;
        font-size: 34px;
        font-weight: bold;
        position: fixed;
        top: 10px;
        right: 60px;
        cursor: pointer;
        z-index: 9999;
    }

    @media (max-width: 768px) {
        #modal-image {
            max-width: 100%;
            max-height: 80vh;
        }

        .close-btn {
            top: 20px;
            right: 20px;
        }
    }
</style>
<style>
    .alerterrr {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 20px;
        background-color: red;
        /* Red */
        color: white;
        margin-bottom: 15px;
        z-index: 9999999;
    }

    .alertr {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 20px;
        background-color: green;
        /* Red */
        color: white;
        margin-bottom: 15px;
        z-index: 9999999;
    }

    /* The close button */
    .closebtna {
        margin-left: 15px;
        color: white;
        font-weight: bold;
        float: right;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        transition: 0.3s;
    }
</style>
<main id="main" class="main">
    @if(session('errorMesg'))
    <div id="errorMessage" class="alert_danger alerterrr"><span class="closebtna" onclick="this.parentElement.style.display='none';">&times;</span>{{ session('errorMesg') }}</div>
    <script>
        // Show the error message
        document.getElementById('errorMessage').style.display = 'block';
        // Hide the error message after 5 seconds
        setTimeout(function() {
            document.getElementById('errorMessage').style.display = 'none';
        }, 5000);
        console.log('err');
    </script>
    @endif
    @if(session('success'))
    <div class="alertr" id="suceesMessage">
        <span class="closebtna" onclick="this.parentElement.style.display='none';">&times;</span>
        {{session('success')}}
    </div>
    <!-- <div class="alert alert-primary alert-dismissable fade in" id="suceesMessage"> <button type="button" data-dismiss="alert" aria-label="close" class="close"><span aria-hidden="true">×</span></button><strong>Well done!</strong> </div> -->
    <!-- <div id="suceesMessage" class="alert alert-success" style="display:none;"></div> -->
    <script>
        // Show the error message
        document.getElementById('suceesMessage').style.display = 'block';
        // Hide the error message after 5 seconds
        setTimeout(function() {
            document.getElementById('suceesMessage').style.display = 'none';
        }, 5000);
    </script>

    @endif
    @php
    $user=auth()->user();

    $encryptedId = Crypt::encryptString($reservationData->id);
    $customerId=Crypt::encryptString($customer_info->id);
    $apart_encryptedId=Crypt::encryptString($department_info->id);

    // Get the current date and time in the appropriate time zone
    $today = \Carbon\Carbon::now('Asia/Riyadh');

    // Parse the end date
    $endDate = \Carbon\Carbon::parse($reservationData->end_date, 'Asia/Riyadh');

    // Check if the end date is in the future
    if ($endDate->isFuture()) {
    $daysRemaining = $endDate->diffInDays($today);
    if ($daysRemaining > 1) {
    $status = 'متبقي ' . $daysRemaining . ' يوم على انتهاء الحجز';
    } else {
    $hoursRemaining = $endDate->diffInHours($today);
    $minutesRemaining = $endDate->diffInMinutes($today) % 60;
    $status = 'متبقي ' . $hoursRemaining . ' ساعة و ' . $minutesRemaining . ' دقيقة على انتهاء الحجز';
    }
    $color = 'green';
    } else {
    $daysElapsed = $today->diffInDays($endDate);
    $status = 'انتهى الحجز منذ ' . $daysElapsed . ' يوم';
    $color = 'red';
    }
    $adminRole = env('ROLE_AS_ADMIN');

    @endphp
    <section class="section" style="direction: rtl;">
        <div class="row align-items-top">
            <div class="col-lg-12">
                <div class="card">
                    @if($reservationData->reservation_status == 0)
                    @if($color=='red')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="direction: rtl;">
                        <i class="bi bi-exclamation-octagon me-1"></i>

                        {{$status}}
                        <button type="button" class="btn-close disabled" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @elseif($color=='yellow')
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="direction: rtl;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{$status}}
                        <button type="button" class="btn-close disabled" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @elseif($color=='green1')

                    <div class="alert alert-info alert-dismissible fade show" role="alert" style="direction: rtl;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        <i class="bi bi-info-circle me-1"></i>
                        {{$status}}

                    </div>
                    @elseif($color=='green')
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="direction: rtl;">
                        {{$status}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @endif
                    <div class="card-body">
                        <h2 class="card-title text-center" style="text-decoration: underline;">بينات الحجز</h2>
                        <div class="row">
                            <div class="col-lg-2">
                                <h2 class="card-title" style="text-decoration: underline;">بيانات العميل:</h2>
                            </div>
                            <div class="col-lg-2">
                                <a class="btn btn-primary" href="/admin/edite/{{$customerId}}/information" onclick="return confirmAction('تعديل')">تعديل بيانات العميل</a>
                            </div>
                            @if($reservationData->reservation_status == 0)
                            @if($reservation_payment->payed == $reservation_payment->total)
                            <div class="col-lg-2">
                                <a href="/admin/close/{{$encryptedId}}/reservation" class="btn btn-success" onclick="return confirmAction('قفل الحجز')">قفل الحجز</a>
                            </div>
                            @else
                            <div class="col-lg-2">
                                <a href="" class="btn btn-success" disabled onclick="err('باقي'); return false;">قفل الحجز</a>
                            </div>
                            @endif
                            <!-- <div class="col-lg-2">
                                <a href="{{url('/')}}" class="btn btn-warning" onclick="return confirmAction('الغاء الحجز')">الغاء الحجز</a>
                            </div> -->

                            <div class="col-lg-2">
                                <a   href="/admin/delete/reservation/{{$encryptedId}}" class="btn btn-danger" onclick="return confirmAction('حذف الحجز')">حذف الحجز</a>
                            </div> 
                            @endif
                        </div>

                        <script>
                            function confirmAction(action) {
                                if (confirm('هل أنت متأكد من رغبتك في ' + action + '؟')) {
                                    // User confirmed the action, proceed with the logic
                                    switch (action) {
                                        case 'تعديل':
                                            // Logic for editing
                                            break;
                                        case 'قفل الحجز':
                                            // Logic for locking the reservation
                                            break;
                                        case 'الغاء الحجز':
                                            // Logic for canceling the reservation
                                            break;
                                        case 'حذف الحجز':
                                            // Logic for deleting the reservation
                                            break;
                                        default:
                                            break;
                                    }
                                    return true; // Allow the default action to proceed
                                }
                                return false; // Prevent the default action
                            }

                            function err(action) {
                                const message = `يجب سدد اجمالي الحجز !`;
                                window.alert(message);
                                // No need to return anything, as we're preventing the default link action
                            }
                        </script>
                        <p style="color: black;">الاسم : {{$customer_info->name}}</p>
                        <p style="color: black;">رقم التليفون 1 : {{$customer_info->phone_one}}</p>
                        @if($customer_info->phone_two != '')
                        <p style="color: black;">رقم التليفون 2 : {{$customer_info->phone_two}}</p>
                        @endif
                        @if($customer_info->national_id != '')
                        <p style="color: black;">الرقم القومي : {{$customer_info->national_id}}</p>
                        @endif
                        <p style="color: black;"> جنسية العميل: @if($customer_info->customer_type == 0) مصري الجنسية @else اجنبي الجنسية @endif</p>
                        <p style="color: black;"> تقيم العميل: @if($customer_info->customer_rate == 0) غير معرف @elseif($customer_info->customer_rate == 1) سيء @elseif($customer_info->customer_rate == 2) جيد @elseif($customer_info->customer_rate == 3) VIP @endif</p>
                        @foreach($reservation_paper as $item)
                        <div class="row">
                            @if($item->id_face != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->id_face) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                            @if($item->id_back != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->id_back) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                            @if($item->marriage_certificate != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->marriage_certificate) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                            @if($item->passport != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->passport) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                        </div>
                        <br>
                        @endforeach
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <h2 class="card-title" style="text-decoration: underline;">تفاصيل الحجز:</h2>
                            </div>
                            @if($reservationData->reservation_status == 0)
                            <div class="col-lg-2">
                                <a href="/admin/edite/reservation/{{$encryptedId}}/data" onclick="return confirmAction('تعديل')" class="btn btn-primary">تعديل بيانات الحجز</a>
                            </div>
                            <div class="col-lg-2">
                                <a href="/admin/add/expness/{{$encryptedId}}" onclick="return confirmAction('تعديل')" class="btn btn-primary"> اضافة مصروف</a>
                            </div>
                            @endif
                        </div>

                        <div>
                            <p style="color: black;">
                                <span style="color: black;">الوحدة رقم: {{$department_info->apartness_no}},</span>
                                <span style="color: black;">طابق رقم: {{$department_info->floor_no}},</span>
                                <span style="color: black;">مبنى: {{$department_info->buliding_no}}</span>
                                <!-- <a href="/admin/apartness/edite/{{$apart_encryptedId}}">(تفاصيل الوحدة)</a> -->
                            </p>
                        </div>
                        <div>
                            <p style="color: black;">بداية الحجز: {{ \Carbon\Carbon::parse($reservationData->start_date)->format('d/m/Y h:i A') }}</p>
                            <p style="color: black;">نهاية الحجز: {{ \Carbon\Carbon::parse($reservationData->end_date)->format('d/m/Y h:i A') }}</p>
                            <p style="color: black;">عدد أيام الحجز: {{$reservationData->days_no}}</p>
                            <p style="color: black;">السعر في الليلة: {{$reservationData->price_per_night}}</p>
                            <p style="color: black;">اجمالي تكلفة الحجز: {{$reservation_payment->total}}</p>
                            <p style="color: black;">العاربون: {{$reservation_payment->payed}}</p>
                            <p style="color: black;">المتبقي من تكلفة الحجز: {{$reservation_payment->remaining}}</p>
                            <p style="color: black;"> دفع من خلال: @if($reservation_payment->payment_method == 1) نقدي @elseif($reservation_payment->payment_method == 2) بنكي @elseif($reservation_payment->payment_method == 3) محفظة @endif</p>

                            @if($reservationData->notes != '')
                            <p style="color: black;"> ملاحظة : {{$reservationData->notes}}</p>
                            @endif
                        </div>
                        @if(!empty($reservationOutgoing))
                        <hr>
                        <h4 style="text-decoration: underline;">مصاريف الحجز</h4>
                        <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                            <thead>
                                <tr>
                                    <th scope="col">التاريخ </th>
                                    <th scope="col">البند</th>
                                    <th scope="col">التكلفة</th>
                                    @if($reservationData->reservation_status == 0)
                                    <th scope="col">حذف</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservationOutgoing as $item)
                                @php
                                $encryptedExpeneseId = Crypt::encryptString($item->id);
                                @endphp
                                <tr id="tab_data_er">
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                    <td>{{$item->descrbtion}}</td>
                                    <td>{{$item->cost}}</td>
                                    @if($reservationData->reservation_status == 0)
                                    <td><a href="/admin/delete/reservation/expenese/{{$encryptedExpeneseId}}" onclick="return confirmAction('حذف')"style="color: red;">حذف</a></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    function openImage(img) {
        var modal = document.getElementById("image-modal");
        var modalImg = document.getElementById("modal-image");

        modal.style.display = "block";
        modalImg.src = img.src;
    }

    function closeImageModal() {
        var modal = document.getElementById("image-modal");
        modal.style.display = "none";
    }
</script>
<div id="image-modal">
    <span class="close-btn" onclick="closeImageModal()">&times;</span>
    <img id="modal-image" src="" alt="Modal Image">
</div>

@endsection