<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-90680653-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-90680653-2');
    </script>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
    <!-- Twitter -->
    <!-- <meta name="twitter:site" content="@bootstrapdash">
    <meta name="twitter:creator" content="@bootstrapdash">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Azia">
    <meta name="twitter:description" content="Responsive Bootstrap 5 Dashboard Template">
    <meta name="twitter:image" content="https://www.bootstrapdash.com/azia/img/azia-social.png"> -->

    <!-- Facebook -->
    <!-- <meta property="og:url" content="https://www.bootstrapdash.com/azia">
    <meta property="og:title" content="Azia">
    <meta property="og:description" content="Responsive Bootstrap 5 Dashboard Template">

    <meta property="og:image" content="https://www.bootstrapdash.com/azia/img/azia-social.png">
    <meta property="og:image:secure_url" content="https://www.bootstrapdash.com/azia/img/azia-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600"> -->

    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap 5 Dashboard Template">
    <meta name="author" content="BootstrapDash">

    <title>Flugzeit Lead Management CRM</title>
    @include('layouts.css')
    <style>
        .az-content-title {
            font-size: 1.6rem;
            color: #4a4a4a;
            font-weight: 500;
        }

        .btn-az-primary {
            color: white;
            padding: 8px 18px;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-az-primary:hover {
            background-color: #2596be;
            transform: scale(1.05);
        }

        .thead-light {
            background-color: #f1f1f1;
            color: #555;
        }



        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h5.font-weight-bold {
            color: #333;
            font-size: 1.25rem;
        }

        hr {
            border-top: 1px solid #ddd;
            margin-top: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body onload="startTime()" style=" background-color: rgb(255, 255, 255)" >
    {{-- @include('layouts.header') --}}
    <div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40 ">
        <div class="container-fluid">
            <div class="az-content-body  d-flex flex-column">
                @yield('content')
            </div>
        </div><!-- container -->
    </div><!-- az-content -->
    <!-- Container-fluid Ends-->

    <!-- footer start-->

    {{-- @include('layouts.footer') --}}
    </div>

    <!-- latest jquery-->
    @include('layouts.script')
    {{-- @jquery
    @toastr_js
    @toastr_render --}}
    <!-- Plugin used-->

    <script type="text/javascript">
        if ($(".page-wrapper").hasClass("horizontal-wrapper")) {
            $(".according-menu.other").css("display", "none");
            $(".sidebar-submenu").css("display", "block");
        }
    </script>




    @stack('scripts')


{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();

            $('.fc-datepicker').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true
            });

            $('#datepickerNoOfMonths').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                numberOfMonths: 2
            });

            // AmazeUI Datetimepicker
            $('#datetimepicker').datetimepicker({
                format: 'yyyy-mm-dd hh:ii',
                autoclose: true
            });
            $('.datetimepickertime').datetimepicker({
                format: 'LT'
            });
            // jQuery Simple DateTimePicker
            $('#datetimepicker2').appendDtpicker({
                closeOnSelected: true,
                onInit: function(handler) {
                    var picker = handler.getPicker();
                    $(picker).addClass('az-datetimepicker');
                }
            });

            new Picker(document.querySelector('#datetimepicker3'), {
                headers: true,
                format: 'MMMM DD, YYYY HH:mm',
                text: {
                    title: 'Pick a Date and Time',
                    year: 'Year',
                    month: 'Month',
                    day: 'Day',
                    hour: 'Hour',
                    minute: 'Minute'
                },
            });
        });

    </script>
</body>

</html>
