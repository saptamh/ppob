<?php

namespace App\Http\Controllers;

use App\ProjectJob;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class ProjectJobController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:projectJob-list', ['only' => ['index']]);
         $this->middleware('permission:projectJob-create', ['only' => ['create','store']]);
         $this->middleware('permission:projectJob-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:projectJob-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = ProjectJob::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.project-job.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.project-job.add');
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
            'name' => 'required',
        ]);
        try {
            $input = $request->all();
            $model = new ProjectJob;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/project-job')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectJob  $project_job
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ProjectJob $project_job)
    {
        $data['edit'] = $project_job->where('id', $id)->first();

        return view('pages.project-job.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectJob  $project_job
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectJob $project_job, Request $request)
    {
        if ($request->ajax()) {
            $model = $project_job->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
