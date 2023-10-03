@extends('layout.sidebar')
@section('transaction', 'active')
@section('content')
    <h2 class="font-weight-bold">Transactions</h2>
    <div class="row mb-3">
        <div class="col-md-3 mb-3">
            <label for="product_id" class="form-label">Chose Product</label><br>
            <select class="product-select form-select" aria-label="Default select example" id="product_id">
                <option></option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-selling_price="{{ $product->selling_price }}">
                        {{ $product->name }}</option>
                @endforeach
            </select>
            <small class="text-danger" id="product_id-error"></small>
        </div>
        <div class="col-md-3 mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="selling_prices">Rp.</span>
                </div>
                <input disabled type="text" value="0" class="form-control form-control-sm text-end"
                    id="selling_price">
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label for="purchase_price" class="form-label">Purchase Price</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="purchase_prices">Rp.</span>
                </div>
                <input type="text" value="" class="form-control text-end format-number" id="purchase_price"
                    placeholder="0">
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label for="profit" class="form-label">Profit</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="profits">Rp.</span>
                </div>
                <input disabled type="text" value="0" class="form-control form-control-sm text-end" id="profit">
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label for="customer_id" class="form-label">Customer</label><br>
            <select class="customer-select form-select" aria-label="Default select example" id="customer_id">
                <option></option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            <small class="text-danger" id="customer_id-error"></small>
        </div>
        <div class="col-md-3 mb-3">
            <label for="status" class="form-label">Payment Status</label><br>
            <select class="form-select form-select-sm" id="status">
                <option value="" selected>Chose Payment Status</option>
                <option value="1">Lunas</option>
                <option value="0">Utang</option>
            </select>
            <small class="text-danger" id="status-error"></small>
        </div>
        <div class="col-md-3 mb-3">
            <label for="description" class="form-label">Description</label><br>
            <textarea class="form-control form-control-sm" id="description" rows="1"></textarea>
        </div>
        <div class="col-md-3  mb-3">
            <label for="" class="form-label">&nbsp
            </label><br>
            <button type="button" class="btn btn-sm btn-primary" id="btn-create">Create New Transaction</button>
        </div>
    </div>
    <hr>
    <div class="table-responsive" style="overflow: auto">
        <table id="table-list" class="table table-striped table-hover" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Transaction</th>
                    <th>Customer</th>
                    <th>Price</th>
                    <th>Selling Price</th>
                    <th>Profit</th>
                    <th>Last Balance</th>
                    <th>Payment Status</th>
                    <th>Description</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script>
        function format(nominal) {
            return new Intl.NumberFormat('en-US').format(nominal);
        }
        $(document).ready(function() {
            // Select 2 Products
            $('.product-select').select2({
                placeholder: "Select a product",
                allowClear: true
            });

            // Select 2 Customers
            $('.customer-select').select2({
                placeholder: "Select a customer",
                allowClear: true
            });

            $('#product_id').change(function() {
                var selling_price = $(this).children('option:selected').data('selling_price');
                selling_price = format(selling_price);
                $('#selling_price').val(selling_price);
            });

            $('#purchase_price').keyup(function() {
                var selling_price = $('#selling_price').val().replace(',', '');
                var profit = selling_price - $(this).val().replace(',', '');
                profit = format(profit);
                $('#profit').val(profit);
            });

            // List Data
            var table = $('#table-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaction') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'transaction',
                        name: 'products.name'
                    },
                    {
                        data: 'customer',
                        name: 'customers.name'
                    },
                    {
                        data: 'purchase_price',
                        name: 'purchase_price'
                    },
                    {
                        data: 'selling_price',
                        name: 'selling_price'
                    },
                    {
                        data: 'profit',
                        name: 'profit'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                ],
                columnDefs: [{
                        targets: 4,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass('text-end')
                        }
                    },
                    {
                        targets: 5,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass('text-end')
                        }
                    },
                    {
                        targets: 6,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass('text-end')
                        }
                    },
                    {
                        targets: 7,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass('text-end')
                        }
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 5,
                lengthMenu: [5, 10, 20, 50, 100, 200, 500]
            });

            // Create Data
            $('#btn-create').click(function() {
                var product_id = $('#product_id').children('option:selected').val();
                var purchase_price = $('#purchase_price').val();
                var selling_price = $('#selling_price').val();
                var profit = $('#profit').val();
                var customer_id = $('#customer_id').children('option:selected').val();
                var status = $('#status').children('option:selected').val();
                var description = $('#description').val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (product_id.length == 0) {
                    $('#product_id-error').text('The product field is requered');
                } else {
                    $('#product_id-error').text(null);
                }

                if (customer_id.length == 0) {
                    $('#customer_id-error').text('The customer field is requered');
                } else {
                    $('#customer_id-error').text(null);
                }

                if (status.length == 0) {
                    $('#status-error').text('The payment status field is requered');
                } else {
                    $('#status-error').text(null);
                }

                if (product_id.length != 0 && customer_id.length != 0 && status.length != 0) {
                    $('#btn-create').prop('disabled', true);
                    $('#btn-create').text('Loading ...');

                    $.ajax({
                        url: "{{ route('transaction.store') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            product_id,
                            customer_id,
                            profit: profit.replace(',', ''),
                            status,
                            description,
                            purchase_price: purchase_price.replace(',', ''),
                            selling_price: selling_price.replace(',', ''),
                            '_token': token
                        },
                        success: function(res) {
                            table.ajax.reload();
                            toastr.success(res.message);

                            $('input').val(null);
                            $('#purchase_price').val(null);
                            $('#selling_price').val(0);
                            $('#profit').val(0);
                            $('#description').val(null);
                            $('option:selected', '#product_id').remove();
                            $('option:selected', '#customer_id').remove();
                            $('#status').prop('selectedIndex', 0);
                            $('#btn-create').prop('disabled', false);
                            $('#btn-create').text('Create New Transaction');
                        },
                        error: function(res) {
                            Swal.fire({
                                icon: 'error',
                                type: 'error',
                                title: 'Opps...',
                                text: res.responseJSON.message,
                            });

                            $('#btn-create').prop('disabled', false);
                            $('#btn-create').text('Create New Transaction');
                        }
                    })
                }
            });

            // Show Data
            // $('body').on('click', '#show-modal-update', function() {
            //     $('small').text(null);
            //     var id = $(this).data('id');
            //     var url = "{{ route('product.show', ':id') }}";
            //     url = url.replace(':id', id);
            //     $.get(url, function(data) {
            //         ModalUpdate.show();
            //         $('#id-update').val(data.data.id);
            //         $('#name-update').val(data.data.name);
            //         $('#purchase_price-update').val(data.data.purchase_price);
            //         $('#selling_price-update').val(data.data.selling_price);
            //     });
            // });

            // Update Data
            // $('#btn-update').click(function() {
            //     var id = $('#id-update').val();
            //     var name = $('#name-update').val();
            //     var purchase_price = $('#purchase_price-update').val();
            //     var selling_price = $('#selling_price-update').val();
            //     var token = $("meta[name='csrf-token']").attr("content");

            //     if (name.length == 0) {
            //         $('#name-update-error').text('The name field is requered');
            //     } else {
            //         $('#name-update-error').text(null);
            //     }

            //     if (purchase_price.length == 0) {
            //         $('#purchase_price-update-error').text('The purchase price field is requered');
            //     } else {
            //         $('#purchase_price-update-error').text(null);
            //     }

            //     if (selling_price.length == 0) {
            //         $('#selling_price-update-error').text('The selling price field is requered');
            //     } else {
            //         $('#selling_price-update-error').text(null);
            //     }

            //     if (name.length != 0 && purchase_price.length != 0 && selling_price.length != 0) {
            //         $('#btn-update').prop('disabled', true);
            //         $('#btn-update').text('Loading ...');
            //         var url = "{{ route('product.update', ':id') }}";
            //         url = url.replace(':id', id);

            //         $.ajax({
            //             url: url,
            //             type: "POST",
            //             dataType: "JSON",
            //             data: {
            //                 name,
            //                 purchase_price: purchase_price.replace(',', ''),
            //                 selling_price: selling_price.replace(',', ''),
            //                 '_token': token
            //             },
            //             success: function(res) {
            //                 table.ajax.reload();
            //                 $(".modal-backdrop.in").hide();
            //                 $('body').removeClass('modal-open');
            //                 $('.modal-backdrop').remove();
            //                 ModalUpdate.hide();
            //                 toastr.success(res.message);

            //                 $('#btn-update').prop('disabled', false);
            //                 $('#btn-update').text('Save');
            //             },
            //             error: function(res) {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     type: 'error',
            //                     title: 'Opps...',
            //                     text: res.responseJSON.message,
            //                 });

            //                 $('#btn-update').prop('disabled', false);
            //                 $('#btn-update').text('Save');
            //             }
            //         })
            //     }
            // });

            // Delete Data
            // $('body').on('click', '#btn-delete', function() {
            //     Swal.fire({
            //         icon: 'info',
            //         type: 'info',
            //         title: 'Info',
            //         text: 'Are you sure to delele this data?',
            //         showCancelButton: true,
            //         showConfirmButton: true,
            //     }).then((res) => {
            //         if (res) {
            //             var id = $(this).data('id');
            //             var url = "{{ route('product.delete', ':id') }}";
            //             var token = $('meta[name="csrf-token"]').attr('content');
            //             url = url.replace(':id', id);
            //             $.ajax({
            //                 url: url,
            //                 type: "POST",
            //                 data: {
            //                     '_token': token
            //                 },
            //                 success: function(res) {
            //                     table.ajax.reload();
            //                     toastr.success(res.message);
            //                 },
            //                 error: function(res) {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         type: 'error',
            //                         title: 'Opps...',
            //                         text: res.responseJSON.message,
            //                     });
            //                 },
            //             });

            //         }
            //     });
            // });
        });
    </script>
@endpush
