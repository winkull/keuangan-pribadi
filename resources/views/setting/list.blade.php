@extends('layout.sidebar')
@section('setting', 'active')
@section('content')
    <h2 class="font-weight-bold mb-4">Settings</h2>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold">Account</h6>
                    <hr>
                    <table class="table table-borderless">
                        <tbody id="tbody">
                            <tr>
                                <td>Loading ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold">Update Balance</h6>
                    <hr>
                    <form>
                        <div class="form-group">
                            <label for="type">Choses Type</label>
                            <select id="type" class="form-select">
                                <option value="1">Add</option>
                                <option value="0">Subtract</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nominal">Nominal</label>
                            <div class="input-group border">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">RP.</span>
                                </div>
                                <input type="text" class="form-control text-end format-number" id="nominal">
                            </div>
                            <small class="text-danger" id="nominal-error"></small>
                        </div>
                        <button type="button" class="btn btn-primary pull-right" id="btn-update-balance">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-bold">Reset Password</h6>
                    <hr>
                    <form>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password">
                                        <span class="input-group-text" id="basic-addon2"><i class="fa fa-eye"
                                                id="show-hide-password" toggle="#password"
                                                style="cursor: pointer"></i></span>
                                    </div>
                                    <small class="text-danger" id="password-error"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password">
                                        <span class="input-group-text" id="basic-addon2"><i class="fa fa-eye"
                                                id="show-hide-password-confirm" toggle="#confirm_password"
                                                style="cursor: pointer"></i></span>
                                    </div>
                                    <small class="text-danger" id="confirm_password-error"></small>
                                    <small class="text-danger" id="not_match-error"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="btn-reset-password">&nbsp</label><br>
                                    <button type="button" class="btn btn-primary" id="btn-reset-password">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.get("{{ route('setting.account') }}", function(res) {
                $('#tbody').html(res.data);
            });

            $('#show-hide-password').click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");

                var input = $($(this).attr("toggle"));

                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            $('#show-hide-password-confirm').click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");

                var input = $($(this).attr("toggle"));

                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            $('#btn-update-balance').click(function() {
                var type = $('#type').children('option:selected').val();
                var nominal = $('#nominal').val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (nominal.length == 0) {
                    $('#nominal-error').text('The nominal field is requered');
                } else {
                    $('#nominal-error').text(null);
                }

                if (nominal.length != 0) {
                    $('#btn-update-balance').prop('disabled', true);
                    $('#btn-update-balance').text('Loading ...');

                    $.ajax({
                        url: "{{ route('setting.update.saldo') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            type,
                            nominal: nominal.replace(',', ''),
                            '_token': token
                        },
                        success: function(res) {
                            toastr.success(res.message);

                            $.get("{{ route('setting.account') }}", function(res) {
                                $('#tbody').html(res.data);
                            });

                            $('#nominal').val(null);
                            $('#btn-update-balance').prop('disabled', false);
                            $('#btn-update-balance').text('Update');
                        },
                        error: function(res) {
                            Swal.fire({
                                icon: 'error',
                                type: 'error',
                                title: 'Opps...',
                                text: res.responseJSON.message,
                            });

                            $('#btn-update-balance').prop('disabled', false);
                            $('#btn-update-balance').text('Update');
                        }
                    });
                }
            });

            $('#btn-reset-password').click(function() {
                var password = $('#password').val();
                var confirm_password = $('#confirm_password').val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (password.length < 5) {
                    $('#password-error').text('The password field min 5 characters');
                } else {
                    $('#password-error').text(null);
                }
                if (confirm_password.length == 0) {
                    $('#confirm_password-error').text('The confirm password field is requered');
                } else {
                    $('#confirm_password-error').text(null);
                }
                if (password != confirm_password) {
                    $('#not_match-error').text(
                        'The confirm password field doesn`n macth with new password');
                } else {
                    $('#not_match-error').text(null);
                }

                if (password.length >= 5 && confirm_password.length != 0 && password == confirm_password) {
                    $('#btn-reset-password').prop('disabled', true);
                    $('#btn-reset-password').text('Loading ...');

                    $.ajax({
                        url: "{{ route('setting.reset.password') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            password,
                            '_token': token
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            $('#btn-reset-password').prop('disabled', false);
                            $('#btn-reset-password').text('Reset');
                        },
                        error: function(res) {
                            Swal.fire({
                                icon: 'error',
                                type: 'error',
                                title: 'Opps...',
                                text: res.responseJSON.message,
                            });

                            $('#btn-reset-password').prop('disabled', false);
                            $('#btn-reset-password').text('Reset');
                        }
                    });
                }
            });
        });
    </script>
@endpush
