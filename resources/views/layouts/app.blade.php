<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Favicon-->
        <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
        <!-- JQuery DataTable Css -->
        <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}">
        <!-- Multi Select Css -->
        <link rel="stylesheet" href="{{asset('assets/plugins/multi-select/css/multi-select.css')}}">
        <!-- Bootstrap Select Css -->
        <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-select/css/bootstrap-select.css')}}">
        <!-- Custom Css -->
        <link rel="stylesheet" href="{{asset('assets/css/amaze.style.min.css')}}">
        {{-- Sweet Alert --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}">
        {{-- DateRange --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        {{-- Summernote --}}
        <link rel="stylesheet" href="{{asset('assets/plugins/summernote/dist/summernote.css')}}"/>
        {{-- CKEDITOR --}}
        <style>
        .cke{visibility:hidden;}
        .cke{width:100% !important;}
        .cke_panel{width: 20% !important;}
        </style>    
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/ckeditor/skins/kama/editor.css?t=L7C8') }} ">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/ckeditor/plugins/scayt/dialogs/dialog.css?t=L7C8') }} ">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/ckeditor/plugins/tableselection/styles/tableselection.css') }} ">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/ckeditor/plugins/dialog/styles/dialog.css') }} ">

        <style>
            /* .daterangepicker select.hourselect,
            .daterangepicker select.minuteselect {
                display: none !important;
            } */
            .datalist-input[list] {
                width: 250px;
                padding: 10px;
                border: 2px solid #4f46e5;
                border-radius: 6px;
                font-size: 14px;
            }

            .datalist-input[list]:focus {
                outline: none;
                border-color: #22c55e;
            }
        </style>
        
    </head>
    <body class="font-ubuntu" base_url="{{url("/")}}" >
        <div id="body" class="theme-cyan">

            <!-- Page Loader -->
            <div class="page-loader-wrapper">
                <div class="loader">
                    <div class="mt-3 d-flex align-items-center justify-content-center">
                        <img class="w60" src="{{asset('assets/images/header.png')}}" style="width:400px !important;" alt="Amaze">
                    </div>
                    <p>Please wait...</p>        
                </div>
            </div>

            <div class="overlay"></div>
            
            @include('layouts.navigation')


            <div class="body_area after_bg">
                
                {{ $header ?? '' }}

                {{ $slot }}

                {{-- <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-12 col-lg-12">
                            <div class="card">
                                <div class="body">
                                    <p class="copyright mb-0">Copyright {{date("Y")}} © All Rights Reserved. <a href="https://indigo21.com/" target="_blank">Indigo 21 Ltd.</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            
            <!-- Large Size -->
            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="largeModalLabel">Modal title</h4>
                        </div>
                        <div class="modal-body"> dignissim nibh faucibus ullamcorper.
                            Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                            nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                            Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc. </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect">SAVE CHANGES</button>
                            <button type="button" class="btn btn-danger btn-simple waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medium Size -->
            <div class="modal fade" id="formModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="formModalLabel">Modal title</h4>
                        </div>
                        <div class="modal-body" id="formModalBody"> 
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" id="formModalSave">SAVE CHANGES</button>
                            <button type="button" class="btn btn-danger btn-simple waves-effect" id="formModalClose" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>

            {{ $modal ?? '' }}





            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Jquery Core Js --> 
            <script src="{{asset('assets/bundles/libscripts.bundle.js')}}"></script> <!-- Lib Scripts Plugin Js --> 
            <script src="{{asset('assets/bundles/vendorscripts.bundle.js')}}"></script> <!-- Lib Scripts Plugin Js --> 
            <!-- Multi Select Plugin Js --> 
            <script src="{{asset('assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script> 
            <!-- Jquery DataTable Plugin Js --> 
            <script src="{{asset('assets/bundles/datatablescripts.bundle.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
            <script src="{{asset('ajax/libs/pdfmake/0.1.53/pdfmake.min.js')}}"></script>
            <script src="{{asset('ajax/libs/pdfmake/0.1.53/vfs_fonts.js')}}"></script>
            <script src="{{asset('ajax/libs/jszip/3.1.3/jszip.min.js')}}"></script>
            <!-- Jquery Validation Plugin Css --> 
            <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.js')}}"></script> 
            <!-- JQuery Steps Plugin Js --> 
            <script src="{{asset('assets/plugins/jquery-steps/jquery.steps.js')}}"></script> 
            {{-- JQuery DateRange --}}
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            {{-- Template Custom --}}
            <script src="{{asset('assets/bundles/mainscripts.bundle.js')}}"></script>
            <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
            {{-- <script src="{{asset('assets/plugins/summernote/dist/summernote.js')}}"></script> --}}
            {{-- <script src="{{asset('assets/plugins/ckeditor/ckeditor.js')}}"></script> <!-- Ckeditor --> 
            <script src="{{asset('assets/plugins/ckeditor/editors.js')}}"></script> <!-- Ckeditor -->                  --}}
            {{-- CKEDITOR --}}
            <script type="text/javascript" src="{{asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
            {{-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> --}}
            <script type="text/javascript" src="{{asset('assets/plugins/ckeditor/config.js?t=L7C8') }}"></script>
            <script type="text/javascript" src="{{asset('assets/plugins/ckeditor/lang/en.js?t=L7C8') }}"></script>
            <script type="text/javascript" src="{{asset('assets/plugins/ckeditor/styles.js?t=L7C8') }}"></script>

            
            @if(Session::has('success'))
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "{{Session::get('success')}}",
                        draggable: false,
                    });
                    $(".swal2-select").remove();
                </script>
            @endif 
            
            {{ $script ?? '' }}

            <script src="{{asset('assets/custom/js/global/softdelete.js')}}"></script>
            <script src="{{asset('assets/custom/js/global/formModal.js')}}"></script>

        </div>
    </body>

</html>
