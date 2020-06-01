<?php

namespace App\Http\Controllers;

use App\ProjectValue;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProjectValueController extends Controller
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
            $data = ProjectValue::where('project_id', $id)->orderBy('created_at', 'asc');

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
            $model = new ProjectValue;
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
     * @param  \App\ProjectValue  $projectValue
     * @return \Illuminate\Http\Response
     */
    public function template($id, ProjectValue $projectValue)
    {
        $data['project_id'] = $id;

        return view('pages.project.value', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectValue  $projectValue
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectValue $projectValue, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $projectValue->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
