@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />

    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel row col-md-8">
            <div class="panel-heading">
                <h2 class="m-none text-black">Users</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-md">
                            <button id="add-user" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="table table-bordered table-striped mb-none" id="datatable-editable" width="100%">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $users as $user )
                            <tr data-id="{{ $user->id }}">
                                <td class="user-email"> {{ $user->email }}</td>
                                <td class="user-name"> {{ $user->name }}</td>
                                <td>
                                    <a href="#" class="on-default edit-user"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="on-default remove-user"><i class="fa fa-trash-o"></i></a>
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
                <h2 class="panel-title"><span class="action"></span> Client</h2>
            </header>
            <div class="panel-body">
                <div class="row mt-md">
                    <label class="col-md-3">Email</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="user-email"/>
                    </div>
                </div>
                <div class="row mt-md">
                    <label class="col-md-3">Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="user-name"/>
                    </div>
                </div>
                <div class="row mt-md mb-md">
                    <label class="col-md-3">Password</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="user-password"/>
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
            var name, email;

            function getData ($this) {
                var $tr = $this.closest("tr");
                editId = $tr.data('id');
                name = $tr.find("td[class=user-name]").text().trim();
                email = $tr.find("td[class=user-email]").text().trim();
            }

            function onResponse (resp) {
                if (resp == 'success') {
                    window.history.go();
                } else {
                    toastr.warning("failed");
                }
            }

            $('#add-user').click(function() {
                isEdit = false;
                $('#edit-dialog .action').text("Add");

                $('#user-name').val("");
                $('#user-email').val("");
                $('#user-password').val("");

                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.edit-user').click(function() {
                isEdit = true;
                getData($(this));
                $('#edit-dialog .action').text("Edit");

                $('#user-name').val(name);
                $('#user-email').val(email);
                $('#user-password').val("");

                $.magnificPopup.open({
                    items: {
                        src: '#edit-dialog',
                        type: 'inline'
                    },
                    modal: true,
                });
            });

            $('.remove-user').click(function(){
                getData($(this));
                onConfirm("delete", deleteUser);
            });

            function deleteUser () {
                $.ajax({
                    url: '/admin/user',
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

                var newName = $('#user-name').val();
                var newEmail = $('#user-email').val();
                var newPassword = $('#user-password').val();

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
                };

                if (isEdit)
                    data['id'] = editId;

                $.ajax({
                    url: '/admin/user',
                    method: method,
                    data: data,
                    success: onResponse
                });
            });
        }).apply(this, [jQuery]);
    </script>
@endsection