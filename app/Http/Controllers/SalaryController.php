<?php

namespace App\Http\Controllers;

use App\Salary;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class SalaryController extends Controller
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
            $data = Salary::where('employee_id', $id)->orderBy('start_date', 'asc');

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
            $model = new Salary;
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
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function template($id, Salary $salary)
    {
        $data['employee_id'] = $id;

        return view('pages.employee.salary', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Salary $salary, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $salary->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
