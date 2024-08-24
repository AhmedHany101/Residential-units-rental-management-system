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
                        <h5 class="card-title">معلومات العميل</h5>
                        <form action="{{ route('edite/customer/data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="custome_id" value="{{$customer_data->id}}">
                            <div class="row">
                                <!-- Customer name required -->
                                <div class="col-md-6">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">الاسم <span style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" value="{{$customer_data->name}}" required>
                                    </div>
                                </div>
                                <!-- Customer phone1 required -->
                                <div class="col-md-6">
                                    <label for="inputText" class="col-sm-2 col-form-label">رقم التليفون 1</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="phone1" value="{{$customer_data->phone_one}}" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Customer phone2 -->
                                <div class="col-md-6">
                                    <label for="inputText" class="col-sm-2 col-form-label">رقم التليفون 2</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="phone2" value="{{$customer_data->phone_two}}" class="form-control">
                                    </div>
                                </div>
                                <!-- Customer phone3 -->
                                <div class="col-md-6">
                                    <label for="inputText" class="col-sm-2 col-form-label">الرقم القومي</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="national_id" value="{{$customer_data->national_id}}" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            @if($customer_data->customer_type == 0)
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">جنسية العميل : </legend>
                                <div class="col-sm-10 d-flex flex-row-reverse align-items-center" style="direction: ltr;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="gridRadios1" value="0" checked>
                                        <label class="form-check-label" for="gridRadios1">مصري الجنسية</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="gridRadios2" value="1" disabled>
                                        <label class="form-check-label" for="gridRadios2" style="margin-right: 10px;">اجنبي الجنسية</label>
                                    </div>
                                </div>
                            </fieldset>
                            @elseif($customer_data->customer_type == 1)
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">جنسية العميل : </legend>
                                <div class="col-sm-10 d-flex flex-row-reverse align-items-center" style="direction: ltr;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="gridRadios1" value="0" disabled>
                                        <label class="form-check-label" for="gridRadios1">مصري الجنسية</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="gridRadios2" value="1" checked>
                                        <label class="form-check-label" for="gridRadios2" style="margin-right: 10px;">اجنبي الجنسية</label>
                                    </div>
                                </div>
                            </fieldset>
                            @endif
                            <div class="col mb-4">
                                <label for="cashPayed" class="form-label">اضف تقيم للعميل</label>

                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Default select example" name="customer_rate">
                                        <option value="{{$customer_data->customer_rate}}">
                                            @if($customer_data->customer_rate == 0)
                                            غير معرف
                                            @elseif($customer_data->customer_rate == 1)
                                            سيء
                                            @elseif($customer_data->customer_rate == 2)
                                            جيد
                                            @elseif($customer_data->customer_rate == 0)
                                            VIP
                                            @endif
                                        </option>

                                        <option value="1">سيء</option>
                                        <option value="2">جيد</option>
                                        <option value="3">VIP</option>
                                    </select>
                                </div>
                            </div>
                            <h5 class="card-title">صور الهويات الشخصية</h5>

                            @foreach($reservation_paper as $item)
                            <div class="row">
                                @if($item->id_face != '')
                                <div class="col-lg-2">
                                    <img src="{{ asset($item->id_face) }}" alt="Test Image" width="100px" height="100px" onclick="openImage(this)" data-bs-toggle="tooltip" data-bs-placement="top" title="صور وجة بطاقة شخصية">
                                    <a href="/admin/delete/image/{{$item->id}}/idface" class="btn btn-danger mt-2" style="align-items: center;">x</a>
                                </div>
                                @endif
                                @if($item->id_back != '')
                                <div class="col-lg-2">
                                    <img src="{{ asset($item->id_back) }}" alt="Test Image" width="100px" height="100px" onclick="openImage(this)"  data-bs-toggle="tooltip" data-bs-placement="top" title="صور ظهر بطاقة شخصية">
                                    <a href="/admin/delete/image/{{$item->id}}/idback" class="btn btn-danger mt-2" style="align-items: center;">x</a>
                                </div>
                                @endif
                                @if($item->marriage_certificate != '')
                                <div class="col-lg-2">
                                    <img src="{{ asset($item->marriage_certificate) }}" alt="Test Image" width="100px" height="100px" onclick="openImage(this)"  data-bs-toggle="tooltip" data-bs-placement="top" title="صور قسيمة زوادج">
                                    <a href="/admin/delete/image/{{$item->id}}/marriage" class="btn btn-danger mt-2" style="align-items: center;">x</a>

                                </div>
                                @endif
                                @if($item->passport != '')
                                <div class="col-lg-2">
                                    <img src="{{ asset($item->passport) }}" alt="Test Image" width="100px" height="100px" onclick="openImage(this)"  data-bs-toggle="tooltip" data-bs-placement="top" title="صور باسبور شخصية">
                                    <a href="/admin/delete/image/{{$item->id}}/passport" class="btn btn-danger mt-2" style="align-items: center;">x</a>

                                </div>
                                @endif
                            </div>
                            <br>
                            @endforeach
                            @if($customer_data->customer_type == 0)
                            <div id="egyptian">
                                <h5 class="card-title">اضافة صور جديدة</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">صورة البطاقة الشخصية (الوجة)</label>
                                        <input type="file" class="form-control" name="id_face[]" onchange="previewImage(event)">
                                        <div class="form-group">
                                            <img class="imagePreview" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">صورة البطاقة الشخصية (الخلف)</label>
                                        <input type="file" class="form-control" name="id_back[]" onchange="previewImage2(event)">
                                        <div class="form-group">
                                            <img class="imagePreview2" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">قسيمة الزواج</label>
                                        <input type="file" class="form-control" name="marriage_certificate[]" onchange="previewImage3(event)">
                                        <div class="form-group">
                                            <img class="imagePreview3" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success mt-4" onclick="addImageUpload()">Add</button>
                                    </div>
                                </div>

                                <div id="imageUploadContainer">
                                    <!-- Dynamically added image upload fields will be inserted here -->
                                </div>

                            </div>
                            @elseif($customer_data->customer_type == 1)
                            <div id="foreign">
                                <h5 class="card-title">صور الهويات الشخصية</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">جواز السفر</label>
                                        <input type="file" class="form-control" name="passport[]" onchange="previewImage4(event)">
                                        <div class="form-group">
                                            <img class="imagePreview4" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">قسيمة الزواج</label>
                                        <input type="file" class="form-control" name="marriageF[]" onchange="previewImage5(event)">
                                        <div class="form-group">
                                            <img class="imagePreview5" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success mt-4" onclick="addImageUpload2()">Add</button>
                                    </div>
                                </div>
                                <div id="imageUploadContainer2">
                                    <!-- Dynamically added image upload fields will be inserted here -->
                                </div>
                            </div>
                          
                            @endif
                            <div class="row mb-3 mt-5">
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
    var egyptian = document.getElementById('egyptian');
    var foreign = document.getElementById('foreign');
    var gridRadios1 = document.getElementById('gridRadios1');
    var gridRadios2 = document.getElementById('gridRadios2');

    gridRadios1.addEventListener('change', function() {
        egyptian.style.display = 'block';
        foreign.style.display = 'none';
    });

    gridRadios2.addEventListener('change', function() {
        egyptian.style.display = 'none';
        foreign.style.display = 'block';
    });
