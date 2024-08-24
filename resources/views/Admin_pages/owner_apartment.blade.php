@extends('layout.layout')
@section('layout')
<style>
    .alerterr {
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

    .alert {
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
    .closebtn {
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
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        width: 100% !important;
        box-sizing: border-box;
    }
</style>
@if(session('errorMesg'))
<div id="errorMessage" class="alert_danger alerterr"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>{{ session('errorMesg') }}</div>
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
<div class="alert" id="suceesMessage">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
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
@if ($errors->any())
<div class="alerterr alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<main id="main" class="main">
    <div class="pagetitle" style="direction: rtl;">
        <h1>بيانات الوحدات</h1>
        <!-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav> -->
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            @foreach($apartness as $apartment)
            @php
            $resevations_for_apartment = $resevations->where('department_id', $apartment->id);
            $reservationcounting = $resevations_for_apartment->count();
            $apart_encryptedId = Crypt::encryptString($apartment->id);

            $reservationcountingpermonth = $resevations_for_apartment->filter(function ($reservation) {
            return Carbon\Carbon::parse($reservation->created_at)->month == date('m');
            })->count();

            $expenseTotal = $expness->where('apartment_id', $apartment->id)->sum('cost');
            @endphp
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="direction: rtl;">وحدة رقم {{$apartment->apartness_no}}
                        @if($apartment->statues == 0)
                        (<span style="color: green;">الوحدة متاحة</span>)
                        @else
                        (<span style="color: red;">الوحدة محجوزة</span>)
                        @endif
                        <a href="/admin/apartness/edite/{{$apart_encryptedId}}" class="btn btn-primary m-2">تعديل</a>
                    </h5>
                    <!-- Bordered Tabs Justified -->
                    <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified-{{ $loop->index }}" role="tablist" style="direction: rtl;">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active" id="main-tab-{{ $loop->index }}" data-bs-toggle="tab" data-bs-target="#bordered-justified-main-{{ $loop->index }}" type="button" role="tab" aria-controls="home-{{ $loop->index }}" aria-selected="true">نظرة عامة</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="home-tab-{{ $loop->index }}" data-bs-toggle="tab" data-bs-target="#bordered-justified-home-{{ $loop->index }}" type="button" role="tab" aria-controls="home-{{ $loop->index }}" aria-selected="true">معلومات الوحدة</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="profile-tab-{{ $loop->index }}" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile-{{ $loop->index }}" type="button" role="tab" aria-controls="profile-{{ $loop->index }}" aria-selected="false">الحوجزات</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="contact-tab-{{ $loop->index }}" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact-{{ $loop->index }}" type="button" role="tab" aria-controls="contact-{{ $loop->index }}" aria-selected="false">المصاريف</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="report-tab-{{ $loop->index }}" data-bs-toggle="tab" data-bs-target="#bordered-justified-report-{{ $loop->index }}" type="button" role="tab" aria-controls="report-{{ $loop->index }}" aria-selected="false">تقارير عامة</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2" id="borderedTabJustifiedContent-{{ $loop->index }}" style="direction: rtl;">
                        <div class="tab-pane fade show active" id="bordered-justified-main-{{ $loop->index }}" role="tabpanel" aria-labelledby="main-tab-{{ $loop->index }}">
                            <section class="section dashboard">
                                <div class="row">
                                    <div class="col-xxl-4 col-md-6">
                                        <div class="card info-card sales-card">
                                            <div class="card-body">
                                                <h5 class="card-title">عدد الحوجزات <span>| هذا الشهر</span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-calendar-week"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6> {{ $reservationcountingpermonth }} </h6>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End Sales Card -->
                                    <!-- Revenue Card -->
                                    <div class="col-xxl-4 col-md-6">
                                        <div class="card info-card revenue-card">
                                            <div class="card-body">
                                                <h5 class="card-title">عدد الحوجزات <span>| الاجمالي</span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-calendar3"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6> {{$reservationcounting}} </h6>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End Revenue Card -->

                                    <!-- Customers Card -->
                                    <!-- <div class="col-xxl-4 col-xl-6">

                                        <div class="card info-card customers-card">
                                            <div class="card-body">
                                                <h5 class="card-title">المصاريف <span>| الاجمالي </span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-currency-pound"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{$expenseTotal}}</h6>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div> -->
                                    <div class="col-xxl-4 col-xl-6">
                                        <div class="card info-card customers-card">
                                            <div class="card-body">
                                                <h5 class="card-title">حوجزات الوحدة <span>| الاجمالي </span></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-currency-pound"></i>
                                                    </div>
                                                    @php
                                                    $totalResvationPerMonth = 0;
                                                    $reservations = $resevations->where('department_id', $apartment->id)->where('reservation_status',1);
                                                    foreach ($reservations as $reservation) {
                                                    $paymentInfo = $resevationsPaymentInfo->where('reservation_id', $reservation->id);
                                                    if ($paymentInfo->count() > 0) {
                                                    $totalResvationPerMonth += $paymentInfo->sum('total');
                                                    }
                                                    }
                                                    @endphp
                                                    <h6>{{ $totalResvationPerMonth }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($apartment->sys_type == 0)
                                    @foreach($apartness_payment as $payment)
                                    @if($payment->apartness_id == $apartment->id)
                                    <div class="col-xxl-4 col-xl-6">
                                        <div class="card info-card customers-card">
                                            <div class="card-body">
                                                <h5 class="card-title">مستحقات المالك <span>| الاجمالي </span></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-currency-pound"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{ $payment->total_account }}</h6>
                                                        @if($payment->total_account > 0)
                                                        <a href="#" type="button" style="text-decoration: underline;font-weight: bolder;" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $payment->id }}">سدد نقدي</a>
                                                        @endif

                                                    </div>

                                                    <div class="modal fade" id="exampleModal{{ $payment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $payment->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel{{ $payment->id }}" style="direction: ltr;">سدد نقدي</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{url('/admin/add/payment')}}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="{{ $payment->id }}">
                                                                        <div class="mb-3">
                                                                            <label for="total" class="form-label">الاجمالي</label>
                                                                            <input type="text" class="form-control" id="total_input{{ $payment->id }}" value="{{ $payment->total_account }}" readonly>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="payed" class="form-label">سدد</label>
                                                                            <input type="text" class="form-control" id="payed{{ $payment->id }}" name="payed_cost">
                                                                        </div>
                                                                        <div class="col mb-4">
                                                                            <label for="cashPayed" class="form-label">اضافة اسم الموظف</label>
                                                                            <div class="col-sm-10">
                                                                                <select class="form-select" name="staff_name" aria-label="Default select example">
                                                                                    @foreach($users_name as $name)
                                                                                    <option value="{{$name->name}}">{{$name->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                                                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        // Assuming you're using jQuery
                                                        $('#exampleModal{{ $payment->id }}').on('show.bs.modal', function(event) {
                                                            var button = $(event.relatedTarget); // Button that triggered the modal
                                                            var total = button.data('total');
                                                            var id = button.data('id');

                                                            // Update the modal's fields
                                                            $(this).find('#total_input{{ $payment->id }}').val(total);
                                                            // You can also update the 'payed' field here if necessary
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-xl-6">
                                        <div class="card info-card customers-card">
                                            <div class="card-body">
                                                <h5 class="card-title"> المنصة <span>| نسبة</span></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-currency-pound"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{$payment->percentage}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                            </section>
                        </div>
                        <div class="tab-pane fade" id="bordered-justified-home-{{ $loop->index }}" role="tabpanel" aria-labelledby="home-tab-{{ $loop->index }}">
                            @if($apartment->sys_type == 0)
                            <p style="font-size: large;"> نوع الوحدة : اداري</p>
                            @else
                            <p style="font-size: large;"> نوع الوحدة : خاص</p>
                            @endif
                            <p style="font-size: large;">رقم الوحدة : {{$apartment->apartness_no}}</p>
                            <p style="font-size: large;"> الدور : {{$apartment->floor_no}}</p>
                            <p style="font-size: large;">رقم المبني : {{$apartment->buliding_no}}</p>
                            <p style="font-size: large;"> عدد الغرف : {{$apartment->room_no}}</p>
                            <p style="font-size: large;"> سعر الليلة : {{$apartment->cost_per_night}}</p>
                            <p style="font-size: large;"><span>تاريخ الاضافة : </span> {{ date('d/m/Y', strtotime($apartment->created_at)) }}</p> @if($apartment->not != "")
                            <p style="font-size: large;"> ملاحظة : {{$apartment->not}}</p>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="bordered-justified-profile-{{ $loop->index }}" role="tabpanel" aria-labelledby="profile-tab-{{ $loop->index }}">
                            <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                                <thead>
                                    <tr>
                                        <th>تفاصيل الحجز</th>
                                        <th scope="col">عداد الايام</th>
                                        <th scope="col">تاريخ ن الحجز</th>
                                        <th scope="col">تاريخ بداية الحجز</th>
                                        <th scope="col">اسم العميل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resevations->where('department_id', $apartment->id) as $item)
                                    @php
                                    $encryptedId = Crypt::encryptString($item->id);

                                    @endphp
                                    <tr id="tab_data_er">
                                        <td><a href="/admin/reservations/{{$encryptedId}}/details"><i class="bi bi-eye"></i> عرض</a></td>
                                        <td>{{$item->days_no}}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</td>
                                        <td>{{$item->customer->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($apartment->sys_type == 0)
                        <div class="tab-pane fade" id="bordered-justified-report-{{ $loop->index }}" role="tabpanel" aria-labelledby="report-tab-{{ $loop->index }}">
                            <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                                <thead>
                                    <tr>
                                        <th>الاجمالي</th>
                                        <th scope="col">القيمة</th>
                                        <th scope="col">التاريخ</th>
                                        <th scope="col">نوع العملية</th>
                                        <th scope="col">اسم الموظف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($general_report->where('apartment_id', $apartment->id) as $item)
                                    @php
                                    $encryptedId = Crypt::encryptString($item->id);
                                    @endphp
                                    <tr id="tab_data_er">
                                        <td>{{$item->total_account}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($item->process_type == 0 )
                                            ايداع حجز
                                            @elseif($item->process_type == 1)
                                            سدد نقدي
                                            @else
                                            سحب مصورفات
                                            @endif
                                        </td>
                                        <td>{{$item->data_entry_name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="tab-pane fade" id="bordered-justified-report-{{ $loop->index }}" role="tabpanel" aria-labelledby="report-tab-{{ $loop->index }}">
                            <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                                <thead>
                                    <tr>
                                        <th>المتبقي</th>
                                        <th>الاجمالي</th>
                                        <th scope="col">القيمة</th>
                                        <th scope="col">التاريخ</th>
                                        <th scope="col">نوع العملية</th>
                                        <th scope="col">اسم الموظف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($privat_apartness_report->where('apartment_id', $apartment->id) as $item)
                                    @php
                                    $encryptedId = Crypt::encryptString($item->id);
                                    @endphp
                                    <tr id="tab_data_er">
                                        <td>{{$item->remainging}}</td>
                                        <td>{{$item->total_account}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            سدد نقدي
                                        </td>
                                        <td>{{$item->data_entry_name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div class="tab-pane fade" id="bordered-justified-contact-{{ $loop->index }}" role="tabpanel" aria-labelledby="contact-tab-{{ $loop->index }}">
                            <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                                <thead>
                                    <tr>
                                        <th scope="col">التاريخ </th>
                                        <th scope="col">اسم منفذ العملية</th>
                                        <th scope="col">البند</th>
                                        <th scope="col">التكلفة</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expness->where('apartment_id', $apartment->id) as $item)
                                    <tr id="tab_data_er">
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>{{$item->data_entry_name}}</td>
                                        <td>{{ $item->note}}</td>

                                        <td>{{$item->cost}}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- End Bordered Tabs Justified -->
            </div>
        </div>
        @endforeach
        </div>
    </section>
</main>

@endsection