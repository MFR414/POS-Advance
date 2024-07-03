<?php

namespace App\Http\Controllers\Application\Web\Users;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if( !auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Tenant User'])) {
                abort(404);
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admins = User::role('Admin')->orderBy('name', 'asc');

        // if(!auth()->user()->hasAnyRole(['Super Admin'])){
        //     $admins = $admins->where('tenant_id', auth()->user()->tenant_id);
        // }

        if(!empty($request->username)){
            $admins = $admins->where('username', 'LIKE', '%'.$request->username.'%');
        }

        if(!empty($request->phone_number)){
            $admins = $admins->where('phone_number', 'LIKE', '%'.$request->phone_number.'%');
        }

        if(!empty($request->name)){
            $admins = $admins->where('name', 'LIKE', '%'.$request->name.'%');
        }

        $admins = $admins->paginate(20);

        return view('application.users.admins.index',[
            'admins' => $admins,
            'active_page' => 'users',
            'active_subpage' => 'admins',
            'search_terms'=>[
                'username' => $request->username,
                'phone_number' => $request->phone_number,
                'name' => $request->name,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $tenants = Tenant::orderBy('name', 'asc')->get();
        return view('application.users.admins.create',[
            // 'tenants' => $tenants,
            'active_page' => 'users',
            'active_subpage' => 'admins',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validate
        $validation_rules = [
            'name' => 'required',
            'username' => 'required|unique:users,username,NULL,id,deleted_at,NULL',
            'phone_number' => 'required|numeric|min:10',
            'password' => 'required|min:8|max:32|confirmed',
        ];

        if (! empty($request->email)) {
            $validation_rules['email'] = 'email';
        }

        $this->validate($request,$validation_rules);

        $admin = User::where('username', $request->username)->first();
        if(empty($admin)){
            $admin = new User();
        } else {
            return redirect()->back()->with('error_message', 'Username ini sudah terpakai, silahkan gunakan username lain untuk mendaftar');
        }

        $admin->username = $request->username;
        $admin->name = $request->name;
        $admin->address = $request->address;
        $admin->email = $request->email;
        $admin->phone_number = $request->phone_number;
        $admin->password = Hash::make($request->password);

        // if(!auth()->user()->hasAnyRole(['Super Admin'])){
        //     $admin->tenant_id = auth()->user()->tenant_id;
        // } else {
        //     $admin->tenant_id = $request->tenant_id;
        // }

        $admin->save();

        $admin->assignRole('Admin');

        return redirect()
        ->route('application.users.admins.index')
        ->with('success_message', 'Berhasil menambahkan '.$admin->name.' sebagai admin!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = User::find($id);
        // $tenants = Tenant::orderBy('name', 'asc')->get();
        if(empty($admin)){
            return redirect()->back()->with('error_message', 'Mohon maaf data admin tidak ditemukan, silahkan coba lagi dalam beberapa saat.');
        }

        return view('application.users.admins.edit',[
            'admin' => $admin,
            // 'tenants' => $tenants,
            'active_page' => 'users',
            'active_subpage' => 'admins',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if(!empty($request->password)){
            $validation_rules['password'] = 'required|min:8|max:32|confirmed';
            $this->validate($request,$validation_rules);
        }

        $admin= User::find($id);
        if(empty($admin)){
            return redirect()->back()->with('error_message', 'Mohon maaf data admin tidak ditemukan, silahkan coba lagi dalam beberapa saat.');
        }

        $admin->username = !empty($request->username) ? $request->username : $admin->username;
        $admin->name = !empty($request->name) ? $request->name : $admin->name;
        $admin->address = !empty($request->address) ? $request->address : $admin->address;
        $admin->email = !empty($request->email) ? $request->email : $admin->email;
        $admin->phone_number = !empty($request->phone_number) ? $request->phone_number : $admin->phone_number;
        $admin->password = !empty($request->password) ? Hash::make($request->password) : $admin->password;

        // if(!auth()->user()->hasAnyRole(['Super Admin'])){
        //     $admin->tenant_id = auth()->user()->tenant_id;
        // } else {
        //     $admin->tenant_id = $request->tenant_id;
        // }

        $admin->save();

        return redirect()
        ->route('application.users.admins.index')
        ->with('success_message', 'Berhasil mengupdate data '.$admin->name.' !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = User::find($id);

        if(empty($admin)){
            return redirect()->back()->with('error_message', 'Mohon maaf data admin tidak ditemukan, silahkan coba lagi dalam beberapa saat.');
        }

        $deletedData = $admin;
        // $admin->delete();

        dd($deletedData);

        return redirect()
        ->route('application.users.admins.index')
        ->with('success_message', 'Berhasil menghapus data '.$deletedData->name.' !');
    }
}
