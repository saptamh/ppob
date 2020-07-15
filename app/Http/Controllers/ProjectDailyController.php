<?php

namespace App\Http\Controllers;

use App\ProjectTimeline;
use App\ProjectDaily;
use App\Employee;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class ProjectDailyController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:projectDaily-list', ['only' => ['index']]);
         $this->middleware('permission:projectDaily-create', ['only' => ['create','store']]);
         $this->middleware('permission:projectDaily-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:projectDaily-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($timeline_id, Request $request)
    {
        if($request->ajax()){
            $data = ProjectDaily::select('*')
                    ->with('Employee')
                    ->with('ProjectTimeline')
                    ->where('project_timeline_id', $timeline_id);
           return DataTables::of($data)->make(true);
        } else {
            $data['timeline_id'] = $timeline_id;
            $data['timeline'] = ProjectTimeline::select('*')
            ->with(['Manager' => function($query) {
                $query->select('id', 'name');
            }])->with(['Project' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectJob' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectItem' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectZone' => function($query) {
                $query->select('id', 'name');
            }])
            ->where('id', $timeline_id)->first();

            return view('pages.project-daily.main', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($timeline_id)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['timeline'] = ProjectTimeline::select('*')
            ->with(['Manager' => function($query) {
                $query->select('id', 'name');
            }])->with(['Project' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectJob' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectItem' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectZone' => function($query) {
                $query->select('id', 'name');
            }])
            ->where('id', $timeline_id)->first();

        return view('pages.project-daily.add', $data);
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
            'project_timeline_id' => 'required',
            'employee_id' => 'required',
            'job' => 'required',
            'date' => 'required|date',
            'target' => 'required|numeric',
            'realisation' => 'required|numeric',
            'description' => 'required',
            'worked_hour' => 'required',
        ]);
        try {
            $input = $request->all();
            $model = new ProjectDaily;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/project-daily/'.$input['project_timeline_id'])->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectDaily  $project_daily
     * @return \Illuminate\Http\Response
     */
    public function edit($timeline_id, $id, ProjectDaily $project_daily)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['timeline'] = ProjectTimeline::select('*')
            ->with(['Manager' => function($query) {
                $query->select('id', 'name');
            }])->with(['Project' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectJob' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectItem' => function($query) {
                $query->select('id', 'name');
            }])
            ->with(['ProjectZone' => function($query) {
                $query->select('id', 'name');
            }])
            ->where('id', $timeline_id)->first();
        $data['edit'] = $project_daily->where('id', $id)->first();

        return view('pages.project-daily.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectDaily  $project_daily
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectDaily $project_daily, Request $request)
    {
        if ($request->ajax()) {
            $model = $project_daily->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    private function __getDropdown() {
        $data['employee'] = Employee::select('id','name')->where('status', 'phl')->pluck('name','id');

        return $data;
    }
}
