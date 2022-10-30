<?php
/**
 * Created by PhpStorm.
 * User: R
 * Date: 10/19/2022
 * Time: 8:53 PM
 */

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Exchange;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExchangeController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $data = Currency::with('company')
            ->where('is_active', true)
            ->where(function($query) {
                $company_id = Auth::user()->company_id;
                $query->whereNull('company_id')
                    ->orWhere('company_id', $company_id);
            })
            ->orderBy('name')
            ->get();
        return view('newexchange', ['currencies' => $data]);
    }

    public function report(Request $request)
    {
        return view('exchangelist');
    }

    public function get_list (Request $request)
    {
        $user = Auth::user();
        if ($user->approve == 2)
            $data = Exchange::where('company_id', $user->company_id)->get();
        else
            $data = Exchange::where('company_id', $user->company_id)
                ->where('user_id', $user->id)
                ->get();
        $result = [];
        foreach ($data as $datum) {
            array_push($result, ['data'=>$datum, 'currency'=>$datum->currency, 'user'=>$datum->user]);
        }
        return Datatables::of($result)->make(true);
    }

    public function export_list (Request $request)
    {
        $headers = array
        (
            'Content-Encoding: UTF-8',
            'Content-Type' => 'text/csv',
        );
        return response()->streamDownload(function () {
            echo "\xEF\xBB\xBF";
            $data = Exchange::get();
            echo "Receipt,Attend,Date,Type,Currency,Rate,Amount,Total,Paid Amount By Customer,Company Payment Method,Customer Payment Method,Customer Name,Customer PhoneNo\n";
            foreach ($data as $datum) {
                $line = str_pad($datum->receipt, 3, '0', STR_PAD_LEFT);
                $line = $line.','.$datum->user->name;
                $line = $line.','.$datum->date;
                $line = $line.','.$datum->method;
                $line = $line.','.$datum->currency->name;
                $line = $line.','.$datum->rate;
                $line = $line.','.$datum->amount;
                if ($datum->method == 'BUY')
                    $line = $line.','.$datum->total.' '.$datum->currency->name;
                else
                    $line = $line.','.$datum->total.' NGN ₦';

                if ($datum->method == 'BUY')
                    $line = $line.','.($datum->amount*$datum->rate).' NGN ₦';
                else
                    $line = $line.','.$datum->amount.' '.$datum->currency->name;

                $line = $line.','.$datum->company_pay;
                $line = $line.','.$datum->customer_pay;
                $line = $line.','.$datum->customer_name;
                $line = $line.','.$datum->phone_no;
                $line = $line."\n";
                echo $line;
            }
        }, 'report.csv', $headers);
    }

    public function test_exchange (Request $request) {
        return view('exchange');
    }

    public function print_exchange (Request $request) {
        $id = $request['id'];
        $exchange = Exchange::where('id', $id)->first();

        if ($exchange == null)
            return;

        $pdf = PDF::loadView('exchange', [
            'exchange' => $exchange
        ]);

        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );

        return $pdf->stream("exchange.pdf");
    }

    public function new_exchange (Request $request) {
        $phoneNo = $request['phone-no'];
        $customerName = $request['customer-name'];
        $amount = $request['amount'];
        $total = $request['total'];
        $rate = $request['rate'];
        $companyPay = $request['company-pay-method'];
        $customerPay = $request['customer-pay-method'];
        $method = $request['method'];
        $currency = $request['currency'];

        $user = Auth::user();
        $receipt = Exchange::where('company_id', $user->company_id)->max('receipt') + 1;
        $exchange = Exchange::create([
            'receipt' => $receipt,
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'currency_id' => $currency,
            'method' => $method,
            'rate' => doubleval($rate),
            'amount' => doubleval($amount),
            'total' => doubleval($total),
            'customer_name' => $customerName,
            'customer_pay' => $customerPay,
            'company_pay' => $companyPay,
            'phone_no' => $phoneNo,
            'date' => date('Y-m-d'),
        ]);

        return redirect('/printexchange?id='.$exchange->id);
    }
}