</script>
<script>
    function previewImage(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = input.parentElement.querySelector('.imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewImage2(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = input.parentElement.querySelector('.imagePreview2');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewImage3(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = input.parentElement.querySelector('.imagePreview3');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addImageUpload() {
        var container = document.getElementById('imageUploadContainer');
        var newIndex = container.children.length + 1;

        var newImageUpload = document.createElement('div');
        newImageUpload.className = 'row';
        newImageUpload.innerHTML = `
        <div class="col-md-3">
            <label class="form-label">صورة البطاقة الشخصية (الوجة)</label>
            <input type="file" class="form-control" name="id_face[]" onchange="previewImage(event)">
            <div class="form-group">
                <img class="imagePreview" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
            </div>
        </div>
        <div class="col-md-3">
        <label class="form-label">صورة البطاقة الشخصية (الخلف)</label>
            <input type="file" class="form-control" name="id_back[]" onchange="previewImage2(event)">
            <div class="form-group">
                <img class="imagePreview2" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
            </div>
        </div>
        <div class="col-md-3">
        <label class="form-label">قسيمة الزواج</label>
            <input type="file" class="form-control" name="marriage_certificate[]" onchange="previewImage3(event)">
            <div class="form-group">
                <img class="imagePreview3" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
            </div>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger mt-4" onclick="removeImageUpload(this)">Remove</button>
        </div>
    `;

        container.appendChild(newImageUpload);
    }

    function removeImageUpload(button) {
        var container = document.getElementById('imageUploadContainer');
        var row = button.parentNode.parentNode;
        container.removeChild(row);
    }
</script>
<script>
    function previewImage4(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = input.parentElement.querySelector('.imagePreview4');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewImage5(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = input.parentElement.querySelector('.imagePreview5');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addImageUpload2() {
        var container = document.getElementById('imageUploadContainer2');
        var newIndex = container.children.length + 1;

        var newImageUpload = document.createElement('div');
        newImageUpload.className = 'row';
        newImageUpload.innerHTML = `
      <div class="col-md-4">
      <label class="form-label">جواز السفر</label>
        <input type="file" class="form-control" name="passport[]" onchange="previewImage4(event)">
        <div class="form-group">
          <img class="imagePreview4" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
        </div>
      </div>
      <div class="col-md-4">
      <label class="form-label">قسيمة الزواج</label>
        <input type="file" class="form-control" name="marriageF[]" onchange="previewImage5(event)">
        <div class="form-group">
          <img class="imagePreview5" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
        </div>
      </div>
           <div class="col-md-3">
        <button type="button" class="btn btn-danger mt-4" onclick="removeImageUpload2(this)">Remove</button>
      </div>
    `;

        container.appendChild(newImageUpload);
    }

    function removeImageUpload2(button) {
        var container = document.getElementById('imageUploadContainer2');
        var row = button.parentNode.parentNode;
        container.removeChild(row);
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var apartmentSelect = document.getElementById('validationDefault04');
        var pricePerNightInput = document.getElementById('pricePerNight');

        apartmentSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var pricePerNight = selectedOption.getAttribute('data-price');
            pricePerNightInput.value = pricePerNight;
            console.log(pricePerNight);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#apartmentNO').select2({
            width: '100%' // Set the width of the select element
        });

        $('#apartmentNO').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var pricePerNight = selectedOption.data('price');
            $('#pricePerNight').val(pricePerNight);
        });
    });
