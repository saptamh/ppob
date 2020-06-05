<?php

namespace App\Http\Controllers;

use App\GoodPurchase;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class GoodPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $id = $request->purchase_id;
            $data = GoodPurchase::selectRaw("id,purchase_id,part_number,name,qty,price,created_at,updated_at,SUM(qty*price) AS total")
            ->where('purchase_id', $id)->groupBy('id')->orderBy('created_at', 'asc');

            return DataTables::of($data)->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $model = new GoodPurchase;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        $totalPrice = GoodPurchase::selectRaw('SUM(price*qty) AS total')->where('purchase_id', $input['purchase_id'])
        ->first();

        return response()->json(array('success'=>true, 'data'=>['total_price'=>$totalPrice->total]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodPurchase  $goodPurchase
     * @return \Illuminate\Http\Response
     */
    public function template($id, GoodPurchase $goodPurchase)
    {
        $data['purchase_id'] = $id;

        return view('pages.purchase.goods', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodPurchase  $goodPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, GoodPurchase $goodPurchase, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $goodPurchase->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
