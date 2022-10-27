@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />

    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel row col-md-8">
            <div class="panel-heading">
                <h2 class="m-none text-black">Currencies</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-md">
                            <button id="add-currency" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="table table-bordered table-striped mb-none" id="datatable-editable" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $currencies as $currency )
                            <tr data-id="{{ $currency->id }}">
                                <td class="currency-name"> {{ $currency->name }}</td>
                                <td>
                                    @if($currency->company_id == Auth::user()->company_id)
                                        <a href="#" class="on-default edit-currency"><i class="fa fa-edit"></i></a>
                                        <a href="#" class="on-default remove-currency"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </section>


    <div id="edit-dialog" class="modal-block mfp-hide">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title"><span class="action"></span> Currency</h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <label class="col-md-3">Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="currency-name"/>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-default btn-primary dialog-ok">OK</button>
                        <button class="btn btn-default dialog-cancel" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </footer>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        (function($) {
            'use strict';

            var editId = 0;
            var isEdit = false;
            var name;

            function getData ($this) {
                var $tr = $this.closest("tr");
                editId = $tr.data('id');
                name = $tr.find("td[class=currency-name]").text().trim();
            }

            function onResponse (resp) {
                if (resp == 'success') {
                    window.history.go();
                } else {
                    toastr.warning("failed");
                }
            }

            $('#add-currency').click(function() {
                isEdit = false;
                $('#edit-dialog .action').text("Add");
                $('#currency-name').val("");
                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.edit-currency').click(function() {
                isEdit = true;
                getData($(this));
                $('#edit-dialog .action').text("Edit");
                $('#currency-name').val(name);

                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.remove-currency').click(function(){
                getData($(this));
                onConfirm("delete", deleteCurrency);
            });

            function deleteCurrency () {
                $.ajax({
                    url: '/admin/currency',
                    method: 'DELETE',
                    data: {
                        id: editId
                    },
                    success: onResponse
                });
            }

            $('#edit-dialog .dialog-ok').click(function () {
                var method='POST';
                if (isEdit)
                    method='PUT';

                var newName = $('#currency-name').val();

                if(newName.length == 0)
                    return toastr.warning("Name cannot be empty.");

                var data = {
                    name: newName
                };
                if (isEdit)
                    data['id'] = editId;

                $.ajax({
                    url: '/admin/currency',
                    method: method,
                    data: data,
                    success: onResponse
                });
            });
        }).apply(this, [jQuery]);
    </script>
@endsection