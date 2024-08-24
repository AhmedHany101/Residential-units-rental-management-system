@extends('layout.layout')
@section('layout')

<main id="main" class="main">
    <div class="pagetitle" style="direction: rtl;">
        <h1>بيانات الحجوزات</h1>
    </div>
    @php
    $adminRole = env('ROLE_AS_ADMIN');
    $currentUser=auth()->user();
    @endphp
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body tab_data">
                        <form action="{{ url('/admin/reservations/filter') }}" method="POST">
                            @csrf
                            <div class="row mb-3" style="direction: rtl;">
                                <div class="col-md-4">
                                    <label for="filterStartDate" class="form-label">من:</label>
                                    <input type="date" class="form-control" id="filterStartDate" name="start_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="filterEndDate" class="form-label">الي:</label>
                                    <input type="date" class="form-control" id="filterEndDate" name="end_date">
                                </div>
                                <div class="col-md-4 mt-4">
                                    <button type="submit" class="btn btn-primary" id="filterButton">تصفية</button>
                                </div>
                            </div>
                        </form>
                        <h5 class="card-title" style="direction: rtl;">الحجوزات المفتوحة</h5>
                        <a href="{{url('/admin/add/new/reservation')}}" class="btn btn-primary">اضافة حجز جديد</a>
                        <table class="table datatable" id="reservationTable">
                            <thead>
                                <tr>
                                    @if($currentUser->type == $adminRole)
                                    <th>تفاصيل الحجز</th>
                                    @endif
                                    <th scope="col">حالة الحجز</th>
                                    <th scope="col">اسم الموظف</th>
                                    <th scope="col">عداد الايام</th>
                                    <th scope="col">تاريخ نهاية الحجز</th>
                                    <th scope="col">تاريخ بداية الحجز</th>
                                    <th scope="col">اسم الوحدة</th>
                                    <th scope="col">المتبقي</th>

                                    <th scope="col">اسم العميل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resevations as $res)
                                @php
                                $encryptedId = Crypt::encryptString($res->id);
                                $apartment = \App\Models\apartness::find($res->department_id);

                                // Get the current date and time in the appropriate time zone
                                $today = \Carbon\Carbon::now('Asia/Riyadh');

                                // Parse the end date
                                $endDate = \Carbon\Carbon::parse($res->end_date, 'Asia/Riyadh');

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
                                $payment = $reservation_payment->where('reservation_id', $res->id)->first();

                                @endphp

                                <tr id="tab_data_er">
                                    @if($currentUser->type == $adminRole)
                                    <td><a href="/admin/reservations/{{$encryptedId}}/details"><i class="bi bi-eye"></i> عرض</a></td>
                                    @endif
                                    <td style="direction: rtl;">
                                        <h5 style="color: {{ $color }} !important;">{{ $status }}</h5>
                                    </td>
                                    <td>{{$res->employ_name}}</td>
                                    <td>{{ $res->days_no }}</td>
                                    <td>{{ $endDate->format('d/m/Y h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($res->start_date)->format('d/m/Y h:i') }}</td>
                                    <td>@if ($apartment){{ $apartment->apartness_no }}@endif</td>
                                    <td>{{$payment->remaining}}</td>
                                    <td>{{ $res->customer->name }}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
    $(document).ready(function() {
        // Retrieve the CSRF token value from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Function to update the table using AJAX
        function updateTable(startDate, endDate) {
            $.ajax({
                url: '/admin/reservations/filter',
                type: "POST",
                data: {
                    _token: csrfToken,
                    start_date: formatDate(startDate),
                    end_date: formatDate(endDate)
                },
                success: function(response) {
                    // Clear the existing table rows
                    $('#reservationTable tbody').append(response);

                    // Iterate over the reservations in the response and generate the table rows
                }
            });
        }

        // Apply filter when the filter button is clicked
        $('#filterButton').click(function() {
            var startDate = $('#filterStartDate').val();
            var endDate = $('#filterEndDate').val();
            updateTable(startDate, endDate);
        });

        // Function to format the date as 'YYYY-MM-DD'
        function formatDate(dateString) {
            var date = new Date(dateString);
            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }
    });
</script> -->
@endsection