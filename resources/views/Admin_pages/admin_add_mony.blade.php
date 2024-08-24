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
<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title" style="direction: rtl;">اضافة مستخدم جديد</h5>
              <!-- Vertical Form -->
              <form class="row g-3"  method="post" action="{{url('/admin/send/mony')}}">
                @csrf
                <div class="col-12" style="direction: rtl;">
                  <label for="inputNanme4" class="form-label">قيمة الايداع </label>
                  <input type="number" class="form-control" name="amount" id="inputNanme4" value="0" required>
                </div>
                <div class="col-12" style="direction: rtl;">
                  <label for="inputEmail4" class="form-label">البند</label>
                  <input type="text" class="form-control" name="note" id="inputEmail4" value="ايداع" required>
                </div>
               
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>
@endsection