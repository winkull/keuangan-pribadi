@extends('layout.sidebar')
@section('report', 'active')
@section('content')
    <h2 class="font-weight-bold">Reports</h2>
    <div class="row mb-3">
        <div class="col-md-auto mb-3">
            <label for="product_id" class="form-label">Chose Product</label><br>
            <select class="product-select form-select" aria-label="Default select example" id="product_id">
                <option></option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-purchase_price="{{ $product->purchase_price }}"
                        data-selling_price="{{ $product->selling_price }}">
                        {{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="customer_id" class="form-label">Customer</label><br>
            <select class="customer-select form-select" aria-label="Default select example" id="customer_id">
                <option></option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="status" class="form-label">Payment Status</label><br>
            <select class="form-select form-select-sm" id="status">
                <option value="" selected>All</option>
                <option value="1">Lunas</option>
                <option value="0">Utang</option>
            </select>
        </div>
        <div class="col-md-auto mb-3">
            <label for="date" class="form-label">Date Range</label>
            <input type="text" class="form-control form-control-sm" id="date" value="">
        </div>
        <div class="col-md-auto mb-3 mt-2">
            <label for="btn-search" class="form-label"></label>
            <button type="button" class="form-control form-control-sm btn btn-sm btn-primary"
                id="btn-search">Search</button>
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <div class="pull-right mb-3">
            <button disabled class="btn btn-primary" id="btn-export-excel">
                <span class="fa fa-file-excel-o mr-2"></span>Export
                Excel
            </button>
            <button disabled class="btn btn-secondary" id="btn-print">
                <span class="fa fa-print mr-2"></span>Print
            </button>

            <form id="print" hidden action="{{ route('report.print') }}" method="post" target="_blank">
                @csrf
                <input type="text" id="product_id_print" name="product_id">
                <input type="text" id="customer_id_print" name="customer_id">
                <input type="text" id="status_print" name="status">
                <input type="text" id="date_print" name="date">
            </form>
        </div>
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
                </tr>
            </thead>
            <tbody id="tbody">
                <tr>
                    <td colspan="10">
                        <Center>No data available in table</Center>
                    </td>
                </tr>
            </tbody>
            <tfoot id="tfoot"></tfoot>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#date').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });
        });

        $(document).ready(function() {
            // Select 2 Products
            $('.product-select').select2({
                placeholder: "All Products",
                allowClear: true
            });

            // Select 2 Customers
            $('.customer-select').select2({
                placeholder: "All Customers",
                allowClear: true
            });

            // Search Data
            $('#btn-search').click(function() {
                var product_id = $('#product_id').children('option:selected').val();
                var customer_id = $('#customer_id').children('option:selected').val();
                var status = $('#status').children('option:selected').val();
                var date = $('#date').val();

                $('#product_id_print').val(product_id);
                $('#customer_id_print').val(customer_id);
                $('#status_print').val(status);
                $('#date_print').val(date);

                $('#btn-search').prop('disabled', true);
                $('#btn-search').text('Loading ...');

                $.ajax({
                    url: "{{ route('report') }}",
                    type: "GET",
                    data: {
                        product_id,
                        customer_id,
                        status,
                        date
                    },
                    success: function(res) {
                        if (res.data) {
                            $('#btn-export-excel').attr('disabled', false);
                            $('#btn-print').attr('disabled', false);
                        }
                        $('#tbody').html(res.tbody);
                        $('#tfoot').html(res.tfoot);

                        $('#btn-search').prop('disabled', false);
                        $('#btn-search').text('search');
                    },
                    error: function(res) {
                        Swal.fire({
                            icon: 'error',
                            type: 'error',
                            title: 'Opps...',
                            text: res.responseJSON.message,
                        });

                        $('#btn-search').prop('disabled', false);
                        $('#btn-search').text('search');
                    }
                })
            });

            // Export Excel
            $('#btn-export-excel').click(function() {
                var product_id = $('#product_id').children('option:selected').val();
                var customer_id = $('#customer_id').children('option:selected').val();
                var status = $('#status').children('option:selected').val();
                var date = $('#date').val();
                var url = "{{ route('report.export') }}" +
                    `?product_id=${product_id}&customer_id=${customer_id}&status=${status}&date=${date}`;

                window.open(url, '_blank');
            })

            // Print
            $('#btn-print').click(function() {
                $('#print').submit();
            })
        });
    </script>
@endpush
