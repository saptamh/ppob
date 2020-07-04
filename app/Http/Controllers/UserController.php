<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Yajra\Datatables\Datatables;

class UserController extends Controller

{
    function __construct()
    {
         $this->middleware('permission:user-list', ['only' => ['index']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if($request->ajax()){
            $data = User::select("*")->with('ModelHasRole.Role');

            return DataTables::of($data)->make(true);
        } else {
            return view('pages.user.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $roles = Role::pluck('name','name')->all();

        return view('pages.user.add',compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|max:50|email|unique:users,email,'.$request->id,
            'password' => 'min:6|confirmed|sometimes',
            'password_confirmation' => 'min:6|sometimes',
            'role' => 'required'
        ]);

        $input = $request->all();
        if (isset($request->password) && !empty($request->password)) {
            $input['password'] = Hash::make($input['password']);
        }

        if (isset($request->id)) {
            $user = User::find($request->id);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id',$request->id)->delete();
            $user->assignRole($request->input('role'));
        } else {
            $user = User::create($input);
            $user->assignRole($request->input('role'));
        }

        return redirect('/user')->with('message', 'Success!');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('pages.user.edit',compact('user','roles','userRole'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id, User $user, Request $request)
    {
        if ($request->ajax()) {
            $model = $user->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
