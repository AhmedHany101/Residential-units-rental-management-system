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
        <h1>ادخال بيانات وحدة جديدة</h1>
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
                <form action="{{ route('save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">بينات المالك</h5>
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0"> حالة المالك</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="owner_type" id="owner_type_new" value="new" {{ old('owner_type') == 'new' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridRadios1">
                                            جديد
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="owner_type" id="owner_type_exsit" value="exsit" {{ old('owner_type') == 'exsit' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridRadios2">
                                            موجود مسبقا
                                        </label>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row mb-3" id="owner_type_exsit_form" style="display: none;">
                                <label for="apartmentNO" class="form-label">الاسم</label>
                                <select class="form-select" id="owner_id" name="owner_id">
                                    <option value=""></option>
                                    @foreach($owner_info as $owner)
                                    <option value="{{$owner->id}}">{{$owner->name}}</option>
                                    @endforeach
                                </select>
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
                            </div>
                            <div id="owner_type_new_form" style="display: none;">
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 col-form-label">الاسم</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">رقم التليفون</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                    </div>
                                </div>
                            </div>
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">فئة الوحدة</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="apartment_type" id="gridRadios1" value="private" {{ old('apartment_type') == 'private' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridRadios1">
                                            خاص
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="apartment_type" id="gridRadios2" value="admin" {{ old('apartment_type') == 'admin' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridRadios2">
                                            اداري
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <div id="privateFields" style="display: none;">
                                <h5 class="card-title">بيانات الوحدة <span style="color: red;">(خاصة)</span></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">رقم الوحدة</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="apartment_noP" value="{{ old('apartment_noP') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">الطابق</label>
                                        <input type="text" class="form-control" id="validationDefault02" name="floor_no_P" value="{{ old('floor_no_P') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">المبني</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="building_no_P" value="{{ old('building_no_P') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">عدد الغرف</label>
                                        <input type="number" class="form-control" id="validationDefault02" name="room_no_P" value="{{ old('room_no_P') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">السعر في الليلة</label>
                                        <input type="number" class="form-control" id="validationDefault01" name="price_per_day_P" value="{{ old('price_per_day_P') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">الاجمالي في الموسم</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="0" name="total_season_P" value="{{ old('total_season_P') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">مدفوع للمالك</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="0" name="payed_P" value="{{ old('payed_P') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">المتبقي للمالك</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="0" name="reamaning_P" value="{{ old('reamaning_P') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">اضافة صور الوحدة</label>
                                        <input type="file" class="form-control" name="images[]" multiple onchange="previewImages(this)">
                                    </div>
                                </div>
                                <div class="row" id="image-preview">
                                </div>
                                <div class="input-group mt-3 mb-3">
                                    <span class="input-group-text">Note</span>
                                    <textarea class="form-control" name="note" aria-label="With textarea" cols="20" rows="5" value="{{ old('note') }}"></textarea>
                                </div>
                            </div>
                            <div id="adminFields" style="display: none;">
                                <h5 class="card-title"> بيانات الوحدة <span style="color: red;">(اداري)</span></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">رقم الوحدة</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="apartment_no_A">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">الطابق</label>
                                        <input type="text" class="form-control" id="validationDefault02" name="floor_no">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">المبني</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="building_no">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">عدد الغرف</label>
                                        <input type="number" class="form-control" id="validationDefault02" name="room_no">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">السعر ف الليلة</label>
                                        <input type="number" class="form-control" id="validationDefault01" value="0" name="price_per_day">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">النسبة</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="0" name="percentage" placeholder="%">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">اضافة صور الوحدة</label>
                                        <input type="file" class="form-control" name="admin_images[]" multiple onchange="previewImages2(this)">
                                    </div>
                                </div>
                                <div class="row" id="image-preview2">
                                </div>
                                <div class="input-group mt-3 mb-3">
                                    <span class="input-group-text">ملاحظة</span>
                                    <textarea class="form-control" name="note" aria-label="With textarea" cols="20" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
</main>
<script>
    var privateFields = document.getElementById('privateFields');
    var adminFields = document.getElementById('adminFields');
    var gridRadios1 = document.getElementById('gridRadios1');
    var gridRadios2 = document.getElementById('gridRadios2');

    gridRadios1.addEventListener('change', function() {
        privateFields.style.display = 'block';
        adminFields.style.display = 'none';
    });

    gridRadios2.addEventListener('change', function() {
        privateFields.style.display = 'none';
        adminFields.style.display = 'block';
    });
</script>
<script>
    var owner_type_new_form = document.getElementById('owner_type_new_form');
    var owner_type_exsit_form = document.getElementById('owner_type_exsit_form');
    var owner_type_exsit = document.getElementById('owner_type_exsit');
    var owner_type_new = document.getElementById('owner_type_new');

    owner_type_exsit.addEventListener('change', function() {
        owner_type_exsit_form.style.display = 'block';
        owner_type_new_form.style.display = 'none';
    });

    owner_type_new.addEventListener('change', function() {
        owner_type_new_form.style.display = 'block';
        owner_type_exsit_form.style.display = 'none';
    });

    function previewImages(input) {
        var previewContainer = document.getElementById("image-preview");
        previewContainer.innerHTML = "";

        if (input.files) {
            var filesArray = Array.from(input.files);

            filesArray.forEach(function(file) {
                var imgElement = document.createElement("img");
                imgElement.src = URL.createObjectURL(file);
                imgElement.alt = file.name;
                imgElement.classList.add("img-thumbnail", "m-2");
                imgElement.style.maxWidth = "100px";

                previewContainer.appendChild(imgElement);
            });
        }
    }

    function previewImages2(input) {
        var previewContainer = document.getElementById("image-preview2");
        previewContainer.innerHTML = "";

        if (input.files) {
            var filesArray = Array.from(input.files);

            filesArray.forEach(function(file) {
                var imgElement = document.createElement("img");
                imgElement.src = URL.createObjectURL(file);
                imgElement.alt = file.name;
                imgElement.classList.add("img-thumbnail", "m-2");
                imgElement.style.maxWidth = "100px";

                previewContainer.appendChild(imgElement);
            });
        }
    }
</script>
@endsection