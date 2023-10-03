@extends('layout.sidebar')
@section('product', 'active')
@section('content')
    <h2 class="font-weight-bold">Products</h2>
    <div class="table-responsive">
        <button class="btn btn-primary pull-right mb-3" {{-- data-bs-toggle="modal" data-bs-target="#modalCreate" --}} id="show-modal-create">Create</button>
        <table id="table-list" class="table table-striped table-hover" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Selling Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="modalCreate" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Create Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="mb-3">
                            <label for="name-create" class="form-label">Name *</label>
                            <input type="text" class="form-control border" id="name-create">
                            <small class="text-danger" id="name-create-error"></small>
                        </div>
                        <div class="mb-3">
                            <label for="selling_price-create" class="form-label">Selling Price *</label>
                            <div class="input-group border">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">RP.</span>
                                </div>
                                <input type="text" class="form-control text-end format-number" id="selling_price-create">
                            </div>
                            <small class="text-danger" id="selling_price-create-error"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-create">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update -->
    <div class="modal fade" id="modalUpdate" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="modalUpdateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUpdateLabel">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="mb-3">
                            <label for="name-update" class="form-label">Name *</label>
                            <input type="hidden" class="form-control border" id="id-update">
                            <input type="text" class="form-control border" id="name-update">
                            <small class="text-danger" id="name-update-error"></small>
                        </div>
                        <div class="mb-3">
                            <label for="selling_price-update" class="form-label">Selling Price *</label>
                            <div class="input-group border">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">RP.</span>
                                </div>
                                <input type="text" class="form-control text-end format-number" id="selling_price-update">
                            </div>
                            <small class="text-danger" id="selling_price-update-error"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-update">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // List Data
            var table = $('#table-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'selling_price',
                        name: 'selling_price',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: 2,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-end')
                    }
                }],
                order: [
                    [1, 'asc']
                ],
            });

            // Modal Create
            var ModalCreate = new bootstrap.Modal(document.getElementById('modalCreate'), {
                keyboard: false
            });

            // Modal Update
            var ModalUpdate = new bootstrap.Modal(document.getElementById('modalUpdate'), {
                keyboard: false
            });

            // Show Modal Create
            $('#show-modal-create').click(function() {
                ModalCreate.show();
                $('body').addClass('modal-open');
                $('small').text(null);
                $('#name-create').val(null);
                $('#selling_price-create').val(null);
            });

            // Create Data
            $('#btn-create').click(function() {
                var name = $('#name-create').val();
                var selling_price = $('#selling_price-create').val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (name.length == 0) {
                    $('#name-create-error').text('The name field is requered');
                } else {
                    $('#name-create-error').text(null);
                }

                if (selling_price.length == 0) {
                    $('#selling_price-create-error').text('The selling price field is requered');
                } else {
                    $('#selling_price-create-error').text(null);
                }

                if (name.length != 0 && selling_price.length != 0) {
                    $('#btn-create').prop('disabled', true);
                    $('#btn-create').text('Loading ...');

                    $.ajax({
                        url: "{{ route('product.store') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            name,
                            selling_price: selling_price.replace(',', ''),
                            '_token': token
                        },
                        success: function(res) {
                            table.ajax.reload();
                            $(".modal-backdrop.in").hide();
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            ModalCreate.hide();
                            toastr.success(res.message);

                            $('#btn-create').prop('disabled', false);
                            $('#btn-create').text('Save');
                        },
                        error: function(res) {
                            Swal.fire({
                                icon: 'error',
                                type: 'error',
                                title: 'Opps...',
                                text: res.responseJSON.message,
                            });

                            $('#btn-create').prop('disabled', false);
                            $('#btn-create').text('Save');
                        }
                    })
                }
            });

            // Show Data
            $('body').on('click', '#show-modal-update', function() {
                $('small').text(null);
                var id = $(this).data('id');
                var url = "{{ route('product.show', ':id') }}";
                url = url.replace(':id', id);
                $.get(url, function(data) {
                    ModalUpdate.show();
                    $('#id-update').val(data.data.id);
                    $('#name-update').val(data.data.name);
                    $('#selling_price-update').val(data.data.selling_price);
                });
            });

            // Update Data
            $('#btn-update').click(function() {
                var id = $('#id-update').val();
                var name = $('#name-update').val();
                var selling_price = $('#selling_price-update').val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (name.length == 0) {
                    $('#name-update-error').text('The name field is requered');
                } else {
                    $('#name-update-error').text(null);
                }

                if (selling_price.length == 0) {
                    $('#selling_price-update-error').text('The selling price field is requered');
                } else {
                    $('#selling_price-update-error').text(null);
                }

                if (name.length != 0 && selling_price.length != 0) {
                    $('#btn-update').prop('disabled', true);
                    $('#btn-update').text('Loading ...');
                    var url = "{{ route('product.update', ':id') }}";
                    url = url.replace(':id', id);

                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            name,
                            selling_price: selling_price.replace(',', ''),
                            '_token': token
                        },
                        success: function(res) {
                            table.ajax.reload();
                            $(".modal-backdrop.in").hide();
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            ModalUpdate.hide();
                            toastr.success(res.message);

                            $('#btn-update').prop('disabled', false);
                            $('#btn-update').text('Save');
                        },
                        error: function(res) {
                            Swal.fire({
                                icon: 'error',
                                type: 'error',
                                title: 'Opps...',
                                text: res.responseJSON.message,
                            });

                            $('#btn-update').prop('disabled', false);
                            $('#btn-update').text('Save');
                        }
                    })
                }
            });

            // Delete Data
            $('body').on('click', '#btn-delete', function() {
                Swal.fire({
                    icon: 'info',
                    type: 'info',
                    title: 'Info',
                    text: 'Are you sure to delele this data?',
                    showCancelButton: true,
                    showConfirmButton: true,
                }).then((res) => {
                    if (res) {
                        var id = $(this).data('id');
                        var url = "{{ route('product.delete', ':id') }}";
                        var token = $('meta[name="csrf-token"]').attr('content');
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                '_token': token
                            },
                            success: function(res) {
                                table.ajax.reload();
                                toastr.success(res.message);
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
        });
    </script>
@endpush
