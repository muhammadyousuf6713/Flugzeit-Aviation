 <!DOCTYPE html>

 @if (\Request::is('rtl'))
     <html dir="rtl" lang="ar">
 @else
     <html lang="en">
 @endif

 <head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     @if (env('IS_DEMO'))
         <x-demo-metas></x-demo-metas>
     @endif

     <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
     <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-ct.png') }}">
     <title>
         AKU Dashboard CMS
     </title>
     <!--     Fonts and icons     -->
     <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
     <!-- Nucleo Icons -->
     <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
     <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
     <!-- Font Awesome Icons -->
     <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
     <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
     <!-- CSS Files -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
     <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

     <style>
         .z-index-9999 {
             z-index: 9999;
         }
     </style>
     {{-- @livewireStyles --}}

 </head>

 <body
     class="g-sidenav-show  bg-gray-100 {{ \Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '') }} ">
     @auth
         @yield('auth')

     @endauth
     @guest
         @yield('guest')
     @endguest

     @if (session()->has('success'))
         <section class="wrap wrap-vertical">

         </section>

         <div id="toast-container" class="toast-container">


             <div class="toast success">
                 <div class="toast-status-icon">
                     <svg>
                         <use xlink:href="#successToastIcon">
                     </svg>
                 </div>
                 <div class="toast-content">
                     <span>Success</span>
                     <p>Your changes are saved successfully.</p>
                 </div>
                 <button class="toast-close" onclick="closeToast(event)">
                     <svg>
                         <use xlink:href="#closeToastIcon">
                     </svg>
                 </button>
                 <div class="toast-duration"></div>
             </div>


             <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                 <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="successToastIcon">
                     <path fill="var(--color-status)"
                         d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                 </symbol>
             </svg>
         </div>
         <div x-data="{ show: true }" x-init="setTimeout(() => show = true, 4000)" x-show="show"
             style="color: #2b2e81; right: 0 !important; top: 3rem !important;"
             class="position-fixed bg-success rounded py-2 px-4 z-index-9999">
             <p class="m-0">{{ session('success') }}</p>
         </div>
     @endif


     <!--   Core JS Files   -->
     <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
     <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
     <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
     <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
     <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
     <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
     @stack('rtl')
     @stack('dashboard')
     <script>
         var win = navigator.platform.indexOf('Win') > -1;
         if (win && document.querySelector('#sidenav-scrollbar')) {
             var options = {
                 damping: '0.5'
             }
             Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
         }
     </script>

     <!-- Github buttons -->
     <script async defer src="https://buttons.github.io/buttons.js')}}"></script>
     <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
     <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
     {{-- @livewireScripts --}}

 </body>

 </html>
