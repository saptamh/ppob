<?php

namespace App\Http\Controllers;

use App\ProjectDocument;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Str;

class ProjectDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProjectDocument $projectDocument)
    {
        if($request->ajax()){
            $id = $request->project_id;
            $data = $projectDocument->where('project_id', $id)->orderBy('created_at', 'asc');

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
            ini_set('memory_limit','10240M');
            $document_name = "";
            $size = 0;
            $type = "";
            if ($request->hasFile('path')) {
                $document = $request->file('path');
                $document_name = Str::kebab($request->name).'-'.time().'.'.$document->getClientOriginalExtension();
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/project/document', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
                $size = Cloudder::getResult()['bytes'];
                $type = $document->getClientOriginalExtension();
            }

            $input = $request->all();
            $input['path'] = $document_name;
            $input['size'] = $size;
            $input['type'] = $type;
            $model = new ProjectDocument;
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
     * @param  \App\ProjectDocument  $projectDocument
     * @return \Illuminate\Http\Response
     */
    public function template($id, ProjectDocument $projectDocument)
    {
        $data['project_id'] = $id;

        return view('pages.project.document', $data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectDocument  $projectDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ProjectDocument $projectDocument, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $projectDocument->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
