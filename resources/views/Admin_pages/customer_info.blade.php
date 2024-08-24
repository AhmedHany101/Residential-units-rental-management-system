@extends('layout.layout')
@section('layout')
<style>
    p {
        font-size: large;
        font-weight: bold;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    h2 {
        font-size: larger !important;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important;
        font-weight: bolder !important;
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
<style>
    .alerterrr {
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

    .alertr {
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
    .closebtna {
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
<main id="main" class="main">
    @php
    $customerId=Crypt::encryptString($customer_info->id);
    @endphp
    <section class="section" style="direction: rtl;">
        <div class="row align-items-top">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <h2 class="card-title" style="text-decoration: underline;">بيانات العميل:</h2>
                            </div>
                            <div class="col-lg-2 m-2">
                                <a class="btn btn-primary" href="/admin/edite/{{$customerId}}/information" onclick="return confirmAction('تعديل')">تعديل بيانات العميل</a>
                            </div>

                        </div>
                        <p style="color: black;">الاسم : {{$customer_info->name}}</p>
                        <p style="color: black;">رقم التليفون 1 : {{$customer_info->phone_one}}</p>
                        @if($customer_info->phone_two != '')
                        <p style="color: black;">رقم التليفون 2 : {{$customer_info->phone_two}}</p>
                        @endif
                        @if($customer_info->phone_three != '')
                        <p style="color: black;">رقم التليفون 3 : {{$customer_info->phone_three}}</p>
                        @endif
                        <p style="color: black;"> جنسية العميل: @if($customer_info->customer_type == 0) مصري الجنسية @else اجنبي الجنسية @endif</p>
                        <p style="color: black;"> تقيم العميل: @if($customer_info->customer_rate == 0) غير معرف @elseif($customer_info->customer_rate == 1) سيء @elseif($customer_info->customer_rate == 2) جيد @elseif($customer_info->customer_rate == 3) VIP @endif</p>
                        @foreach($reservation_paper as $item)
                        <div class="row">
                            @if($item->id_face != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->id_face) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                            @if($item->id_back != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->id_back) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                            @if($item->marriage_certificate != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->marriage_certificate) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                            @if($item->passport != '')
                            <div class="col-lg-2">
                                <img src="{{ asset($item->passport) }}" alt="Test Image" width="50px" height="50px" onclick="openImage(this)">
                            </div>
                            @endif
                        </div>
                        <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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
                case 'حذف الحجز':
                    // Logic for deleting the reservation
                    break;
                default:
                    break;
            }
            return true; // Allow the default action to proceed
        }
        return false; // Prevent the default action
    }
</script>
<div id="image-modal">
    <span class="close-btn" onclick="closeImageModal()">&times;</span>
    <img id="modal-image" src="" alt="Modal Image">
</div>
@endsection