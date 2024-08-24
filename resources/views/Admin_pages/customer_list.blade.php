@extends('layout.layout')
@section('layout')
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h5 class="card-title">بينات الوحدات</h5> -->
                        <a href="{{url('/admin/add/new/apartment')}}" class="btn btn-primary mt-2">اضافة وحدة جديدة</a>
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>عرض بيانات العميل</th>
                                    <th>تفاصيل الحساب</th>
                                    <th scope="col">عدد مرات الحجز</th>
                                    <th scope="col">رقم التليفون</th>
                                    <th scope="col">الرقم القومي</th>
                                    <th scope="col">الاسم</th>
                                    <th scope="col">المتبقي علي العميل</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer_data as $customer)
                                @php
                                $encryptedId = Crypt::encryptString($customer->id);
                                $reservationNum = \App\Models\reservation_data::where('customer_id', $customer->id)->count();
                                $curr_res = \App\Models\reservation_data::where('customer_id', $customer->id)->where('reservation_status', 0)->first();
                                $remaining = \App\Models\reservation_payment::where('reservation_id', $curr_res?->id)->first()?->remaining;
                                @endphp
                                <tr>
                                    <td>
                                        <a href="/admin/customer/info/{{$encryptedId}}">
                                            عرض بيانات العميل
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/admin/customer/reservation/{{$encryptedId}}">
                                            تفاصيل الحساب 
                                        </a>
                                    </td>
                                    <td>{{ $reservationNum }}</td>
                                    <td>{{ $customer->phone_one }}</td>
                                    <td>{{$customer->national_id}}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>
                                        @if($remaining == 0)
                                        <h5 class="green" style="color: green;">{{$remaining}}</h5>
                                        @else
                                        <h5 class="green" style="color: red;">{{$remaining}}</h5>
                                        @endif
                                    </td>
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