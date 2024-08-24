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

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        width: 100% !important;
        box-sizing: border-box;
    }
</style>



<main id="main" class="main">
    <div class="pagetitle" style="direction: rtl;">
        <h1>تعديل حجز {{$reservation_data->id}}</h1>
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
                        <div class="row">
                            <div class="col-md-8">
                                <form action="{{ route('edite/data/reservation') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$reservation_data->id}}">
                                    <label for="apartmentNO" class="form-label">اختار الوحدة</label>
                                    <select class="form-select" id="apartmentNO" name="apartment_id" required>
                                        <option value="{{$reservation_apartment->id}}" style="color: red !important;text-align: right !important;">(مبنى رقم {{$reservation_apartment->buliding_no}} , الطابق {{$reservation_apartment->floor_no}}, وحدة رقم {{$reservation_apartment->apartness_no}}) الوحدة المسجلة في الحجز </option>
                                        @foreach($apartness as $apartment)
                                        <option value="{{$apartment->id}}" data-price="{{$apartment->cost_per_night}}">رقم المبني {{$apartment->buliding_no}} , طابق رقم {{$apartment->floor_no}}, وحدة رقم {{$apartment->apartness_no}}</option>
                                        @endforeach
                                    </select>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
                            </div>
                            <div class="col-md-4">
                                <label for="pricePerNight" class="form-label">السعر في الليلة</label>
                                <input type="number" class="form-control" id="pricePerNight" name="cost_per_night" value="{{$reservation_data->price_per_night}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="validationDefault02" class="form-label">عداد الايام المحجوزة</label>
                                <input type="number" class="form-control" id="days_no" value="{{$reservation_data->days_no}}" name="days_no">
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault02" class="form-label">تاريخ البداية</label>
                                <input type="datetime-local" class="form-control" id="validationDefault02" value="{{$reservation_data->start_date}}" name="start_date">
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault02" class="form-label">تاريخ النهاية</label>
                                <input type="datetime-local" class="form-control" id="validationDefault02" value="{{$reservation_data->end_date}}" name="end_date" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="total" class="form-label">الاجمالي</label>
                                <input type="number" class="form-control" id="total" value="{{$resevation_payment->total}}" name="total" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="cashPayed" class="form-label">المبلغ المدفوع</label>
                                <input type="number" class="form-control" id="cashPayed" value="{{$resevation_payment->payed}}" name="payed">
                            </div>

                            <div class="col-md-4">
                                <label for="remaining" class="form-label">المبلغ المتبقي</label>
                                <input type="number" class="form-control" id="remaining" value="{{$resevation_payment->remaining}}" name="remaining" readonly>
                            </div>
                            <div class="input-group mt-3 mb-3">
                                <span class="input-group-text">اضف ملاحظة</span>
                                <textarea class="form-control" name="note" aria-label="With textarea" cols="20" rows="5">{{$reservation_data->notes}} </textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label for="cashPayed" class="form-label">طريقة الدفع</label>

                                <div class="col-sm-10">
                                    <select class="form-select" name="payment_method" aria-label="Default select example">
                                        <option value="{{$resevation_payment->payment_method}}" style="color:red !important;">
                                            تم الدفع عن طريق ( @if($resevation_payment->payment_method == 1)
                                            نقدي
                                            @elseif($resevation_payment->payment_method == 2)
                                            بنكي
                                            @elseif($resevation_payment->payment_method == 3)
                                            محفظة إلكترونية
                                            @endif
                                            )
                                        </option>
                                        <option value="1">نقدي</option>
                                        <option value="2">بنكي</option>
                                        <option value="3">محفظة إلكترونية</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endsection