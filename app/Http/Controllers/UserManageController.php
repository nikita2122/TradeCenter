<?php
/**
 * Created by PhpStorm.
 * User: R
 * Date: 10/19/2022
 * Time: 8:53 PM
 */

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Symfony\Component\String\length;

class UserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $users = User::with('company')->where('approve', User::CLIENT)
            ->where('is_active', true)
            ->where('company_id', $company_id)
            ->orderBy('name')
            ->get();
        return view('admin.user', ['users' => $users]);
    }

    public function IsExists (string $email, int $id) {
        $exists = User::where('email', $email)
            ->where('id', '<>', $id)
            ->where('is_active',true)
            ->count();

        if ($exists > 0)
            return true;
        return false;
    }

    public function add (Request $request)
    {
        if ($this->IsExists($request->email, 0))
            return "";
        $company_id = Auth::user()->company_id;

        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'company_id' => $company_id,
            'password' => Hash::make($request->password),
            'approve' => User::CLIENT,
            'is_active' => true
        ]);
        return response()->json('success');
    }

    public function edit (Request $request)
    {
        if ($this->IsExists($request->email, $request->id))
            return "";
        $update = [
            'email' => $request->email,
            'name' => $request->name,
        ];
        if(strlen($request->password) > 0)
            $update['password'] = Hash::make($request->password);

        User::where('id', $request->id)->update($update);
        return response()->json('success');
    }

    public function delete (Request $request)
    {
        User::where('id', $request->id)->update([
            'is_active' => false,
        ]);
        return response()->json('success');
    }
}