</script>
<!--
 * The script uses jQuery to calculate the total cost and remaining balance based on selected options
 * and user input in a booking form.
-->
<script>
    $(document).ready(function() {
        $('#apartmentNO').select2({
            width: '100%'
        });

        $('#apartmentNO').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var pricePerNight = selectedOption.data('price');
            $('#pricePerNight').val(pricePerNight);
            calculateTotal();
            calculateRemaining();
        });

        $('#cashPayed').on('input', function() {
            calculateRemaining();
        });

        $('#pricePerNight').on('input', function() {
            calculateTotal();

            calculateRemaining();
        });

        $('#days_no').on('input', function() {
            calculateTotal();
            calculateRemaining();

        });

        function calculateTotal() {
            var pricePerNight = $('#pricePerNight').val();
            var dayNumber = $('#days_no').val();
            var total = pricePerNight * dayNumber;
            $('#total').val(total);
        }

        function calculateRemaining() {
            var total = $('#total').val();
            var cashPayed = $('#cashPayed').val();
            var remaining = total - cashPayed;
            $('#remaining').val(remaining);
        }
    });
</script>
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
<div id="image-modal">
    <span class="close-btn" onclick="closeImageModal()">&times;</span>
    <img id="modal-image" src="" alt="Modal Image">
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@endsection