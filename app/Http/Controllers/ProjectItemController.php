<?php

namespace App\Http\Controllers;

use App\ProjectItem;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class ProjectItemController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:projectItem-list', ['only' => ['index']]);
         $this->middleware('permission:projectItem-create', ['only' => ['create','store']]);
         $this->middleware('permission:projectItem-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:projectItem-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = ProjectItem::select('*');
           return DataTables::of($data)->make(true);
        } else {
            return view('pages.project-item.main');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.project-item.add');
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
            $model = new ProjectItem;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }
            $model->fill($input);
            $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/project-item')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectItem  $project_item
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ProjectItem $project_item)
    {
        $data['edit'] = $project_item->where('id', $id)->first();

        return view('pages.project-item.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectItem  $project_item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectItem $project_item, Request $request)
    {
        if ($request->ajax()) {
            $model = $project_item->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }
}
