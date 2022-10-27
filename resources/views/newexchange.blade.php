@extends('layouts.app')

@section('content')

    <section role="main" class="content-body">
        <!-- start: page -->
        <section class="panel">
            
            <div class="panel-body">
                <div class="pl-sm">
                    <center> <h1><img src="assets/images/logo.png" alt="" width="50px"/> New Exchange </h1></center>
                    <br/>
                    <center>
                        <div class="row" style="display: flex; justify-content: center; align-items: center;">
                            <div class="col-md-8 text-left">
                                <div class="row">
                                    <label class="col-md-3">Method</label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="method">
                                            <option value="BUY">BUY</option>
                                            <option value="SELL">SELL</option>
                                        </select>
                                    </div>
                                    <label class="col-md-3">Currency</label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="currency">
                                            @foreach( $currencies as $currency )
                                                <option value="{{$currency->id}}">{{$currency->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-md">
                                    <label class="col-md-3">Amount</label>
                                    <div class="col-md-3">
                                        <input type="number" id="amount" class="form-control"/>
                                    </div>
                                    <label class="col-md-3">Rate</label>
                                    <div class="col-md-3">
                                        <input type="number" id="rate" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-md">
                                    <label class="col-md-3">Total</label>
                                    <div class="col-md-6">
                                        <input type="number" id="total" class="form-control " readonly/>
                                    </div>
                                    <label class="col-md-3 lbl-currency text-left mt-xs" style="margin-left: -25px;"></label>
                                </div>
                                <div class="row mt-lg">
                                    <label class="col-md-3">Customer Payment Method</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="customer-pay-method">
                                            <option value="CASH">CASH</option>
                                            <option value="BANK TRANSFER">BANK TRANSFER</option>
                                            <option value="BANK TRANSFER & CASH">BANK TRANSFER & CASH</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-md">
                                    <label class="col-md-3">Company Payment Method</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="company-pay-method">
                                            <option value="CASH">CASH</option>
                                            <option value="BANK TRANSFER">BANK TRANSFER</option>
                                            <option value="BANK TRANSFER & CASH">BANK TRANSFER & CASH</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-md mb-lg">
                                    <label class="col-md-3">Customer Name</label>
                                    <div class="col-md-3">
                                        <input type="text" id="customer-name" class="form-control"/>
                                    </div>
                                    <label class="col-md-3">Phone No</label>
                                    <div class="col-md-3">
                                        <input type="text" id="phone-no" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-lg text-center">
                                    <button class="btn btn-primary" id="btn-checkout">Checkout <i class="fa fa-arrow-right ml-xs"></i></button>
                                </div>
                            </div>
                        </div>
                    </center>
                    <br/>
                    <center class="text-md"> Trade center is a good place for financial exchange </center>
                </div>
            </div>
           </section>
        </section>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        (function($) {
            'use strict';
            function setCurrencyForTotal () {
                var method = $('#method').val();
                var selected = $('#currency option:selected').text();

                if (method == "BUY") {
                    $('.lbl-currency').text(selected);
                } else {
                    $('.lbl-currency').text('NGN ₦');
                }
            }

            $('#currency, #method').change(function () {
                setCurrencyForTotal();
            }).change();

            function calcTotal () {
                var method = $('#method').val();
                var amount = $("#amount").val();
                var rate = $("#rate").val();
                if (method == "BUY") {
                    $("#total").val(amount);
                } else {
                    $("#total").val(amount*rate);
                }
            }

            $('#amount, #rate, #method').change(function () {
                calcTotal();
            }).change();

            $('input').on('input', function() {
               var pr = $(this).closest('div');
               if (pr.hasClass("has-error")) {
                   pr.removeClass("has-error");
               }
            });

            $('#btn-checkout').click(function () {
                var isValid  = true;
                var inputs = ["phone-no", "customer-name", "amount", "rate", "total"];
                var values = [];
                var params = {};
                inputs.forEach(function (input) {
                    var $this = $("#"+input);
                    var vl = $this.val();
                    values.push(vl);
                    var pr = $this.closest('div');
                    if (vl.length <= 0)
                    {
                        pr.addClass("has-error");
                        isValid = false;
                    }
                    params[input] = vl;
                });
                params['company-pay-method'] = $('#company-pay-method').val();
                params['customer-pay-method'] = $('#customer-pay-method').val();
                params['method'] = $('#method').val();
                params['currency'] = $('#currency').val();
                if (!isValid) {
                    toastr.warning("Please input the fields.");
                    return;
                }
                var urlParams = new URLSearchParams(params);
                window.open('/newexchange?'+urlParams.toString(), '_blank');
            });
        }).apply(this, [jQuery]);
    </script>
@endsection


