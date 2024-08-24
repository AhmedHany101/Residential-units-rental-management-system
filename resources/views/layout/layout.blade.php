<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>الامير</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('Style/assets/img/favicon.png')}}" rel="icon">
  <link href="{{asset('Style/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Vendor CSS Files -->
  <link href="{{asset('Style/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('Style/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('Style/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('Style/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('Style/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('Style/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('Style/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('Style/assets/css/style.css')}}" rel="stylesheet">


</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-left">

        <!-- End Search Icon-->


        <!-- End Notification Nav -->



        <!-- End Profile Nav -->

      </ul>
    </nav>
    <!-- End Icons Navigation -->
    <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn"></i>

      <a href="index.html" class="logo d-flex align-items-center">
        <img src="" alt="">
        <span class="d-none d-lg-block">الامير</span>
      </a>

    </div>
  </header>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar" style="direction: rtl;">
    @php
    $user=auth()->user();
    $adminRole = env('ROLE_AS_ADMIN');
    $normaluser=env('userType');
    $admintype=env('Admintype');
    @endphp
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{url('/admin/dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>لوحة التحكم</span>

        </a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/add/new/expenses')}}">
          <i class="bi bi-safe2-fill"></i> <span>اضافة مصروفات</span>
        </a>
      </li> -->

     

      @if($user->type ==$adminRole)
      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/add/new/apartment')}}">
          <i class="bi bi-house-fill"></i>
          <span>اضافة وحدة جديدة</span>
        </a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/Customers')}}">
          <i class="bi bi-people-fill"></i> <span>العملاء</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/owners')}}">
          <i class="bi bi-people"></i>
          <span>ملاك الوحدات السكنية</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/add/staff')}}">
          <i class="bi bi-people"></i>
          <span>اضافة مستخدم</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/apartness')}}">
          <i class="bi bi-houses-fill"></i>
          <span>الوحدات</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/add/new/reservation')}}">
          <i class="bi bi-calendar-plus-fill"></i>
          <span>اضافة حجز جديد</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/reservations/data')}}">
          <i class="bi bi-calendar3"></i>
          <span>الحجوزات الحالية</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/history/reservations')}}">
          <i class="bi bi-calendar-x-fill"></i> <span>الحجوزات المنتهة</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/expnses/report')}}">
          <i class="bi bi-safe2-fill"></i>
          <span>تقارير المصاريف</span>
        </a>
      </li>

      @elseif($user->type ==$admintype)
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/apartness')}}">
          <i class="bi bi-houses-fill"></i>
          <span>الوحدات</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/add/new/reservation')}}">
          <i class="bi bi-calendar-plus-fill"></i>
          <span>اضافة حجز جديد</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/reservations/data')}}">
          <i class="bi bi-calendar3"></i>
          <span>الحجوزات الحالية</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/history/reservations')}}">
          <i class="bi bi-calendar-x-fill"></i> <span>الحجوزات المنتهة</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/expnses/report')}}">
          <i class="bi bi-safe2-fill"></i>
          <span>تقارير المصاريف</span>
        </a>
      </li>
      @elseif($user->type ==$normaluser)
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/apartness')}}">
          <i class="bi bi-houses-fill"></i>
          <span>الوحدات</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/reservations/data')}}">
          <i class="bi bi-calendar3"></i>
          <span>الحجوزات الحالية</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/expnses/report')}}">
          <i class="bi bi-safe2-fill"></i>
          <span>تقارير المصاريف</span>
        </a>
      </li>
      @endif
      <!-- End Blank Page Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{url('/admin/logout')}}">
          <i class="bi bi-box-arrow-right"></i>
          <span> تسجيل خروج</span>
        </a>
      </li>
    </ul>

  </aside>
  <!-- End Sidebar-->
  @yield('layout')

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Alamir</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('Style/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/chart.js/chart.umd.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/quill/quill.min.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('Style/assets/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('Style/assets/js/main.js')}}"></script>

</body>

</html>
