<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use PaymentHelp;

class ApprovalController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:nonpurchase-approval', ['only' => ['nonpurchase', 'store']]);
         $this->middleware('permission:purchase-approval', ['only' => ['purchase','store']]);
         $this->middleware('permission:salaryPayment-approval', ['only' => ['salaryPayment','store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nonpurchase(Request $request)
    {
        if($request->ajax()){
            $data = Payment::select('*')
            ->where('is_manager_approval', "PENDING")
            ->where('payment_type','NONPURCHASE');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.approval.nonpurchase');
        }
    }

    public function salaryPayment(Request $request)
    {
        if($request->ajax()){
            $data = Payment::select('*')
            ->where('is_manager_approval', "PENDING")
            ->where('payment_type','SALARY');;
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.approval.salary');
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

        return view('pages.employee.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $source = "SALARY";
        if ($request->type == "nonpurchase") {
            $source = "NONPURCHASE";
        }

        $status = "PENDING";
        $reason = "";
        if ($request->status == "REJECT") {
            $status = "REJECT";
            $reason = $request->reason;
            //update to purchase request (salary payment or nonpurchase)
            PaymentHelp::updatePaymentProcessStatus($source, $request->payment_id, $status, $reason);
        }

        Payment::where('id', $request->id)->update(['is_manager_approval'=>$request->status]);

        return redirect('/approval/'.$request->type)->with('message', 'Success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Employee $employee)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['edit'] = $employee->where('id', $id)->first();

        return view('pages.employee.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Employee $employee, Request $request)
    {
        if ($request->ajax()) {
            $model = $employee->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    private function __getDropdown() {
        $data['religion'] = ['islam', 'kristen', 'hindu', 'budha', 'khonghucu'];
        $data['education'] = ['sd', 'smp', 'sma', 'd3', 's1', 's2'];
        $data['location'] = ['office','project'];
        $data['status'] = ['kontrak', 'tetap', 'phl'];

        return $data;
    }
}
