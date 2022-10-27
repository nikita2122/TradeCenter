@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />

    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel row col-md-8">
            <div class="panel-heading">
                <h2 class="m-none text-black">Admin Users</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-md">
                            <button id="add-admin" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="table table-bordered table-striped mb-none" id="datatable-editable" width="100%">
                        <thead>
                        <tr>
                            <th>Company Name</th>
                            <th hidden></th>
                            <th>Email</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $admins as $admin )
                            <tr data-id="{{ $admin->id }}">
                                <td>{{ $admin->company->name }}</td>
                                <td class="admin-companyid" hidden> {{ $admin->company_id }}</td>
                                <td class="admin-email"> {{ $admin->email }}</td>
                                <td class="admin-name"> {{ $admin->name }}</td>
                                <td>
                                    <a href="#" class="on-default edit-admin"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="on-default remove-admin"><i class="fa fa-trash-o"></i></a>
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
                <h2 class="panel-title"><span class="action"></span> Admin</h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <label class="col-md-3">Company</label>
                    <div class="col-md-9">
                        <select id="sel-company" class="form-control">
                            <option value="0">[Unassigned]</option>
                            @foreach( $companies as $company )
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-md">
                    <label class="col-md-3">Email</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="admin-email"/>
                    </div>
                </div>
                <div class="row mt-md">
                    <label class="col-md-3">Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="admin-name"/>
                    </div>
                </div>
                <div class="row mt-md mb-md">
                    <label class="col-md-3">Password</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="admin-password"/>
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
            var name, email, company_id;

            function getData ($this) {
                var $tr = $this.closest("tr");
                editId = $tr.data('id');
                name = $tr.find("td[class=admin-name]").text().trim();
                email = $tr.find("td[class=admin-email]").text().trim();
                company_id = $tr.find("td[class=admin-companyid]").text().trim();
            }

            function onResponse (resp) {
                if (resp == 'success') {
                    window.history.go();
                } else {
                    toastr.warning("failed");
                }
            }

            $('#add-admin').click(function() {
                isEdit = false;
                $('#edit-dialog .action').text("Add");

                $('#admin-name').val("");
                $('#admin-email').val("");
                $('#sel-company').val("0");
                $('#admin-password').val("");

                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.edit-admin').click(function() {
                isEdit = true;
                getData($(this));
                $('#edit-dialog .action').text("Edit");

                $('#admin-name').val(name);
                $('#admin-email').val(email);
                $('#sel-company').val(company_id);
                $('#admin-password').val("");

                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.remove-admin').click(function(){
                getData($(this));
                onConfirm("delete", deleteAdmin);
            });

            function deleteAdmin () {
                $.ajax({
                    url: '/spadmin/admin',
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

                var newName = $('#admin-name').val();
                var newEmail = $('#admin-email').val();
                var newPassword = $('#admin-password').val();
                var newCompanyId = parseInt($('#sel-company').val());

                if(newCompanyId == 0)
                    return toastr.warning("Please select the company.");
                if(newName.length == 0)
                    return toastr.warning("Name cannot be empty.");
                if(newEmail.length == 0)
                    return toastr.warning("Email cannot be empty.");
                if((!isEdit || newPassword.length > 0) && newPassword.length < 6)
                    return toastr.warning("Password length must be longer than 6.");

                var data = {
                    name: newName,
                    email: newEmail,
                    password: newPassword,
                    company_id: newCompanyId
                };

                if (isEdit)
                    data['id'] = editId;

                $.ajax({
                    url: '/spadmin/admin',
                    method: method,
                    data: data,
                    success: onResponse
                });
            });
        }).apply(this, [jQuery]);
    </script>
@endsection