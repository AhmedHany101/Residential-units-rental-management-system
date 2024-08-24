@extends('layout.layout')
@section('layout')
<main id="main" class="main">
    <div class="pagetitle" style="direction: rtl;">
        <h1>بيانات الخزنة</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body table-responsive">

                    <a href="{{url('/admin/add/cash')}}" class="btn btn-primary mt-2">ايداع نقدي</a>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">التاريخ </th>
                                    <th scope="col">البند</th>
                                    <th scope="col">القيمة</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($all_transactions as $transaction)
                                <tr id="tab_data_er">
                                    <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y') }}</td>
                                    <td>{{ $transaction['type'] }}</td>
                                    <td>{{ $transaction['amount'] }}</td>
                                </tr>
                                @endforeach
                                <!-- <tr>
                                    <td></td>
                                    <td></td>
                                 
                                    <td style="font-size: x-large;color:green">{{$total}}</td>
                                    
                                </tr> -->
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection