<script src="{{ asset('/lib/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('/lib/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<script src="{{ asset('/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/lib/ionicons/ionicons.js') }}"></script>
<script src="{{ asset('/js/IntlTelInput.min.js') }}"></script>


<!--<script>
    alert('working');
</script>-->
<script>
    const phoneInputField = document.querySelector("#phone");
    const phoneInput = window.intlTelInput(phoneInputField, {
        preferredCountries: ["pk", "ca", "us", "uk"],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });


    const info = document.querySelector(".alert-info");



    function process(event) {
        event.preventDefault();

        const phoneNumber = phoneInput.getNumber();

        info.style.display = "";
        info.innerHTML = `Phone number in E.164 format: <strong>${phoneNumber}</strong>`;
    }



    // const phoneInputField1 = document.querySelector("#customer_cell");
    // const phoneInput1 = window.intlTelInput(phoneInputField1, {
    //     preferredCountries: ["pk", "ca", "us", "uk"],
    //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    // });
    // const info = document.querySelector(".alert-info");

    // function process(event) {
    //     event.preventDefault();
    //     const phoneNumber1 = phoneInput1.getNumber();


    //     info.style.display = "";
    //     info.innerHTML = `Phone number in E.164 format: <strong>${phoneNumber1}</strong>`;
    // }
</script>
{{-- @php
    $get_esc_count = App\escallation::all()->count();

@endphp --}}
<script src="{{ asset('/lib/jquery.flot/jquery.flot.js') }}"></script>
<script src="{{ asset('/lib/jquery.flot/jquery.flot.categories.js') }}"></script>
<script src="{{ asset('/lib/jquery.flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('/lib/flot.curvedlines/curvedLines.js') }}"></script>
<script src="{{ asset('/js/jquery.cookie.js') }}" type="text/javascript"></script>

<script src="{{ asset('/lib/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('/lib/spectrum-colorpicker/spectrum.js') }}"></script>
<script src="{{ asset('/lib/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('/lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<script src="{{ asset('/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<script src="{{ asset('/lib/pickerjs/picker.min.js') }}"></script>
<script src="{{ asset('/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/js/azia.js') }}"></script>
<script src="{{ asset('/js/chart.flot.sampledata.js') }}"></script>
<script src="{{ asset('/js/dashboard.sampledata.js') }}"></script>
<script src="{{ asset('/lib/jquery-steps/jquery.steps.min.js') }}"></script>
<script src="{{ asset('/lib/parsleyjs/parsley.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> --}}
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script src="{{ asset('/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js
"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"
    integrity="sha512-CryKbMe7sjSCDPl18jtJI5DR5jtkUWxPXWaLCst6QjH8wxDexfRJic2WRmRXmstr2Y8SxDDWuBO6CQC6IE4KTA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment-with-locales.min.js"
    integrity="sha512-vFABRuf5oGUaztndx4KoAEUVQnOvAIFs59y4tO0DILGWhQiFnFHiR+ZJfxLDyJlXgeut9Z07Svuvm+1Jv89w5g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"
    integrity="sha512-CryKbMe7sjSCDPl18jtJI5DR5jtkUWxPXWaLCst6QjH8wxDexfRJic2WRmRXmstr2Y8SxDDWuBO6CQC6IE4KTA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment-with-locales.min.js"
    integrity="sha512-vFABRuf5oGUaztndx4KoAEUVQnOvAIFs59y4tO0DILGWhQiFnFHiR+ZJfxLDyJlXgeut9Z07Svuvm+1Jv89w5g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // $(document).ready(function() {

    //     // Escalation Timer Start
    //     setInterval(() => {
    //         $.ajax({
    //             type: "GET",
    //             url: "{{ url('/escalation_timer_for_not_assign') }}",
    //             success: function() {}
    //         });

    //     }, 5000);
    //     setInterval(() => {
    //         $.ajax({
    //             type: "GET",
    //             url: "{{ url('/escalation_timer_for_open') }}",
    //             success: function() {}
    //         });
    //     }, 6000);
    //     // Escalation Timer End
    //     // Escalation Timer Start
    //     setInterval(() => {
    //         $.ajax({
    //             type: "GET",
    //             url: "{{ url('/escalation_timer_for_not_assign') }}",
    //             success: function() {}
    //         });

    //     }, 5000);
    //     setInterval(() => {
    //         $.ajax({
    //             type: "GET",
    //             url: "{{ url('/escalation_timer_for_open') }}",
    //             success: function() {}
    //         });
    //     }, 6000);
    //     // Escalation Timer End

    //     $('#country-dropdown').on('change', function() {
    //         var country_id = this.value;
    //         // alert(country_id)
    //         $("#city-dropdown").html('');
    //         $.ajax({
    //             url: "{{ url('get-cities-by-country') }}",
    //             type: "POST",
    //             data: {
    //                 country_id: country_id,
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             dataType: 'json',
    //             success: function(result) {
    //                 $('#city-dropdown').html(
    //                     '<option value="">Select City</option>');
    //                 $.each(result.cities, function(key, value) {
    //                     $("#city-dropdown").append('<option value="' +
    //                         value.id +
    //                         '">' + value.name + '</option>');
    //                 });
    //             }
    //         });
    //     });
    // });
</script>
<script>
    $(function() {
        'use strict'

        $('.az-iconbar .nav-link').on('click', function(e) {
            e.preventDefault();

            $(this).addClass('active');
            $(this).siblings().removeClass('active');

            $('.az-iconbar-aside').addClass('show');

            var targ = $(this).attr('href');
            $(targ).addClass('show');
            $(targ).siblings().removeClass('show');
        });

        $('.az-iconbar-body .with-sub').on('click', function(e) {
            e.preventDefault();
            $(this).parent().addClass('show');
            $(this).parent().siblings().removeClass('show');
        });

        $('.az-iconbar-toggle-menu').on('click', function(e) {
            e.preventDefault();

            if (window.matchMedia('(min-width: 992px)').matches) {
                $('.az-iconbar .nav-link.active').removeClass('active');
                $('.az-iconbar-aside').removeClass('show');
            } else {
                $('body').removeClass('az-iconbar-show');
            }
        })

        $('#azIconbarShow').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('az-iconbar-show');

            var targ = $('.az-iconbar .nav-link.active').attr('href');
            $(targ).addClass('show');
        });

        $(document).bind('click touchstart', function(e) {
            e.stopPropagation();

            var azContent = $(e.target).closest('.az-content').length;
            var azIconBarMenu = $(e.target).closest('.az-header-menu-icon').length;

            if (azContent) {
                $('.az-iconbar-aside').removeClass('show');

                // for mobile
                if (!azIconBarMenu) {
                    $('body').removeClass('az-iconbar-show');
                }
            }
        });

        /******************* DASHBOARD CHARTS **************************/

        $.plot('#flotBar1', [{
            data: [
                [1, 0],
                [2, 0],
                [3, 0],
                [4, 1],
                [5, 3],
                [6, 3],
                [7, 10],
                [8, 11],
                [9, 10],
                [10, 9],
                [11, 12],
                [12, 8],
                [13, 10],
                [14, 6],
                [15, 3]
            ],
            bars: {
                show: true,
                lineWidth: 0,
                fillColor: '#dee2e6',
                barWidth: .3,
                order: 'left'
            }
        }, {
            data: [
                [1, 0],
                [2, 0],
                [3, 1],
                [4, 2],
                [5, 2],
                [6, 5],
                [7, 8],
                [8, 12],
                [9, 10],
                [10, 11],
                [11, 3]
            ],
            bars: {
                show: true,
                lineWidth: 0,
                fillColor: '#006adb',
                barWidth: .3,
                align: 'right'
            }
        }], {
            grid: {
                borderWidth: 0
            },
            yaxis: {
                min: 0,
                max: 15,
                tickColor: '#ddd',
                ticks: [
                    [0, ''],
                    [5, '$500'],
                    [10, '$1000'],
                    [15, '$3000']
                ],
                font: {
                    color: '#444',
                    size: 10
                }
            },
            xaxis: {
                mode: 'categories',
                tickColor: '#eee',
                ticks: [
                    [0, '3am'],
                    [1, '4am'],
                    [2, '5am'],
                    [3, '6am'],
                    [4, '7am'],
                    [5, '8am'],
                    [6, '9am'],
                    [7, '10am'],
                    [8, '11am'],
                    [9, '12nn'],
                    [10, '1pm'],
                    [11, '2pm'],
                    [12, '3pm'],
                    [13, '4pm'],
                    [14, '5pm']
                ],
                font: {
                    color: '#999',
                    size: 9
                }
            }
        });


        $.plot('#flotLine1', [{
            data: [
                [1, 0],
                [2, 0],
                [3, 0],
                [4, 1],
                [5, 3],
                [6, 3],
                [7, 10],
                [8, 11],
                [9, 12],
                [10, 9],
                [11, 12],
                [12, 8],
                [13, 5],
                [14, 10],
                [15, 11]
            ],
            color: '#ced4da'
        }, {
            data: [
                [1, 0],
                [2, 0],
                [3, 1],
                [4, 2],
                [5, 2],
                [6, 5],
                [7, 8],
                [8, 12],
                [9, 9],
                [10, 11],
                [11, 5]
            ],
            color: '#f10075'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: 0.12
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0
            },
            yaxis: {
                min: 0,
                max: 15,
                tickColor: '#ddd',
                ticks: [
                    [0, ''],
                    [5, '100K'],
                    [10, '200K'],
                    [15, '300K']
                ],
                font: {
                    color: '#444',
                    size: 10
                }
            },
            xaxis: {
                mode: 'categories',
                tickColor: '#eee',
                ticks: [
                    [0, '3am'],
                    [1, '4am'],
                    [2, '5am'],
                    [3, '6am'],
                    [4, '7am'],
                    [5, '8am'],
                    [6, '9am'],
                    [7, '10am'],
                    [8, '11am'],
                    [9, '12nn'],
                    [10, '1pm'],
                    [11, '2pm'],
                    [12, '3pm'],
                    [13, '4pm'],
                    [14, '5pm']
                ],
                font: {
                    color: '#999',
                    size: 9
                }
            }
        });

        $.plot('#flotChart1', [{
            data: dashData5,
            color: '#560bd0'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: .5
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 60
            },
            xaxis: {
                show: false
            }
        });

        $.plot('#flotChart2', [{
            data: dashData6,
            color: '#006adb'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: .5
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 80
            },
            xaxis: {
                show: false
            }
        });

        $.plot('#flotChart3', [{
            data: dashData7,
            color: '#00cccc'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: .5
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 80
            },
            xaxis: {
                show: false
            }
        });

        $.plot('#flotChart4', [{
            data: dashData5,
            color: '#f10075'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: .5
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 80
            },
            xaxis: {
                show: false
            }
        });

        $.plot('#flotChart5', [{
            data: dashData6,
            color: '#3bb001'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: .5
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 80
            },
            xaxis: {
                show: false
            }
        });

        $.plot('#flotChart6', [{
            data: dashData7,
            color: '#fd7e14'
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: .5
                        }]
                    }
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 80
            },
            xaxis: {
                show: false
            }
        });

        $.plot('#flotChart7', [{
            data: dashData3,
            color: '#00cccc',
            curvedLines: {
                apply: true
            }
        }, {
            data: dashData4,
            color: '#560bd0',
            curvedLines: {
                apply: true
            }
        }], {
            series: {
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 0,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: .5
                        }, {
                            opacity: 1
                        }]
                    }
                },
                curvedLines: {
                    active: true
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: true,
                min: 0,
                max: 50,
                ticks: [
                    [0, ''],
                    [10, '100'],
                    [20, '200'],
                    [30, '300']
                ],
                tickColor: '#f3f3f3'
            },
            xaxis: {
                show: true,
                ticks: [
                    [0, ''],
                    [20, 'Nov 20'],
                    [40, 'Nov 21'],
                    [60, 'Nov 22']
                ],
                tickColor: 'rgba(255,255,255,0)'
            }
        });

        $.plot('#flotChart8', [{
            data: dashData4,
            color: '#3381d6'
        }], {
            series: {
                bars: {
                    show: true,
                    lineWidth: 0,
                    fill: 1,
                    barWidth: .5
                }
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: true,
                min: 0,
                max: 30,
                ticks: [
                    [0, ''],
                    [10, '100'],
                    [20, '200']
                ],
                tickColor: 'rgba(255,255,255,0)'
            },
            xaxis: {
                show: true,
                max: 40,
                ticks: [
                    [0, ''],
                    [15, 'Nov 20'],
                    [30, 'Nov 21']
                ],
                tickColor: 'rgba(255,255,255,0)'
            }
        });

        $.plot('#flotChart9', [{
            data: dashData3,
            color: '#fff',
            bars: {
                show: true,
                lineWidth: 0,
                barWidth: .5
            }
        }, {
            data: dashData4,
            color: '#fff',
            lines: {
                show: true,
                lineWidth: 2,
                fill: .16
            }
        }], {
            series: {
                shadowSize: 0
            },
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: true,
                min: 0,
                max: 30,
                ticks: [
                    [0, ''],
                    [10, '100'],
                    [20, '200']
                ],
                tickColor: 'rgba(255,255,255,0)'
            },
            xaxis: {
                show: true,
                max: 40,
                ticks: [
                    [0, ''],
                    [15, 'Nov 20'],
                    [30, 'Nov 21']
                ],
                tickColor: 'rgba(255,255,255,0)'
            }
        });

        //
    });
