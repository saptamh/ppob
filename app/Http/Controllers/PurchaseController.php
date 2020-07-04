<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Project;
use App\GoodPurchase;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:purchase-list', ['only' => ['index']]);
         $this->middleware('permission:purchase-create', ['only' => ['create','store']]);
         $this->middleware('permission:purchase-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Purchase::with('Project')->select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.purchase.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['projects'] = Project::select('id', 'name')->orderBy('name', 'asc')->get()->pluck('name','id');
        $data['payment_status'] = ['debt', 'Down Payment', 'paid'];
        $data['term'] = ['Cash Before Delivery', 'Cash On Delivery', 'Down Payment'];

        return view('pages.purchase.add', $data);
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
            'supplier_name' => 'required',
            'supplier_address' => 'required',
            'payment_status' => 'required',
            'term_of_payment' => 'required'
        ]);
        try {
            $input = $request->all();
            $input['incoming_date'] = $input['incoming_date'] == "" ? NULL : $input['incoming_date'];
            $model = new Purchase;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/purchase')->with('message', 'Success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Purchase $purchase)
    {
        $data['edit'] = $purchase->where('id', $id)->first();
        $totalGoods = GoodPurchase::selectRaw('SUM(price*qty) AS total')->where('purchase_id', $data['edit']['id'])
        ->first();

        $data['percentase'] = ($data['edit']['term_of_payment'] == 2 ? floor(($data['edit']['down_payment'] / 100) * $totalGoods->total) : 0);
        $data['nominal'] = ($data['edit']['term_of_payment'] == 2 ? $totalGoods->total - floor(($data['edit']['down_payment'] / 100) * $totalGoods->total) : 0);

        $data['projects'] = Project::select('id', 'name')->orderBy('name', 'asc')->get()->pluck('name','id');
        $data['payment_status'] = ['debt', 'Down Payment', 'paid'];
        $data['term'] = ['Cash Before Delivery', 'Cash On Delivery', 'Down Payment'];

        return view('pages.purchase.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Purchase $purchase, Request $request)
    {
        if ($request->ajax()) {
            $model = $purchase->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
