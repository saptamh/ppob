<?php

namespace App\Http\Controllers;

use App\ProjectZone;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class ProjectZoneController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:projectZone-list', ['only' => ['index']]);
         $this->middleware('permission:projectZone-create', ['only' => ['create','store']]);
         $this->middleware('permission:projectZone-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:projectZone-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = projectZone::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.project-zone.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.project-zone.add');
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
            $model = new projectZone;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/project-zone')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\projectZone  $project_zone
     * @return \Illuminate\Http\Response
     */
    public function edit($id, projectZone $project_zone)
    {
        $data['edit'] = $project_zone->where('id', $id)->first();

        return view('pages.project-zone.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\projectZone  $project_zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, projectZone $project_zone, Request $request)
    {
        if ($request->ajax()) {
            $model = $project_zone->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
