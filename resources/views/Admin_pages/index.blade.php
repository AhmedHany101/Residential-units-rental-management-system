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


<!-- End Page Title -->
@php
$user=auth()->user();
$adminRole = env('ROLE_AS_ADMIN');
@endphp
@if($user->type == $adminRole)
<main id="main" class="main">
    <div class="pagetitle" style="direction:rtl">
        <h1>لوحه التحكم</h1>
        <nav>
            <ol class="breadcrumb">
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12" style="direction:rtl">
                <div class="row">

                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">الحوجزات هذا <span>| الشهر</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$reservation_this_month}}</h6>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">الحوجزات <span>| اجمالي</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$total_reservation}}</h6>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Sales Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">الخزنة<span></span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-pound"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$total}}</h6>
                                        <a href="{{url('/admin/cash/report')}}" style="text-decoration: underline;">التقارير</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">اجمالي خزنة الحجوزات<span></span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-pound"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$main_total}}</h6>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> -->
                    <!-- End Revenue Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title"> العملاء<span> </span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$customer_number}}</h6>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- End Customers Card -->
                </div><!-- End Left side columns -->
            </div>

            <div class="col-lg-12">
                <div class="row">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">الحجوزات | الشهر</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>

                                        <th>تفاصيل الحجز</th>

                                        <th scope="col">حالة الحجز</th>
                                        <th scope="col">عداد الايام</th>
                                        <th scope="col">تاريخ ن الحجز</th>
                                        <th scope="col">تاريخ بداية الحجز</th>
                                        <th scope="col">رقم الوحدة</th>
                                        <th scope="col">اسم العميل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resrvation as $res)

                                    @php
                                    $encryptedId = Crypt::encryptString($res->id);
                                    $apartment = \App\Models\apartness::find($res->department_id);

                                    if($res->reservation_status == 0)
                                    {
                                    $status="الحجز مفتوح";
                                    $color = 'red';
                                    }else{
                                    $status="الحجز مغلق";
                                    $color = 'green';
                                    }

                                    @endphp
                                    <tr id="tab_data_er">
                                        <td><a href="/admin/reservations/{{$encryptedId}}/details"><i class="bi bi-eye"></i> عرض</a></td>
                                        <td style="direction: rtl;">
                                            <h5 style="color: {{$color}} !important;">{{$status}}</h5>
                                        </td>
                                        <td>{{$res->days_no}}</td>
                                        <td>{{ \Carbon\Carbon::parse($res->end_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($res->start_date)->format('d/m/Y') }}</td>
                                        <td>@if ($apartment)
                                            {{$apartment->apartness_no}}
                                            @endif
                                        </td>
                                        <td>{{$res->customer->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>
                    <!-- End Customers Card -->
                </div><!-- End Left side columns -->
            </div>
        </div>
    </section>
</main>
@else
<div class="pagetitle" style="direction:rtl">
    <h1>لوحه التحكم</h1>
    <nav>
        <ol class="breadcrumb">
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12" style="direction:rtl">
            <div class="row">
            </div><!-- End Left side columns -->
        </div>

    </div>
</section>
@endif

@endsection