</script>

<script>
    // phone input
    for (let index = 0; index < 3; index++) {
        // store the instance variable so we can access it in the console e.g. window.iti.getNumber()
        window.iti = iti;

        (function($) {

            $('form_customer').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // alert(telInput);

                let form = $(this).get(0);
                $('#form_customer').addClass('was-validated');

                //Need to display invalid feedback explicitly on submit for input fields due to plugin changing structure
                $('#form_customer .tel-input').each(function() {
                    let telInput = $(this).get(0);
                    if ($(this).prop('required') && !telInput.checkValidity()) {
                        //                        $(this).parents('.form-group').find('.invalid-feedback').show();
                    }
                });

                // $('#form_customer').submit();
            });

            input.addEventListener("countrychange", function() {
                let isValidNumber = iti.isValidNumber();
                //                checkPhoneValidation($(this), isValidNumber);
            });

            $('#form_customer').on('keyup', '.tel-input', function(event) {
                let isValidNumber = iti.isValidNumber();
                checkPhoneValidation($(this), isValidNumber);
            });

        })(jQuery);

        function checkPhoneValidation(element, isValidNumber) {

            let invFeedbackDiv = element.parents('.form-group').find(`.invalid-feedback${index}`);
            input = element.get(0);

            if (isValidNumber) {
                //need to set this first as otherwise input validity is falsely linking to custom error
                input.setCustomValidity('');
            }

            if (isValidNumber && input.validity.valid) {
                input.setCustomValidity('');
                invFeedbackDiv.hide();
            } else {
                invFeedbackDiv.html('Invalid phone number');
                input.setCustomValidity('invalid');

                if ($('#form_customer').hasClass('was-validated')) {
                    invFeedbackDiv.show();
                }
            }

        }



    }
