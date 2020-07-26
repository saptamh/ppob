<?php

namespace App\Http\Controllers;

use App\SalaryPayment;
use App\Employee;
use App\Salary;
use App\Project;
use App\Payment;
use App\Bonus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use PaymentHelp;
use App\Mail\ManagerPaymentNotification;
use Illuminate\Support\Facades\Mail;

class SalaryPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:salaryPayment-list', ['only' => ['index']]);
         $this->middleware('permission:salaryPayment-create', ['only' => ['create','store']]);
         $this->middleware('permission:salaryPayment-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:salaryPayment-delete', ['only' => ['destroy']]);
    }

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
            }])
            ->with('Project');

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
            'salary' => 'required',
            'periode' => 'required',
        ]);

        $document_name = "";
        if ($request->hasFile('upload')) {
                $document = $request->file('upload');
                $document_name = $request->id . '-'.time().'.'.$document->getClientOriginalExtension();
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/salary', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
        } else {
            if (isset($request->upload_hidden) && !empty($request->upload_hidden)) {
                $document_name = $request->upload_hidden;
            }
        }
        try {
            $input = $request->all();
            $input['upload'] = $document_name;
            $input['payment_process_status'] = "PENDING";
            $model = new SalaryPayment;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);

            $save = $model->save();

            if ($save) {
                if (!isset($input['id'])) {
                    $employee = Employee::select('name')->where('id', $input['employee_id'])->first();
                    $dataToPayment = [
                        'payment_name' => 'Pembayaran Gaji ' . $employee->name,
                        'payment_type' => 'SALARY',
                        'payment_total' => $input['total_salary'],
                        'payment_id' => $model->id,
                        'project_id' => isset($input['project_id']) ? $input['project_id'] : NULL,
                    ];
                    PaymentHelp::savePaymentPartial($dataToPayment);
                } else {
                    Payment::where('payment_type', 'salary')
                    ->where('payment_id', $input['id'])
                    ->update([
                        'payment_total'=> $input['total_salary'],
                        'project_id' => isset($input['project_id']) ? $input['project_id'] : NULL,
                        'payment_status' => $input['payment_process_status'],
                        'is_manager_approval' => 'PENDING',
                    ]);
                }

                $objDemo = new \stdClass();
                $objDemo->type = "Salary";
                $objDemo->content = $this->__emailContent($model);
                $objDemo->url = url('/approval/salary');
                Mail::send(new ManagerPaymentNotification($objDemo));
            }

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

    public function project() {
        $data =  Project::select('id', 'name')->get();

        return response()->json(['data'=>$data], 201);
    }

    public function bonus(Request $request) {
        $data =  Bonus::select('value')
                ->where('rate','<=', $request->rate)
                ->orderBy('rate', 'desc')
                ->first();

        return response()->json(['data'=>$data], 201);
    }

    private function __emailContent($data) {
        $project = "-";
        if ($data->project_id) {
            $project = Project::select('name')->where('id', $data->project_id)->first();
        }
        $employee = Employee::select('nik','name','status','location')->where('id', $data->employee_id)->first();
        $is_manager = "true";
        if ((int)$data->total_salary <= 1000000) {
            $is_manager="false";
        }
        $content = [
            "<td>NIK</td><td>: " . $employee->nik . "</td>",
            "<td>Name</td><td>: " . $employee->name . "</td>",
            "<td>Status</td><td>: " . $employee->status . "</td>",
            "<td>Project</td><td>: " . $project->name . "</td>",
            "<td>Work Day</td><td>: " . $data->work_day . "</td>",
            "<td>Over Time (Day)</td><td>: " . $data->over_time_day . "</td>",
            "<td>Over Time (Hour)</td><td>: " . $data->over_time_hour . "</td>",
            "<td>Meal Allowance</td><td>: " . $data->meal_allowance . "</td>",
            "<td>Bonus</td><td>: " . $data->bonus . "</td>",
            "<td>Cashbon</td><td>: " . $data->cashbon . "</td>",
            "<td>Total Salary</td><td>: " . number_format($data->total_salary, 0, '.', '.') . "</td>",
            "<td>Dokumen terkait</td><td>: " . $data->upload . "</td>",
            "is_manager:".$is_manager,
        ];

        return $content;
    }
}
