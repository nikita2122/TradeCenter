@extends('layouts.app')

@section('content')

    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel">
            
            <div class="panel-body">
                <div class="pl-sm">
                    <center> <h2>Change your profile </h2></center>
                    <br/>
                    <center>
                        <div class="row" style="display: flex; justify-content: center; align-items: center;">
                            <form class="col-md-4 text-left" action="{{ route('changepsd') }}" method="POST">
                                @csrf
                                <h4>Password Change</h4>
                                <div class="row mt-md">
                                    <label class="col-md-4">Old Password</label>
                                    <div class="col-md-8">
                                        <input type="password" name="oldpassword" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-sm">
                                    <label class="col-md-4">New Password</label>
                                    <div class="col-md-8">
                                        <input type="password" name="newpassword" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-sm">
                                    <label class="col-md-4">Password Repeat</label>
                                    <div class="col-md-8">
                                        <input type="password" name="passwordrpt" class="form-control"/>
                                    </div>
                                </div>
                                <div class="text-center mt-lg">
                                    <button class="btn btn-primary">Change</button>
                                </div>
                            </form>
                        </div>
                    </center>
                    <br/>
                    <center class="text-md"> Copyright @2022 </center>
                </div>
            </div>
           </section>
        </section>
    </section>

@endsection

@section('script')
    @if(Session::has('message'))
        <script>
            $(function(){
                toastr.warning("{{ Session::get('message') }}");
            })
        </script>
    @endif
    @if(Session::has('success'))
        <script>
            $(function(){
                toastr.success("{{ Session::get('success') }}");
            })
        </script>
    @endif
@endsection


