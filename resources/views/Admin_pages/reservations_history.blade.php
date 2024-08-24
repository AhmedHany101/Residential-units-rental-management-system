@extends('layout.layout')
@section('layout')
<!-- @foreach($resevations as $res)
    @php
    $encrypted_id = Crypt::encryptString($res->id);
    @endphp
    /details">{{$res->id}}</a>
    @endforeach -->
<main id="main" class="main">
@php
    $adminRole = env('ROLE_AS_ADMIN');
    $currentUser=auth()->user();
    @endphp
    <div class="pagetitle" style="direction: rtl;">
        <h1>بيانات المحجوزات</h1>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="direction: rtl;">الحجوزات المنتهية</h5>
                        <form action="{{ url('/admin/reservations/close/filter') }}" method="POST">
                            @csrf
                            <div class="row mb-3" style="direction: rtl;">
                                <div class="col-md-4" style="direction: rtl;">
                                    <label for="filterStartDate" class="form-label" style="text-align: right;">  من:</label>
                                    <input type="date" class="form-control" id="filterStartDate" name="start_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="filterEndDate" class="form-label"> الي:</label>
                                    <input type="date" class="form-control" id="filterEndDate" name="end_date">
                                </div>
                                <div class="col-md-4 mt-4">
                                    <button type="submit" class="btn btn-primary" id="filterButton">تصفية</button>
                                </div>
                            </div>
                        </form>
                        <a href="{{url('/admin/add/new/reservation')}}" class="btn btn-primary">اضافة حجز جديد</a>
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                @if($currentUser->type == $adminRole)
                                    <th>تفاصيل الحجز</th>
                                    @endif
                                    <th scope="col">حالة الحجز</th>
                                    <th scope="col">عداد الايام</th>
                                    <th scope="col">تاريخ نهاية الحجز</th>
                                    <th scope="col">تاريخ بداية الحجز</th>
                                    <th scope="col">رقم الوحدة</th>
                                    <th scope="col">اسم العميل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                use Carbon\Carbon;
                                $apartness_model = \App\Models\apartness::all();
                                @endphp
                                @foreach($resevations as $res)
                                @php
                                $encryptedId = Crypt::encryptString($res->id);
                                $apartment = $apartness_model->find($res->department_id);

                                // Get the current date
                                $today = Carbon::today();

                                // Parse the end date
                                $endDate = Carbon::parse($res->end_date);

                                // Calculate the difference in days between the end date and today
                                $daysDifference = $endDate->diffInDays($today);

                                // Determine the status based on the end date
                                if ($endDate->isPast()) {
                                $status = 'انته الحجز من ' . $endDate->diffInDays($today) . ' يوم';
                                $color = 'red';
                                } elseif ($endDate->isToday()) {
                                $status = 'اليوم';
                                $color = 'yellow';
                                } elseif ($endDate->isTomorrow()) {
                                $status = 'سوف ينتهي الحجز غدا';
                                $color = 'green';
                                } else {
                                $status = 'متبقي علي انتهاء الحجز ' . $daysDifference;
                                $color = 'green';
                                }
                                @endphp
                                <tr>
                                @if($currentUser->type == $adminRole)
                                <td><a href="/admin/reservations/{{$encryptedId}}/details"><i class="bi bi-eye"></i> عرض</a></td>
                                @endif
                                    <td style="direction: rtl;">
                                        <h5>منتهي</h5>
                                    </td>
                                    <td>{{$res->days_no}}</td>
                                    <td>{{$res->end_date}}</td>
                                    <td>{{$res->start_date}}</td>
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

            </div>
        </div>
    </section>

</main>
@endsection