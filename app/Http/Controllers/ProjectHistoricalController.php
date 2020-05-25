<?php

namespace App\Http\Controllers;

use App\ProjectHistorical;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProjectHistoricalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $id = $request->project_id;
            $data = ProjectHistorical::where('project_id', $id)->orderBy('date', 'asc');

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
            $model = new ProjectHistorical;
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
     * @param  \App\ProjectHistorical  $salary
     * @return \Illuminate\Http\Response
     */
    public function template($id, ProjectHistorical $project_historical)
    {
        $data['project_id'] = $id;

        return view('pages.project.log_project', $data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectHistorical $project_historical, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $project_historical->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
