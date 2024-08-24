@extends('layout.layout')
@section('layout')
<style>
    @keyframes heartbeat {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
<main id="main" class="main">

    <div class="pagetitle">
        <h1 style="direction: rtl;">بيانات حجوزات عميل : {{$customer->name}}</h1>
    </div>
    <!-- End Page Title -->
    <h3 style="direction: rtl;">الحجوزات الجارية</h3>

    @foreach ($customerReservations as $reservation)
    @if ($reservation->reservation_status == 0)
    @php
    $encryptedId = Crypt::encryptString($reservation->id);

    @endphp
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body" style="direction: rtl;">
                            <div class="row m-2">
                                <div class=" col-lg-4 p-2">
                                    <p> وحدة رقم :{{ $reservation->apartness_no }}</p>
                                    <p>
                                        نوع الوحدة :
                                        @if($reservation->apartment_type == 0 )
                                        اداري
                                        @else
                                        خاص
                                        @endif
                                    </p>
                                    <p> عداد ايام الحجز : {{$reservation->days_no}}</p>
                                    <p> تكلفة الليلة داخل الحجز {{$reservation->price_per_night}}</p>
                                    <a href="/admin/reservations/{{$encryptedId}}/details" class="btn btn-primary"><i class="bi bi-eye"></i> عرض</a></a>

                                </div>
                                <div class=" col-lg-4 p-2">
                                    <p>
                                        تم الدفع عن طريق : @if($reservation->payment_method == 1)
                                        نقدي
                                        @elseif($reservation->payment_method == 2)
                                        بنك
                                        @elseif($reservation->payment_method == 3) محفظة
                                        @endif
                                    </p>
                                    <p> المبلغ المتبقي : {{ $reservation->remaining }}</p>
                                    <p> تم دفع : {{ $reservation->payed }}</p>
                                    <p> اجمالي تكلفة الحجز : {{ $reservation->total }}</p>
                                    <p> تاريخ نهاية الحجز : {{$reservation->end_date}}</p>
                                    <p> تاريخ بداية الحجز : {{$reservation->start_date}}</p>reservation
                                </div>
                                <div class=" col-lg-4 p-2" style="text-align: center;">
                                    @php
                                  
                                    // Get the current date and time in the appropriate time zone
                                    $today = \Carbon\Carbon::now('Asia/Riyadh');

                                    // Parse the end date
                                    $endDate = \Carbon\Carbon::parse($reservation->end_date, 'Asia/Riyadh');

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
                                    @endphp
                                    <div style="display: inline-block; width: 200px; height: 200px; border-radius: 50%; background-color: {{$color}}; text-align: center; line-height: 200px; color: white; font-size: 24px; animation: heartbeat 1s infinite;">
                                        {{$status}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="direction: rtl;">الحجوزات المنتهية</h5>
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>طريقة الدفع</th>
                                        <th>متبقي</th>
                                        <th>تم دفع </th>
                                        <th>اجمالي الحجز</th>
                                        <th>انتهاء الحجز</th>
                                        <th>بداية الحجز</th>
                                        <th>عدد ايام الحجز</th>
                                        <th>سعر الليلة داخل الحجز</th>
                                        <th>نوع الوحدة</th>
                                        <th>رقم الوحدة</th>

                                    </tr>
                                </thead>
                                @if ($customerReservations->isNotEmpty())
                                <tbody>
                                    @foreach ($customerReservations as $reservation)
                                    @if ($reservation->reservation_status == 1)
                                    <tr>

                                        <td>@if($reservation->payment_method == 1)
                                            نقدي
                                            @elseif($reservation->payment_method == 2)
                                            بنك
                                            @elseif($reservation->payment_method == 3) محفظة
                                            @endif
                                        </td>

                                        <td>{{ $reservation->remaining }}</td>
                                        <td>{{ $reservation->payed }}</td>
                                        <td>{{ $reservation->total }}</td>
                                        <td>{{$reservation->end_date}}</td>
                                        <td>{{$reservation->start_date}}</td>
                                        <td>{{$reservation->days_no}}</td>
                                        <td>{{$reservation->price_per_night}}</td>
                                        <td>
                                            @if($reservation->apartment_type == 0 )
                                            اداري
                                            @else
                                            خاص
                                            @endif
                                        </td>
                                        <td>{{ $reservation->apartness_no }}</td>

                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                @else
                                <p>No reservations found for the customer.</p>
                                @endif
                            </table>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
</main>
@endsection