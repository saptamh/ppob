<?php

namespace App\Http\Controllers;

use App\PettyCash;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use PaymentHelp;

class PettyCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:pettyCash-list', ['only' => ['index']]);
         $this->middleware('permission:pettyCash-create', ['only' => ['create','store']]);
         $this->middleware('permission:pettyCash-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:pettyCash-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = PettyCash::select('*','petty_cashes.id as rawId')
            ->with('Project');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.petty-cash.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['select_box'] = $this->__getDropdown();

        return view('pages.petty-cash.add', $data);
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
            'budget_for' => 'required',
            'date' => 'required',
            'noted_news' => 'required',
            'name_bank_from' => 'required',
            'name_bank_to' => 'required',
            'nominal' => 'required',
        ]);

        $document_name = "";
        if ($request->hasFile('upload')) {
                $document = $request->file('upload');
                $document_name = $request->id . '-'.time().'.'.$document->getClientOriginalExtension();
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/patty_cash', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
        } else {
            if (isset($request->upload_hidden) && !empty($request->upload_hidden)) {
                $document_name = $request->upload_hidden;
            }
        }

        try {
            $input = $request->all();
            $input['upload'] = $document_name;
            $model = new PettyCash;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            } else {
                $input['number'] = PaymentHelp::generateCode();
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/petty-cash')->with('message', 'Success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function show(PettyCash $pettyCash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function edit($id,PettyCash $pettyCash)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['edit'] = $pettyCash->where('id', $id)->first();

        return view('pages.petty-cash.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PettyCash $pettyCash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, PettyCash $pettyCash, Request $request)
    {
        if ($request->ajax()) {
            $model = $pettyCash->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    private function __getDropdown() {
        $data['budget'] = ['OFFICE', 'PROJECT'];
        $data['type'] = ['KREDIT', 'DEBET'];

        return $data;
    }
}
