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
</style>@if(session('errorMesg'))
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
        <h1>تعديل بيانات المالك {{$ownerinfo->name}}</h1>
        <!-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Forms</li>
                <li class="breadcrumb-item active">Elements</li>
            </ol>
        </nav> -->
    </div>
    <section class="section" style="direction: rtl;">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">بينات المالك</h5>
                        <form action="{{ route('editeownerData') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{$ownerinfo->id}}">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">الاسم</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{$ownerinfo->name}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">رقم التليفون</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control" value="{{$ownerinfo->phone}}">
                                </div>
                            </div>
                            <div class="row mb-3">
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