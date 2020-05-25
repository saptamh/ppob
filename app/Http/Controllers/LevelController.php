<?php

namespace App\Http\Controllers;

use App\Level;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class LevelController extends Controller
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
            $data = Level::where('employee_id', $id)->orderBy('start_date', 'asc');

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
            $model = new Level;
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
     * @param  \App\Level  $salary
     * @return \Illuminate\Http\Response
     */
    public function template($id, Level $level)
    {
        $data['employee_id'] = $id;

        return view('pages.employee.level', $data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Level $level, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $level->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
