<?php
/**
 * Created by PhpStorm.
 * User: R
 * Date: 10/19/2022
 * Time: 8:53 PM
 */

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Symfony\Component\String\length;

class CurrencyManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
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
        return view('admin.currency', ['currencies' => $data]);
    }

    public function IsExists (string $name, int $id) {
        $exists = Currency::where('name', $name)
            ->where('id', '<>', $id)
            ->where('is_active',true)
            ->count();

        if ($exists > 0)
            return true;
        return false;
    }

    public function add (Request $request)
    {
        if ($this->IsExists($request->name, 0))
            return "";
        $company_id = Auth::user()->company_id;

        Currency::create([
            'name' => $request->name,
            'company_id' => $company_id,
            'is_active' => true
        ]);
        return response()->json('success');
    }

    public function edit (Request $request)
    {
        if ($this->IsExists($request->name, $request->id))
            return "";
        $update = [
            'name' => $request->name,
        ];

        Currency::where('id', $request->id)->update($update);
        return response()->json('success');
    }

    public function delete (Request $request)
    {
        Currency::where('id', $request->id)->update([
            'is_active' => false,
        ]);
        return response()->json('success');
    }
}