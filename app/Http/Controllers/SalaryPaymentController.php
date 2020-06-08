<?php

namespace App\Http\Controllers;

use App\SalaryPayment;
use App\Employee;
use App\Salary;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class SalaryPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SalaryPayment $salaryPayment)
    {
        if($request->ajax()){
            $data = $salaryPayment->select('*')->with('Employee')
            ->with(['Employee.Level' => function($query) {
                $query->select('employee_id','level')->orderBy('created_at', 'desc')->first();
            }]);
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.salary-payment.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employee'] = Employee::select('id','name')->get()->pluck('name', 'id');
        return view('pages.salary-payment.add', $data);
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
            'employee_id' => 'required',
            'payment_date' => 'required',
            'salary' => 'required',
            'periode' => 'required',
        ]);

        $document_name = "";
        if ($request->hasFile('receipe')) {
                $document = $request->file('receipe');
                $document_name = $request->id . '-'.time().'.'.$document->getClientOriginalExtension();
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/salary', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
        } else {
            if (isset($request->receipe_hidden) && !empty($request->receipe_hidden)) {
                $document_name = $request->receipe_hidden;
            }
        }
        try {
            $input = $request->all();
            $input['receipe'] = $document_name;
            $model = new SalaryPayment;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/salary-payment')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SalaryPayments  $salaryPayments
     * @return \Illuminate\Http\Response
     */
    public function edit($id, SalaryPayment $salaryPayment)
    {
        $data['edit'] = $salaryPayment->where('id', $id)->first();
        $data['employee'] = Employee::select('id','name')->get()->pluck('name', 'id');

        return view('pages.salary-payment.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SalaryPayments  $salaryPayments
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, SalaryPayments $salaryPayments, Request $request)
    {
        if ($request->ajax()) {
            $model = $salaryPayments->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function salary($employee_id, Salary $salary, Request $request)
    {
        try {
            if ($request->ajax()) {
                $salary = $salary->with('Employee')->where('employee_id',$employee_id)->orderBy('id', 'desc')->first();
            }
        } catch(\Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500);
        }

        return response()->json(['data'=>$salary], 201);
    }
}
