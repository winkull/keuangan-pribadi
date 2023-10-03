<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Keuangan Pribadi</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    {{-- Sweat Alert 2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css">

    {{-- Datatable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />

    {{-- Toartr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Date Range --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>

<body style="overflow: hidden; height: 100%;">
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="img bg-wrap text-center py-4">
                <div class="user-logo">
                    <div class="img"
                        style="background-image: url('https://www.4freephotos.com/medium/batch/Opened-book-rose-1550.jpg');">
                    </div>
                    <h3>Keuangan Pribadi</h3>
                </div>
            </div>
            <ul class="list-unstyled components mb-5">
                <li class="@yield('dashboard')">
                    <a href="{{ route('dashboard') }}"><span class="fa fa-home mr-3"></span> Dashboard</a>
                </li>
                <li class="@yield('customer')">
                    <a href="{{ route('customer') }}"><span class="fa fa-address-book mr-3"></span> Customers</a>
                </li>
                <li class="@yield('product')">
                    <a href="{{ route('product') }}"><span class="fa fa-list mr-3"></span> Products</a>
                </li>
                <li class="@yield('transaction')">
                    <a href="{{ route('transaction') }}"><span class="fa fa-clipboard mr-3"></span> Transactions</a>
                </li>
                <li class="@yield('report')">
                    <a href="{{ route('report') }}"><span class="fa fa-print mr-3"></span> Reports</a>
                </li>
                <li class="@yield('setting')">
                    <a href="{{ route('setting') }}"><span class="fa fa-cog mr-3"></span> Settings</a>
                </li>
                <li>
                    <a href="javascript:void(0)" id="btn-logout"><span class="fa fa-sign-out mr-3"></span> Sign
                        Out</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-4 pt-5 bg-light">
            <button type="button" id="sidebarCollapse" class="btn btn-primary mb-3">
                <i class="fa fa-bars"></i>
            </button>
            <div style="height: 200%; overflow-y: auto;">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous">
    </script>

    {{-- Jqwery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    {{-- Toartr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Sweat Alert 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>

    {{-- Datatable --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    {{-- Select 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Date Range --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toaster Option 
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            }

            // Show Hide Sidebar
            var fullHeight = function() {

                $('.js-fullheight').css('height', $(window).height());
                $(window).resize(function() {
                    $('.js-fullheight').css('height', $(window).height());
                });

            };
            fullHeight();

            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });

            // Logout
            $('#btn-logout').click(function() {
                Swal.fire({
                    icon: 'info',
                    type: 'info',
                    title: 'Info',
                    text: 'Are you sure?',
                    showCancelButton: true,
                    showConfirmButton: true,
                }).then((res) => {
                    if (res) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: "{{ route('logout') }}",
                            type: "POST",
                            data: {
                                '_token': token
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    type: 'success',
                                    title: 'Info',
                                    text: res.message,
                                    timer: 1000,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                }).then(function() {
                                    window.location.href =
                                        "{{ route('login') }}";
                                });
                            },
                            error: function(res) {
                                Swal.fire({
                                    icon: 'error',
                                    type: 'error',
                                    title: 'Opps...',
                                    text: res.responseJSON.message,
                                });
                            },
                        });

                    }
                });
            });

            // Format Price
            $(document).on('input', '.format-number', function(e) {
                if (/^[0-9.,]+$/.test($(this).val())) {
                    $(this).val(
                        parseFloat($(this).val().replace(/,/g, '')).toLocaleString('en')
                    );
                } else {
                    $(this).val(
                        $(this)
                        .val()
                        .substring(0, $(this).val().length - 1)
                    );
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
