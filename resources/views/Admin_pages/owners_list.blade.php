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
                                    <th>تقارير الوحدات</th>
                                    <th>تعديل معلومات المالك</th>
                                    <th scope="col">عداد الوحدات</th>

                                    <th scope="col">رقم التليفون</th>
                                    <th scope="col">الاسم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($owners as $owner)
                                @php
                                $encryptedId = Crypt::encryptString($owner->id);
                                $ownerID = Crypt::encryptString($owner->id);
                                $ownerApartmentCount = $apartment->where('owner_id', $owner->id)->count();
                                @endphp
                                <tr>
                                    <td>
                                            <a href="/admin/apartment/{{$encryptedId}}/reports">
                                            عرض التقارير
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/admin/edite/owner/{{$ownerID}}">
                                            تعديل
                                        </a>
                                    </td>
                                    <td>{{ $ownerApartmentCount }}</td>

                                    <td>{{ $owner->phone }}</td>
                                    <td>{{ $owner->name }}</td>
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