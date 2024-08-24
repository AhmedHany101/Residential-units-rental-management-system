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

    #image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        overflow: auto;
        text-align: center;
    }

    #modal-image {
        display: block;
        max-width: 80%;
        max-height: 80%;
        margin: 10% auto;
    }

    .close-btn {
        color: red;
        font-size: 34px;
        font-weight: bold;
        position: fixed;
        top: 10px;
        right: 60px;
        cursor: pointer;
        z-index: 9999;
    }

    @media (max-width: 768px) {
        #modal-image {
            max-width: 100%;
            max-height: 80vh;
        }

        .close-btn {
            top: 20px;
            right: 20px;
        }
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
        <h1>تعديل بيانات الوحدة {{$apartment_data->apartness_no}}</h1>
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
                        <form action="{{ route('editeApartness') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="apartmentID" value="{{$apartment_data->id}}">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">الاسم</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{$apartment_data->owner->name}}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">رقم التليفون</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control" value="{{$apartment_data->owner->phone}}" readonly>
                                </div>
                            </div>
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">فئة الوحدة</legend>
                                <div class="col-sm-10">
                                    @if($apartment_data->sys_type == 1)
                                    <div class="form-check">
                                        <input class="form-check-input check" type="radio" name="apartment_type" id="gridRadios1" value="private" checked>
                                        <label class="form-check-label" for="gridRadios1">
                                            خاص
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="apartment_type" id="gridRadios2" value="admin" disabled>
                                        <label class="form-check-label" for="gridRadios2">
                                            اداري
                                        </label>
                                    </div>
                                    @elseif($apartment_data->sys_type == 0)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="apartment_type" id="gridRadios1" value="private" disabled>
                                        <label class="form-check-label" for="gridRadios1">
                                            خاص
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check" type="radio" name="apartment_type" id="gridRadios2" value="admin" checked>
                                        <label class="form-check-label" for="gridRadios2">
                                            اداري
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </fieldset>
                            @if($apartment_data->sys_type == 1)
                            <div id="privateFields">
                                <h5 class="card-title">بيانات الوحدة</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">رقم الوحدة</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="apartment_noP" value="{{$apartment_data->apartness_no}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">الطابق</label>
                                        <input type="text" class="form-control" id="validationDefault02" name="floor_no_P" value="{{$apartment_data->floor_no}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">المبني</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="building_no_P" value="{{$apartment_data->buliding_no}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">عدد الغرف

                                        </label>
                                        <input type="number" class="form-control" id="validationDefault02" name="room_no_P" value="{{$apartment_data->room_no}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">السعر في الليلة</label>
                                        <input type="number" class="form-control" id="validationDefault01" name="price_per_day_P" value="{{$apartment_data->cost_per_night}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">الاجمالي في الموسم</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="{{ $apartness_private->total_account}}" name="total_season_P">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">مدفوع للمالك</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="{{ $apartness_private->payed}}" name="payed_P">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">المتبقي للمالك</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="{{ $apartness_private->remaining}}" name="reamaning_P">
                                    </div>
                                </div>
                                <div class="input-group mt-3 mb-3">
                                    <span class="input-group-text">ملاحظة</span>
                                    <textarea class="form-control" name="note" aria-label="With textarea" cols="20" rows="5">{{$apartment_data->not}}</textarea>
                                </div>
                                <div class="row m-2" id="image-preview">
                                <label for="inputText" class="col-sm-2 col-form-label">صور الوحدة</label>
                                    @foreach($apartment_images as $item)
                                    @php
                                    $image_id=Crypt::encryptString($item->id);
                                    @endphp
                                    <div class="col-lg-2">
                                        <img src="{{ asset($item->image) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                                        <br>
                                        <a href="/admin/delete/apartment/image/{{$image_id}}" style="text-decoration: underline;color:red;">حذف <i class="bi bi-trash3"></i> </a>
                                    </div>
                                    @endforeach

                                </div>
                                <div class="row m-2">
                                    <div class="col-md-4">
                                        <label for="validationDefault02" class="form-label">اضافة صور جديدة</label>
                                        <input type="file" class="form-control" name="images[]" multiple onchange="previewImages(this)">
                                    </div>
                                </div>
                                <div class="row" id="image-preview2">
                                </div>
                            </div>
                            @elseif($apartment_data->sys_type == 0)
                            <div id="adminFields">
                                <h5 class="card-title">بيانات الوحدة</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">رقم الوحدة</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="apartment_no_A" value="{{$apartment_data->apartness_no}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">الطابق</label>
                                        <input type="text" class="form-control" id="validationDefault02" name="floor_no" value="{{$apartment_data->floor_no}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">المبني</label>
                                        <input type="text" class="form-control" id="validationDefault01" name="building_no" value="{{$apartment_data->buliding_no}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">عدد الغرف</label>
                                        <input type="number" class="form-control" id="validationDefault02" name="room_no" value="{{$apartment_data->room_no}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationDefault01" class="form-label">السعر ف الليلة</label>
                                        <input type="number" class="form-control" id="validationDefault01" value="{{$apartment_data->cost_per_night}}" name="price_per_day">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationDefault02" class="form-label">النسبة</label>
                                        <input type="number" class="form-control" id="validationDefault02" value="{{$apartness_admin->percentage}}" name="percentage" placeholder="%">
                                    </div>
                                </div>
                                <div class="input-group mt-3 mb-3">
                                    <span class="input-group-text">ملاحظة</span>
                                    <textarea class="form-control" name="note" aria-label="With textarea" cols="20" rows="5">{{$apartment_data->not}}</textarea>
                                </div>
                            </div>
                            <div class="row m-2" id="image-preview">
                            <label for="inputText" class="col-sm-2 col-form-label">صور الوحدة</label>

                                @foreach($apartment_images as $item)
                                @php
                                $image_id=Crypt::encryptString($item->id);
                                @endphp
                                <div class="col-lg-2">
                                    <img src="{{ asset($item->image) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                                    <br>
                                    <a href="/admin/delete/apartment/image/{{$image_id}}" style="text-decoration: underline;color:red;">حذف <i class="bi bi-trash3"></i> </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="row m-2">
                                <div class="col-md-4">
                                    <label for="validationDefault02" class="form-label">اضافة صور جديدة</label>
                                    <input type="file" class="form-control" name="images[]" multiple onchange="previewImages(this)">
                                </div>
                            </div>
                            <div class="row" id="image-preview2">
                            </div>
                            @endif
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
<div id="image-modal">
    <span class="close-btn" onclick="closeImageModal()">&times;</span>
    <img id="modal-image" src="" alt="Modal Image">
</div>
<script>
    function openImage(img) {
        var modal = document.getElementById("image-modal");
        var modalImg = document.getElementById("modal-image");

        modal.style.display = "block";
        modalImg.src = img.src;
    }

    function closeImageModal() {
        var modal = document.getElementById("image-modal");
        modal.style.display = "none";
    }
</script>
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
    function previewImages(input) {
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