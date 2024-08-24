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
<main id="main" class="main">
    <div class="pagetitle" style="direction: rtl;">
        <h1>بيانات المصروفات</h1>
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
                    <div class="card-body table-responsive">
                        <a href="{{url('/admin/add/new/expenses')}}" class="btn btn-primary mt-2">اضافة مصروفات</a>
                        @if($currentUser->type == $adminRole)
                        <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                            <thead>
                                <tr>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">اسم منفذ العملية</th>
                                    <th scope="col">نوع الصرف</th>
                                    <th scope="col">البند</th>
                                    <th scope="col">التكلفة</th>
                                    <th scope="col">حذف</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenese_data as $item)
                                @php
                                $encryptedId = Crypt::encryptString($item->id);
                                @endphp
                                <tr id="tab_data_er">
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>

                                    <td>{{ $item->data_entry_name }}</td>

                                    <td>
                                        @if($item->expense_type == 1)
                                        مكتبي
                                        @elseif($item->expense_type == 2)
                                        عمالة
                                        @elseif($item->expense_type == 3)
                                        وحدات خاصة
                                        @elseif($item->expense_type == 4)
                                        وحدات إدارية
                                        @endif
                                    </td>
                                    <td>{{ $item->note }}</td>

                                    <td>{{ $item->cost }}</td>
                                    <td><a href="/admin/delete/expenses/{{$encryptedId}}" onclick="return confirmAction('حذف ')" style="color: red;">حذف</a></td>
                                </tr>
                                @endforeach
                                <!-- <tr id="total-row">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $expenese_data->sum('cost') }}</td>
                                </tr> -->
                            </tbody>

                        </table>
                        @else
                        <table class="table datatable" id="reservationTable" style="direction:ltr!important">
                            <thead>
                                <tr>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">اسم منفذ العملية</th>
                                    <th scope="col">نوع الصرف</th>
                                    <th scope="col">البند</th>
                                    <th scope="col">التكلفة</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                        @endif
                    </div>
                </div>
            </div>

    </section>
</main>
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
                case 'حذف ':
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

<script>
    $(document).ready(function() {
        var table = $('#reservationTable').DataTable({
            // Your DataTable configuration
        });

        table.on('draw.dt', function() {
            var total = table.column(4, {
                search: 'applied'
            }).data().sum();
            $('#total-row td:last-child').text(total.toFixed(2));
        });
    });
</script>
@endsection