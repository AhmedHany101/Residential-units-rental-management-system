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
        <h1>اضافة حجز جديد</h1>
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
                        <form action="{{ route('add/resrvation/expenses') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$resvation->id}}">
                            <div class="row" id="officeExpenseInput">
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label">بند المصروف </label>
                                    <textarea type="text" class="form-control" name="note" placeholder="أدخل بند المصروف المكتبي" required></textarea>
                                </div>
                                <div class="col-md-8">
                                    <label for="officeExpense" class="form-label"> القيمة </label>
                                    <input type="number" class="form-control" name="cost" value="0" required>
                                </div>
                            </div>
                            <div class="col mb-4">
                                <label for="cashPayed" class="form-label">اضافة اسم الموظف</label>
                                <div class="col-sm-10">
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
@endsection