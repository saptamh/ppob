<?php

namespace App\Http\Controllers;

use App\ProjectTimeline;
use App\Project;
use App\ProjectItem;
use App\ProjectJob;
use App\ProjectZone;
use App\Employee;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class ProjectTimelineController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:projectTimeline-list', ['only' => ['index']]);
         $this->middleware('permission:projectTimeline-create', ['only' => ['create','store']]);
         $this->middleware('permission:projectTimeline-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:projectTimeline-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = ProjectTimeline::select('*')
                    ->with('Manager')
                    ->with('Project')
                    ->with('ProjectItem')
                    ->with('ProjectJob')
                    ->with('ProjectZone')
                    ->with(['ProjectDaily' => function($query) {
                        $query->selectRaw("project_timeline_id, SUM(realisation) AS total_realisation")->groupBy('project_timeline_id');
                    }]);
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.project-timeline.main');
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

        return view('pages.project-timeline.add', $data);
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
            'projects_id' => 'required',
            'manager_id' => 'required',
            'type' => 'required',
            'date' => 'required|date',
            'project_item_id' => 'required',
            'project_job_id' => 'required',
            'project_zone_id' => 'required',
            'qty' => 'required|numeric',
            'duration' => 'required|numeric',
        ]);
        try {
            $input = $request->all();
            $model = new ProjectTimeline;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/project-timeline')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectTimeline  $project_timeline
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ProjectTimeline $project_timeline)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['edit'] = $project_timeline->where('id', $id)->first();

        return view('pages.project-timeline.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectTimeline  $project_timeline
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectTimeline $project_timeline, Request $request)
    {
        if ($request->ajax()) {
            $model = $project_timeline->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    private function __getDropdown() {
        $data['type'] = ['PERSIAPAN', 'ONGOING', 'MAINTENANCE'];
        $data['projects'] = Project::select('id','name')->get()->pluck('name','id');
        $data['project_items'] = ProjectItem::select('id','name')->get()->pluck('name','id');
        $data['project_jobs'] = ProjectJob::select('id','name')->get()->pluck('name','id');
        $data['project_zones'] = ProjectZone::select('id','name')->get()->pluck('name','id');
        $data['manager'] = Employee::select('id','name')->whereHas('Level', function($q){
            $q ->where('level', 'like', '%manager%');
        })->get()->pluck('name','id');

        return $data;
    }
}