</script>


<script>
    $('#noti_my_jobs').on('click', function() {
        $.ajax({
            url: "{{ url('/get_notifications_my_jobs') }}",
            type: 'GET',
            success: function(response) {
                // alert('sdsd');
                $('#add_noti_my_jobs').empty().append(response);

            }

        });

    });
    $.ajax({

        url: "{{ url('/get_noti_count_my_jobs/') }}" + '/' + count,
        type: 'GET',
        success: function(response) {
            // alert(response.count);
            count = response.count;
            // alert(count);
            $('#noti_count_my_jobs').empty().append(response.count);
            if (count > 0) {
                $('#noti_my_jobs').addClass("new");
            }
            if (response.is_new == 1) {

                // alert('sdsd');
            }

        }

    });
    var my_jobs_count = 0;
    var interval_count = 0;
    setInterval(() => {
        interval_count++
        $.ajax({

            url: "{{ url('/get_noti_count_my_jobs/') }}" + '/' + my_jobs_count,
            type: 'GET',
            success: function(response) {
                // alert(response.count);
                my_jobs_count = response.count;
                if (my_jobs_count > 0) {
                    $('#noti_my_jobs').addClass("new");
                }
                // alert(count);

                $('#noti_count_my_jobs').empty().append(response.count);
                if (response.is_new == 1) {
                    // alert('new Notificacion');

                    toastr.info('New Notificacion Received');



                }

            }

        });
    }, 5000);
