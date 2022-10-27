@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />

    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel row col-md-8">
            <div class="panel-heading">
                <h2 class="m-none text-black">Companies</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-md">
                            <button id="add-company" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="table table-bordered table-striped mb-none" id="datatable-editable" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $companies as $company )
                            <tr data-id="{{ $company->id }}">
                                <td class="company-name"> {{ $company->name }}</td>
                                <td class="company-address"> {{ $company->address }}</td>
                                <td>
                                    <a href="#" class="on-default edit-company"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="on-default remove-company"><i class="fa fa-trash-o"></i></a>
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
                <h2 class="panel-title"><span class="action"></span> Company</h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <label class="col-md-3">Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="company-name"/>
                    </div>
                </div>
                <div class="row mt-md">
                    <label class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="company-address"/>
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
            var name, address;

            function getData ($this) {
                var $tr = $this.closest("tr");
                editId = $tr.data('id');
                name = $tr.find("td[class=company-name]").text().trim();
                address = $tr.find("td[class=company-address]").text().trim();
            }

            function onResponse (resp) {
                if (resp == 'success') {
                    window.history.go();
                } else {
                    toastr.warning("failed");
                }
            }

            $('#add-company').click(function() {
                isEdit = false;
                $('#edit-dialog .action').text("Add");
                $('#company-name').val("");
                $('#company-address').val("");
                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.edit-company').click(function() {
                isEdit = true;
                getData($(this));
                $('#edit-dialog .action').text("Edit");
                $('#company-name').val(name);
                $('#company-address').val(address);

                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.remove-company').click(function(){
                getData($(this));
                onConfirm("delete", deleteCompany);
            });

            function deleteCompany () {
                $.ajax({
                    url: '/spadmin/company',
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

                var newName = $('#company-name').val();
                var newAddress = $('#company-address').val();

                if(newName.length == 0)
                    return toastr.warning("Name cannot be empty.");
                if(newAddress.length == 0)
                    return toastr.warning("Address cannot be empty.");

                var data = {
                    name: newName,
                    address: newAddress
                };
                if (isEdit)
                    data['id'] = editId;

                $.ajax({
                    url: '/spadmin/company',
                    method: method,
                    data: data,
                    success: onResponse
                });
            });
        }).apply(this, [jQuery]);
    </script>
@endsection