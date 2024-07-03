<?php

namespace App\Http\Controllers\Application\Web\Users;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class CashiersController extends Controller
{

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if( !auth()->user()->hasAnyRole(['Super Admin'])) {
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
        $cashiers = User::role('Cashier User')->orderBy('name', 'asc');

        if(!empty($request->username)){
            $cashiers = $cashiers->where('username', 'LIKE', '%'.$request->username.'%');
        }

        if(!empty($request->phone_number)){
            $cashiers = $cashiers->where('phone_number', 'LIKE', '%'.$request->phone_number.'%');
        }

        if(!empty($request->name)){
            $cashiers = $cashiers->where('name', 'LIKE', '%'.$request->name.'%');
        }

        $cashiers = $cashiers->paginate(20);

        return view('application.users.cashiers.index',[
            'cashiers' => $cashiers,
            'active_page' => 'users',
            'active_subpage' => 'cashiers',
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
        return view('application.users.cashiers.create',[
            'active_page' => 'users',
            'active_subpage' => 'cashiers',
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

        $cashier = User::where('username', $request->username)->first();
        if(empty($cashier)){
            $cashier = new User();
        } else {
            return redirect()->back()->with('error_message', 'Username ini sudah terpakai, silahkan gunakan username lain untuk mendaftar');
        }

        $cashier->username = $request->username;
        $cashier->name = $request->name;
        $cashier->address = $request->address;
        $cashier->email = $request->email;
        $cashier->phone_number = $request->phone_number;
        $cashier->password = Hash::make($request->password);
        // $cashier->tenant_id = $request->tenant_id;
        $cashier->save();
        $cashier->assignRole('Cashier User');

        return redirect()
        ->route('application.users.cashiers.index')
        ->with('success_message', 'Berhasil menambahkan '.$cashier->name.' sebagai kasir!');
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
        $cashier = User::find($id);

        if(empty($cashier)){
            return redirect()->back()->with('error_message', 'Mohon maaf data tidak ditemukan, silahkan coba lagi dalam beberapa saat.');
        }

        return view('application.users.cashiers.edit',[
            'cashier' => $cashier,
            'active_page' => 'users',
            'active_subpage' => 'cashiers',
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

        $cashier= User::find($id);
        if(empty($cashier)){
            return redirect()->back()->with('error_message', 'Mohon maaf data tidak ditemukan, silahkan coba lagi dalam beberapa saat.');
        }

        $cashier->username = !empty($request->username) ? $request->username : $cashier->username;
        $cashier->name = !empty($request->name) ? $request->name : $cashier->name;
        $cashier->address = !empty($request->address) ? $request->address : $cashier->address;
        $cashier->email = !empty($request->email) ? $request->email : $cashier->email;
        $cashier->phone_number = !empty($request->phone_number) ? $request->phone_number : $cashier->phone_number;
        $cashier->password = !empty($request->password) ? Hash::make($request->password) : $cashier->password;
        $cashier->save();

        return redirect()
        ->route('application.users.cashiers.index')
        ->with('success_message', 'Berhasil mengupdate data '.$cashier->name.' !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cashier = User::find($id);

        if(empty($cashier)){
            return redirect()->back()->with('error_message', 'Mohon maaf data tidak ditemukan, silahkan coba lagi dalam beberapa saat.');
        }
        
        $deletedData = $cashier;
        $cashier->delete();

        return redirect()
        ->route('application.users.cashiers.index')
        ->with('success_message', 'Berhasil menghapus data '.$deletedData->name.' !');
    }
}