</script>




<script></script>




<script>
    // Noti general
    $('#noti_general').on('click', function() {
        $.ajax({
            url: "{{ url('/get_notifications_general') }}",
            type: 'GET',
            success: function(response) {
                // alert('sdsd');
                $('#add_noti_general').empty().append(response);

            }

        });

    });
    // var count = 0;
    // var general_count;
    // $.ajax({

    //     url: "{{ url('/get_noti_count_general/') }}" + '/' + general_count,
    //     type: 'GET',
    //     success: function(response) {
    //         // alert(response.count);
    //         payments_general = response.count;
    //         $('#noti_count_general').empty().append(response.count);
    //         if (general_count > 0) {
    //             $('#noti_payments').addClass("new");
    //         } else {
    //             $('#noti_payments').removeClass("new");
    //         }
    //         if (response.is_new == 1) {
    //             // alert('sdsd');
    //         }

    //     }

    // });

    // var interval_count_general = 0;
    // setInterval(() => {

    //     interval_count_general++;
    //     $.ajax({

    //         url: "{{ url('/get_noti_count_general/') }}" + '/' + payments_general,
    //         type: 'GET',
    //         success: function(response) {
    //             // alert(response.count);
    //             general_count = response.count;

    //             if (general_count > 0) {
    //                 $('#noti_general').addClass("new");
    //             } else {
    //                 $('#noti_general').removeClass("new");
    //             }
    //             // alert('sd');
    //             // alert(response.count);
    //             $('#noti_count_general').empty().append(response.count);
    //             if (response.is_new == 1) {

    //                 toastr.info('Follow-up Notification Received');
    //                 toastr.options = {
    //                     tapToDismiss: true,
    //                     timeOut: 0,
    //                     extendedTimeOut: 0,
    //                     allowHtml: true,
    //                     preventDuplicates: true,
    //                     preventOpenDuplicates: true,
    //                     newestOnTop: true,
    //                     closeButton: true,
    //                     closeHtml: '<button class="btn" style="background-color: grey; padding: 5px;">OK</button>'
    //                 }


    //             }

    //         }

    //     });
    // }, 5000);







    // $('#noti_payments').on('click', function() {
    //     $.ajax({
    //         url: "{{ url('/get_notifications_payments') }}",
    //         type: 'GET',
    //         success: function(response) {
    //             // alert('sdsd');
    //             $('#add_noti_payments').empty().append(response);

    //         }

    //     });

    // });
    // var count = 0;
    // var payments_count
    // $.ajax({

    //     url: "{{ url('/get_noti_count_payments/') }}" + '/' + count,
    //     type: 'GET',
    //     success: function(response) {
    //         // alert(response.count);
    //         payments_count = response.count;
    //         $('#noti_count_payments').empty().append(response.count);
    //         if (payments_count > 0) {
    //             $('#noti_payments').addClass("new");
    //         }
    //         if (response.is_new == 1) {
    //             // alert('sdsd');
    //         }

    //     }

    // });

    // var interval_count_payment = 0;
    // setInterval(() => {

    //     interval_count_payment++;
    //     $.ajax({

    //         url: "{{ url('/get_noti_count_payments/') }}" + '/' + payments_count,
    //         type: 'GET',
    //         success: function(response) {
    //             // alert(response.count);
    //             payments_count = response.count;

    //             if (payments_count > 0) {
    //                 $('#noti_payments').addClass("new");
    //             }
    //             // alert('sd');
    //             // alert(response.count);
    //             $('#noti_count_payments').empty().append(response.count);
    //             if (response.is_new == 1) {
    //                 toastr.info('New Notificacion Received');
    //             }

    //         }

    //     });
    // }, 5000);




    // $('#noti_escalations').on('click', function() {

    //     $.ajax({
    //         url: "{{ url('/get_escalations') }}",
    //         type: 'GET',
    //         success: function(response) {
    //             // alert('sdsd');
    //             $('#add_noti_escalations').empty().append(response);

    //         }

    //     });

    // });
    // $('#noti_approvals').on('click', function() {

    //     $.ajax({
    //         url: "{{ url('/get_approvals') }}",
    //         type: 'GET',
    //         success: function(response) {
    //             // alert('sdsd');
    //             $('#add_noti_approvals').empty().append(response);

    //         }

    //     });

    // });
    // $('#noti_issuance').on('click', function() {

    //     $.ajax({
    //         url: "{{ url('/get_issuance') }}",
    //         type: 'GET',
    //         success: function(response) {
    //             // alert('sdsd');
    //             $('#add_noti_issuance').empty().append(response);

    //         }

    //     });

    // });

    // function play_audio(task) {
    //     $(".my_audio").trigger('load');
    //     if (task == 'play') {
    //         $(".my_audio").trigger('play');
    //     }
    //     if (task == 'stop') {
    //         $(".my_audio").trigger('pause');
    //         $(".my_audio").prop("currentTime", 0);
    //     }
    // }



    // Escalation Noti Count End

    // Approval Noti Count Start
    // var approval_count = 0;
    // var is_play = 0;
    // var noti_counter = 0;

    // $.ajax({

    //     url: "{{ url('/get_approvals_count') }}" + '/' + approval_count,
    //     type: 'GET',
    //     success: function(response) {
    //         approval_count = response.count;
    //         // alert(approval_count);
    //         if (approval_count > 0) {
    //             $('#noti_approvals').addClass("new");
    //         }

    //         $('#noti_count_approvals').empty().append(response.count);
    //     }

    // });
    // setInterval(() => {
    //     $.ajax({

    //         url: "{{ url('/get_approvals_count') }}" + '/' + approval_count,
    //         type: 'GET',
    //         success: function(response) {
    //             approval_count = response.count;
    //             // alert(approval_count);
    //             if (approval_count > 0) {
    //                 $('#noti_approvals').addClass("new");
    //             }

    //             $('#noti_count_approvals').empty().append(response.count);
    //             if (response.is_new == 1 && noti_counter == 0) {
    //                 toastr.info('New Approval Received');
    //                 is_play = 1;
    //             } else {
    //                 is_play = 0;
    //             }

    //         }

    //     });
    //     if (is_play == 1) {
    //         $(".my_audio").trigger('load');
    //         play_audio('play');
    //     }
    // }, 5000);

    // Approval Noti Count End


    // Issuance Noti Count Start

    // var issuance_count = 0;
    // var is_play = 0;
    // var noti_counter = 0;

    // $.ajax({

    //     url: "{{ url('/get_issuance_count') }}" + '/' + issuance_count,
    //     type: 'GET',
    //     success: function(response) {
    //         issuance_count = response.count;
    //         // alert(approval_count);
    //         if (issuance_count > 0) {
    //             $('#noti_issuance').addClass("new");
    //         }

    //         $('#noti_count_issuance').empty().append(response.count);
    //     }

    // });
    // setInterval(() => {
    //     $.ajax({

    //         url: "{{ url('/get_issuance_count') }}" + '/' + issuance_count,
    //         type: 'GET',
    //         success: function(response) {
    //             issuance_count = response.count;
    //             // alert(response.count);
    //             // alert(approval_count);
    //             if (issuance_count > 0) {
    //                 $('#noti_approvals').addClass("new");
    //             }

    //             $('#noti_count_approvals').empty().append(response.count);
    //             if (response.is_new == 1 && noti_counter == 0) {
    //                 toastr.info('New Issuance Received');
    //                 is_play = 1;
    //             } else {
    //                 is_play = 0;
    //             }

    //         }

    //     });
    //     if (is_play == 1) {
    //         $(".my_audio").trigger('load');
    //         play_audio('play');
    //     }
    //     // alert(issuance_count)
    // }, 5000);
    // Issuance Noti Count End
</script>
<script>
    // toastr js

    $(document).ready(function() {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': false,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
    });



    // time picker
</script>
{{-- Country Mobile Number Code start  --}}
{{-- Country Mobile Number Code Ends --}}
