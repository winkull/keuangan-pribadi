<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- Sweat Alert 2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css">

    <style>
        @media only screen and (min-width: 400px) {
            .marginLogin {
                margin-top: 8%
            }
        }
    </style>
</head>

<body>
    <section class="vh-100 marginLogin">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form>
                        <div
                            class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-4">
                            <p class="lead fw-bold fw-normal mb-0 me-3">Sign in</p>
                        </div>

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" class="form-control form-control-lg" />
                            <small class="text-danger p-2" id="username-error"></small>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" class="form-control form-control-lg" />
                                <span class="input-group-text" id="basic-addon2"><i class="fa fa-eye" id="show-hide"
                                        toggle="#password" style="cursor: pointer"></i></span>
                            </div>
                            <small class="text-danger p-2" id="password-error"></small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" class="btn btn-primary" id="btn-login">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
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

    {{-- Sweat Alert 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#btn-login').click(function() {
                var username = $('#username').val();
                var password = $('#password').val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (username.length == 0) {
                    $('#username-error').text('The username is required.');
                } else {
                    $('#username-error').text(null);
                }

                if (password.length == 0) {
                    $('#password-error').text('The password is required.');
                } else {
                    $('#password-error').text(null);
                }



                $('#btn-login').text('Loading ...');
                $('#btn-login').prop('disabled', true);
                $.ajax({
                    url: "{{ route('login.process') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        'username': username,
                        'password': password,
                        '_token': token
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            type: 'success',
                            title: 'Info',
                            text: 'Login Successfully!',
                            timer: 1000,
                            showCancelButton: false,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = "{{ route('dashboard') }}";
                        });
                    },
                    error: function(res) {
                        if (res.status == 401) {
                            Swal.fire({
                                icon: 'warning',
                                type: 'warning',
                                title: 'Oops...',
                                text: res.responseJSON.message,
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                type: 'error',
                                title: 'Oops...',
                                text: res.responseJSON.message,
                            })
                        }
                        $('#btn-login').prop('disabled', false);
                        $('#btn-login').text('Login');
                    }
                });
            });

            $('#show-hide').click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");

                var input = $($(this).attr("toggle"));

                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        });
    </script>
</body>

</html>
