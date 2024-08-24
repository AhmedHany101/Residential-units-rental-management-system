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
        <h1>اضافة مصروف</h1>
        <!-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">/الرئسية</a></li>
           
            </ol>
        </nav> -->
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="direction: rtl;">
                        <h5 class="card-title"> اضافة بيانات المصاريف</h5>
                        <form action="{{ route('save/epenses/data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col mb-3">
                                <div class="col-md-8">
                                    <label for="expenseType" class="form-label">نوع المصروف</label>
                                    <select class="form-select" id="expenseType" name="expense_type" required onchange="toggleExpenseInput()">
                                        <option value="">اختر نوع المصروف</option>
                                        <option value="office">مكتبي</option>
                                        <option value="labor">عمالة</option>
                                        <option value="private">وحدات خاصة</option>
                                        <option value="admin">وحدات إدارية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="officeExpenseInput" style="display: none;">
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label">بند المصروف المكتبي</label>
                                    <textarea type="text" class="form-control" name="note1" placeholder="أدخل بند المصروف المكتبي"></textarea>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label"> القيمة </label>
                                    <input type="number" class="form-control" name="cost1" value="0">
                                </div>
                            </div>

                            <div class="row" id="laborExpenseInput" style="display: none;">
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label">بند مصروف العمالة</label>
                                    <textarea type="text" class="form-control" name="note2" placeholder="أدخل بند مصروف العمالة"></textarea>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label"> القيمة </label>
                                    <input type="number" class="form-control" name="cost2" value="0">
                                </div>
                            </div>

                            <div class="row" id="privateExpenseInput" style="display: none;">
                                <div class="col-md-8">
                                    <label for="apartmentNO" class="form-label">اختار الوحدة</label>
                                    <select class="form-select" id="apartmentNO" name="Private_apartment_id">
                                        <option value=""></option>
                                        @foreach($privat_apartness as $apartment)
                                        <option value="{{$apartment->id}}" data-price="{{$apartment->cost_per_night}}">رقم المبني {{$apartment->buliding_no}} , طابق رقم {{$apartment->floor_no}}, وحدة رقم {{$apartment->apartness_no}}</option>
                                        @endforeach
                                    </select>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label">بند المصروف </label>
                                    <textarea type="text" class="form-control" name="note3" placeholder="أدخل بند مصروف العمالة"></textarea>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label"> القيمة </label>
                                    <input type="number" class="form-control" name="cost3" value="0">
                                </div>
                            </div>

                            <div class="row" id="adminExpenseInput" style="display: none;">
                                <div class="col-md-8">
                                    <label for="apartmentNO" class="form-label">اختار الوحدة</label>
                                    <select class="form-select" id="apartmentNO" name="Admin_apartment_id">
                                        <option value=""></option>
                                        @foreach($admin_apartness as $apartment)
                                        <option value="{{$apartment->id}}" data-price="{{$apartment->cost_per_night}}">رقم المبني {{$apartment->buliding_no}} , طابق رقم {{$apartment->floor_no}}, وحدة رقم {{$apartment->apartness_no}}</option>
                                        @endforeach
                                    </select>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label">بند المصروف </label>
                                    <textarea type="text" class="form-control" name="note4" placeholder="أدخل بند مصروف العمالة"></textarea>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label"> القيمة </label>
                                    <input type="number" class="form-control" name="cost4" value="0">
                                </div>
                            </div>
                            <div class="col mb-3">
                                <label for="cashPayed" class="form-label">اضافة اسم الموظف</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="staff_name" aria-label="Default select example">
                                        @foreach($users_name as $name)
                                        <option value="{{$name->name}}">{{$name->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 mt-2">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function toggleExpenseInput() {
        var expenseType = document.getElementById("expenseType").value;
        var officeExpenseInput = document.getElementById("officeExpenseInput");
        var laborExpenseInput = document.getElementById("laborExpenseInput");
        var privateExpenseInput = document.getElementById("privateExpenseInput");
        var adminExpenseInput = document.getElementById("adminExpenseInput");

        if (expenseType === "office") {
            officeExpenseInput.style.display = "block";
            laborExpenseInput.style.display = "none";
            privateExpenseInput.style.display = "none";
            adminExpenseInput.style.display = "none";
        } else if (expenseType === "labor") {
            officeExpenseInput.style.display = "none";
            laborExpenseInput.style.display = "block";
            privateExpenseInput.style.display = "none";
            adminExpenseInput.style.display = "none";
        } else if (expenseType === "private") {
            officeExpenseInput.style.display = "none";
            laborExpenseInput.style.display = "none";
            privateExpenseInput.style.display = "block";
            adminExpenseInput.style.display = "none";
        } else if (expenseType === "admin") {
            officeExpenseInput.style.display = "none";
            laborExpenseInput.style.display = "none";
            privateExpenseInput.style.display = "none";
            adminExpenseInput.style.display = "block";
        } else {
            officeExpenseInput.style.display = "none";
            laborExpenseInput.style.display = "none";
            privateExpenseInput.style.display = "none";
            adminExpenseInput.style.display = "none";
        }
    }
</script>
@endsection