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
    @php
    $adminRole = env('ROLE_AS_ADMIN');
    $currentUser=auth()->user();
    @endphp
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h5 class="card-title">بينات الوحدات</h5> -->
                        @if($currentUser->type == $adminRole)
                        <a href="{{url('/admin/add/new/apartment')}}" class="btn btn-primary mt-2">اضافة وحدة جديدة</a>
                        @endif
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>

                                    <!-- <th>تقارير حجوزات الوحدة</th> -->
                                    @if($currentUser->type == $adminRole)
                                    <th>تعديل</th>
                                    @endif
                                    <th scope="col">الحالة </th>
                                    <th scope="col">فئة الوحدة</th>
                                    <th scope="col">السعر في اللية</th>
                                    <th scope="col">عداد الغرف</th>
                                    <th scope="col">رقم المبني</th>
                                    <th scope="col">رقم الطابق</th>
                                    <th scope="col">رقم الوحدة</th>
                                    @if($currentUser->type == $adminRole)
                                    <th scope="col">اسم المالك</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apartness_data as $aprtment)
                                @php
                                $encryptedId = Crypt::encryptString($aprtment->id);
                                @endphp
                                <tr>
                                    <!-- <td><a href=""><i class="bi bi-eye"></i> تقارير الوحدة</a></td> -->
                                    <!--<td><a href="/admin/aprtment/{{$encryptedId}}/report"><i class="bi bi-eye"></i> تقارير الوحدة</a></td>-->
                                    @if($currentUser->type == $adminRole)
                                    <td><a href="/admin/apartness/edite/{{$encryptedId}}"><i class="bi bi-pen"></i> تعديل</a></td>
                                    @endif
                                    <td style="direction: rtl;">
                                        @if($aprtment->statues == 0)
                                        <h5 style="color: green;">متاحة</h5>
                                        @else
                                        <h5 style="color: red;">محجوز</h5>
                                        @endif
                                    </td>
                                    <td>
                                        @if($aprtment->sys_type == 0)
                                        اداري
                                        @elseif($aprtment->sys_type == 1)
                                        خاص
                                        @else
                                        غير معرف
                                        @endif
                                    </td>                        
                                    <td>{{$aprtment->cost_per_night}} ج</td>
                                    <td>غرفة {{$aprtment->room_no}}</td>
                                    <td>{{$aprtment->buliding_no}} مبني </td>
                                    <td>{{$aprtment->floor_no}} طابق رقم</td>
                                    <td>{{$aprtment->apartness_no}} وحدة رقم</td>
                                    @if($currentUser->type == $adminRole)
                                    <td>
                                        @if($aprtment->owner)
                                        {{$aprtment->owner->name}}
                                        @else
                                        غير معرف
                                        @endif
                                    </td>
                                    @endif
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