<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Employee::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.employee.main');
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
        $validatedData = $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'address' => 'required',
            'religion' => 'required',
            'education' => 'required',
            'location' => 'required',
            'start_date' => 'required',
            'status' => 'required',
        ]);
        try {
            $input = $request->all();
            $model = new Employee;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/employee')->with('message', 'Success!');
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
