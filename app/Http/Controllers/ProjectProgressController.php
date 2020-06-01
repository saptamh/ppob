<?php

namespace App\Http\Controllers;

use App\ProjectProgress;
use App\ProjectValue;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProjectProgressController extends Controller
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
            $data = ProjectProgress::with('ProjectValue')->where('project_id', $id)->orderBy('created_at', 'asc');

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
            $value = ProjectValue::select('id','value')->where('id', $input['project_value_id'])->first();
            $input['result'] = ($input['progress'] * $value->value) / 100;
            $model = new ProjectProgress;
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
     * @param  \App\ProjectProgress  $projectProgress
     * @return \Illuminate\Http\Response
     */
    public function template($id, ProjectProgress $projectProgress)
    {
        $data['project_id'] = $id;
        $data['values'] = ProjectValue::selectRaw("id, FORMAT(value, 0) AS new_value")->orderBy('created_at', 'asc')->get()->pluck('new_value','id');

        return view('pages.project.progress', $data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectProgress  $projectProgress
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectProgress $projectProgress, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $projectProgress->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
