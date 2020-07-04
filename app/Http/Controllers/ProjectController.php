<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;


class ProjectController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:project-list', ['only' => ['index']]);
         $this->middleware('permission:project-create', ['only' => ['create','store']]);
         $this->middleware('permission:project-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:project-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Project::with(['ProjectValue' => function($query) {
                $query->select('project_id','value')->orderBy('updated_at', 'desc')->first();
            }])
            ->with(['ProjectProgress' => function($query) {
                $query->selectRaw("project_id,SUM(progress) AS total_progress,SUM(result) AS total_result")->groupBy('project_id')->first();
            }])
            ->with(['ProjectHistorical' => function($query) {
                $query->select('project_id','duration','retention')->orderBy('created_at', 'desc')->first();
            }])
            ->select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.project.main');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.project.add');
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
            'no_contract' => 'required',
            'name' => 'required',
            'address' => 'required',
            'customer' => 'required',
            'pic_customer' => 'required',
            'work_type' => 'required',
        ]);
        try {
            $input = $request->all();
            $model = new Project;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/project')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Project $project)
    {
        $data['edit'] = $project->where('id', $id)->first();

        return view('pages.project.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Project $project, Request $request)
    {
        if ($request->ajax()) {
            $model = $project->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
