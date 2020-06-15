<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Employee;
use App\SalaryPayment;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Payment::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.payment.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.payment.add', $data);
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
            'payment_method' => 'required',
            'paid_date' => 'required',
            'payment_status' => 'required',
            'description' => 'required',
        ]);

        $document_name = "";
        if ($request->hasFile('upload')) {
                $document = $request->file('upload');
                $document_name = $request->id . '-'.time().'.'.$document->getClientOriginalExtension();
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/payment', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
        } else {
            if (isset($request->upload_hidden) && !empty($request->upload_hidden)) {
                $document_name = $request->upload_hidden;
            }
        }

        try {
            $input = $request->all();
            $input['upload'] = $document_name;
            $model = new Payment;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }

            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/payment')->with('message', 'Success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Payment $payment)
    {
        $data['edit'] = $payment->where('id', $id)->first();

        return view('pages.payment.edit', $data);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Payment $payment, Request $request)
    {
        if ($request->ajax()) {
            $model = $payment->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    function getSalaryPayment(SalaryPayment $salary_payment, Request $request) {
        if($request->ajax()){
            try {
                $data = $salary_payment->select('*')
                ->with('Employee')
                ->where('id', $request->payment_id)
                ->first();
            } catch(\Exception $e) {
                return response()->json(['error'=>$e->getMessage()], 500);
            }

            return response()->json(['data'=>$data], 201);
        }
    }
}
