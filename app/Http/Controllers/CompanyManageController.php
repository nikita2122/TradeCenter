<?php
/**
 * Created by PhpStorm.
 * User: R
 * Date: 10/19/2022
 * Time: 8:53 PM
 */

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('spadmin');
    }

    public function index(Request $request)
    {
        $data = Company::where('is_active', true)->orderBy('name')->get();
        return view('spadmin.company', ['companies' => $data]);
    }

    public function add (Request $request)
    {
        Company::create([
            'name' => $request->name,
            'address' => $request->address,
            'is_active' => true
        ]);
        return response()->json('success');
    }

    public function edit (Request $request)
    {
        Company::where('id', $request->id)->update([
            'name' => $request->name,
            'address' => $request->address,
        ]);
        return response()->json('success');
    }

    public function delete (Request $request)
    {
        Company::where('id', $request->id)->update([
            'is_active' => false,
        ]);
        return response()->json('success');
    }
}