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
                        <h5 class="card-title">معلومات العميل</h5>
                        <form action="{{ route('save/reservation') }}" method="POST" enctype="multipart/form-data" id="addreservation">
                            @csrf
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0"> حدد حالة العميل : </legend>
                                <div class="col-sm-10 d-flex flex-row-reverse align-items-center" style="direction: ltr;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_status" id="exeite_customer" value="exeite_customer" {{ old('customer_status') == 'exeite_customer' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exeite_customer"> موجود مسبقا</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_status" id="new_customer" value="new_customer" {{ old('customer_status') == 'new_customer' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="new_customer" style="margin-right: 10px;">عميل جديد</label>
                                    </div>
                                </div>
                            </fieldset>
                            <div id="newCus" style="display: none;">
                                <div class="row">
                                    <!-- Customer name required -->
                                    <div class="col-md-6">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">الاسم <span style="color: red;">*</span> </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <!-- Customer phone1 required -->
                                    <div class="col-md-6">
                                        <label for="inputText" class="col-sm-2 col-form-label">رقم التليفون 1 <span style="color: red;">*</span> </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phone1" value="{{ old('phone1') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Customer phone2 -->
                                    <div class="col-md-6">
                                        <label for="inputText" class="col-sm-2 col-form-label">رقم التليفون 2</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phone2" value="{{ old('phone2') }}" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Customer phone3 -->
                                    <div class="col-md-6">
                                        <label for="inputText" class="col-sm-2 col-form-label">الرقم القومي <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="national_id" value="{{ old('national_id') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">جنسية العميل : <span style="color: red;">*</span> </legend>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="gridRadios1" value="0" {{ old('customer_type') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridRadios1">مصري الجنسية</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="gridRadios2" value="1" {{ old('customer_type') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridRadios2" style="margin-right: 10px;">اجنبي الجنسية</label>
                                    </div>
                                </fieldset>
                                <div id="egyptian" style="display: none;">
                                    <h5 class="card-title">صور الهويات الشخصية</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">صورة البطاقة الشخصية (الوجة) <span style="color: red;">*</span></label>
                                            <input type="file" class="form-control" name="id_face[]" onchange="previewImage(event)">
                                            <div class="form-group">
                                                <img class="imagePreview" src="#" alt="Preview" style="max-width: 100px; display: none; max-height: 80px;">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">صورة البطاقة الشخصية (الخلف) <span style="color: red;">*</span></label>
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
                                <div id="foreign" style="display: none;">
                                    <h5 class="card-title">صور الهويات الشخصية</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">جواز السفر <span style="color: red;">*</span></label>
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
                            </div>
                            <div id="exitCus" style="display: none;">
                                <div class="col-md-8">
                                    <label for="apartmentNO" class="form-label">اختار العميل <span style="color: red;">*</span></label>
                                    <select class="form-select" id="customer_type_id" name="customer_id">
                                        <option value=""></option>
                                        @foreach($customer_data as $customer)
                                        <option value=" {{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
                                </div>
                            </div>
                            <h5 class="card-title">معلومات الحجز</h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="apartmentNO" class="form-label">اختار الوحدة <span style="color: red;">*</span></label>
                                    <select class="form-select" id="apartmentNO" name="apartment_id" required>
                                        <option value=""></option>
                                        @foreach($apartness as $apartment)
                                        <option value="{{$apartment->id}}" data-price="{{$apartment->cost_per_night}}">رقم المبني {{$apartment->buliding_no}} , طابق رقم {{$apartment->floor_no}}, وحدة رقم {{$apartment->apartness_no}}</option>
                                        @endforeach
                                    </select>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
                                </div>
                                <div class="col-md-4">
                                    <label for="pricePerNight" class="form-label">السعر في الليلة <span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="pricePerNight" name="cost_per_night">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="validationDefault02" class="form-label">عداد الايام المحجوزة <span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="days_no" value="1" name="days_no">
                                </div>
                                <div class="col-md-4">
                                    <label for="validationDefault02" class="form-label">تاريخ البداية</label>
                                    <input type="datetime-local" class="form-control" id="validationDefault02" value="0" name="start_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="validationDefault02" class="form-label">تاريخ النهاية <span style="color: red;">*</span></label>
                                    <input type="datetime-local" class="form-control" id="validationDefault02" value="0" name="end_date" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="total" class="form-label">الاجمالي</label>
                                    <input type="number" class="form-control" id="total" value="0" name="total" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="cashPayed" class="form-label">المبلغ المدفوع</label>
                                    <input type="number" class="form-control" id="cashPayed" value="0" name="payed">
                                </div>

                                <div class="col-md-4">
                                    <label for="remaining" class="form-label">المبلغ المتبقي</label>
                                    <input type="number" class="form-control" id="remaining" value="0" name="remaining" readonly>
                                </div>
                                <div class="input-group mt-3 mb-3">
                                    <span class="input-group-text">اضف ملاحظة</span>
                                    <textarea class="form-control" name="note" aria-label="With textarea" cols="20" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="cashPayed" class="form-label">طريقة الدفع</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="payment_method" aria-label="Default select example">
                                            <option value="1">نقدي</option>
                                            <option value="2">بنكي</option>
                                            <option value="3">محفظة إلكترونية</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col mb-4">
                                    <label for="cashPayed" class="form-label">اضف تقيم للعميل</label>

                                    <div class="col-sm-10">
                                        <select class="form-select" aria-label="Default select example" name="customer_rate">
                                            <option value="0">غير معرف</option>
                                            <option value="1">سيء</option>
                                            <option value="2">جيد</option>
                                            <option value="3">VIP</option>
                                        </select>
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
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-10">
                                    <button type="button" class="btn btn-primary" id="save-button">حفظ</button>
                                    <input type="submit" style="display:none;" />
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
    var exeite_customer = document.getElementById('exeite_customer');
    var new_customer = document.getElementById('new_customer');
    var newCus = document.getElementById('newCus');
    var exitCus = document.getElementById('exitCus');

    exeite_customer.addEventListener('change', function() {
        exitCus.style.display = 'block';
        newCus.style.display = 'none';
    });

    new_customer.addEventListener('change', function() {
        exitCus.style.display = 'none';
        newCus.style.display = 'block';
    });
</script>
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
        $('#customer_type_id').select2({
            width: '100%' // Set the width of the select element

        });
    });
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Assuming you're using jQuery
    $('#save-button').click(function() {
        // Disable the button
        $(this).attr('disabled', true);

        // Submit the form
        $('#addreservation').submit();
    });
</script>
@endsection