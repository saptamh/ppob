<?php

namespace App\Http\Controllers;

use App\EmployeeWorkExperience;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class EmployeeWorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $id = $request->employee_id;
            $data = EmployeeWorkExperience::where('employee_id', $id);

            return DataTables::of($data)->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $model = new EmployeeWorkExperience;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return response()->json(array('success'=>true));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmployeeWorkExperience  $employeeWorkExperience
     * @return \Illuminate\Http\Response
     */
    public function template($id, EmployeeWorkExperience $employeeWorkExperience)
    {
        $data['employee_id'] = $id;

        return view('pages.employee.experience', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeWorkExperience  $employeeWorkExperience
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeWorkExperience $employeeWorkExperience)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeWorkExperience  $employeeWorkExperience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeWorkExperience $employeeWorkExperience)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeWorkExperience  $employeeWorkExperience
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, EmployeeWorkExperience $employeeWorkExperience, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $employeeWorkExperience->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
