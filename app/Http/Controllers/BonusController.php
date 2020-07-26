<?php

namespace App\Http\Controllers;

use App\Bonus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class BonusController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:bonus-list', ['only' => ['index']]);
         $this->middleware('permission:bonus-create', ['only' => ['create','store']]);
         $this->middleware('permission:bonus-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:bonus-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Bonus::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.bonus.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.bonus.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rate' => 'required',
            'value' => 'required'
        ]);
        try {
            $input = $request->all();
            $model = new Bonus;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/bonus')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Bonus $bonus)
    {
        $data['edit'] = $bonus->where('id', $id)->first();

        return view('pages.bonus.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Bonus $bonus, Request $request)
    {
        if ($request->ajax()) {
            $model = $bonus->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
