<?php

namespace App\Http\Controllers;

use App\BillItem;
use App\Project;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class BillItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = BillItem::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.bill.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['project'] = Project::select('id','name')->get()->pluck('name', 'id');
        $data['bill_type'] = ['IN', 'OUT'];

        return view('pages.bill.add', $data);
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
            'project_id' => 'required',
            'bill_type' => 'required',
            'bill_date' => 'required',
            'pic_name' => 'required',
            'work_area' => 'required',
            'description' => 'required',
        ]);
        try {
            $input = $request->all();
            $model = new BillItem;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/bill')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BillItem  $billItem
     * @return \Illuminate\Http\Response
     */
    public function edit($id, BillItem $billItem)
    {
        $data['edit'] = $billItem->where('id', $id)->first();

        return view('pages.bill.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BillItem  $billItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, BillItem $billItem, Request $request)
    {
        if ($request->ajax()) {
            $model = $billItem->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
