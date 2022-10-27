@extends('layouts.app')

@section('content')
    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel">
            <div class="panel-body">
                <div class="row mb-sm">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary" id="btn-export-csv">Export to CSV<i class="fa fa-file-excel-o ml-xs"></i></button>
                    </div>
                </div>
                <table class="table table-bordered table-responsive" id="table-exchange" style="width: 100%;"></table>
            </div>
           </section>
        </section>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        (function($) {
            'use strict';
            var tableExchange = $('#table-exchange').DataTable({
                serverSide: true,
                ajax: "/exchangelist",
                sort: false,
                columns: [{
                    title: 'Receipt',
                    data: 'data.receipt',
                    render: function(data) {
                        data = "" + data;
                        var cut = Math.max(3, data.length);
                        console.log(cut);
                        return ("00"+data).substr(-cut);
                    }
                },{
                    title: 'Date',
                    data: 'data.date'
                },{
                    title: 'Type',
                    data: 'data.method'
                },{
                    title: 'Currency',
                    data: 'currency.name'
                },{
                    title: 'Rate',
                    data: 'data.rate'
                },{
                    title: 'Amount',
                    data: 'data.amount',
                    render: function (data, meta, row) {
                        return data;
                    }
                },{
                    title: 'Total',
                    data: 'data.total',
                    render: function (data, meta, row) {
                        if (row['data']['method'] == 'BUY')
                            return row['data']['amount'] + ' ' + row['data']['currency'].name;
                        else
                            return data + ' ' + 'NGN ₦';
                    }
                },{
                    title: 'Pay Amount',
                    render: function (data, meta, row) {
                        if (row['data']['method'] == 'BUY')
                            return row['data']['amount']*row['data']['rate'] + ' ' + 'NGN ₦';
                        else
                            return row['data']['amount'] + ' ' + row['data']['currency'].name;
                    }
                },{
                    title: 'Company Pay',
                    data: 'data.company_pay'
                },{
                    title: 'Customer Pay',
                    data: 'data.customer_pay'
                },{
                    title: 'Customer Name',
                    data: 'data.customer_name'
                },{
                    title: '',
                    render: function (data, meta, row) {
                        return '<a href="printexchange?id=' + row['data']['id'] + '" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
                    }
                }],
                drawCallback: function() {
                }
            });

            $("#btn-export-csv").click(function () {
                window.open('/exchange-export','_blank');
            });
        }).apply(this, [jQuery]);
    </script>
@endsection
res

