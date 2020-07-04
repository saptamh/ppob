<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Employee;
use App\SalaryPayment;
use App\Project;
use App\Nonpurchase;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use PaymentHelp;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:payment-list', ['only' => ['index']]);
         $this->middleware('permission:payment-create', ['only' => ['create','store']]);
         $this->middleware('permission:payment-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Payment::select('*')
            ->with('Project')
            ->with('Source');
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
            $status = 'PENDING';
            $input['source_id'] = $input['source_id'] == "OFFICE" ? NULL : $input['source_id'];
            if (isset($request->paid)) {
                $status = 'PAID';
                PaymentHelp::savePaymentToPettyCash($input);

            }
            if (isset($request->reject)) {
                $status = 'REJECT';
            }
            $input['payment_status'] = $status;

            $model = new Payment;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

            //update to purchase request (salary payment or nonpurchase)
            PaymentHelp::updatePaymentProcessStatus($model->payment_type, $model->payment_id, $model->payment_status);

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
        if ($data['edit']['project_id']) {
            $project = Project::where('id', $data['edit']['project_id'])->first();
            $data['source'] = ['OFFICE'=>'OFFICE', $project->id => $project->name];
        } else {
            $data['source'] = ['OFFICE'=>'OFFICE'];
        }

        $data['pr_document'] = '';
        if ($data['edit']['payment_type'] == 'SALARY') {
            $data['pr_document'] = SalaryPayment::select('upload')->where('id', $data['edit']['payment_id'])->first('upload');
        }

        if ($data['edit']['payment_type'] == 'NONPURCHASE') {
            $data['pr_document'] = Nonpurchase::select('upload')->where('id', $data['edit']['payment_id'])->first('upload');
        }

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
