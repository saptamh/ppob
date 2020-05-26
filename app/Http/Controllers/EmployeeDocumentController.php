<?php

namespace App\Http\Controllers;

use App\EmployeeDocument;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Str;

class EmployeeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EmployeeDocument $employee_document)
    {
        if($request->ajax()){
            $id = $request->employee_id;
            $data = $employee_document->where('employee_id', $id)->orderBy('created_at', 'asc');

            return DataTables::of($data)->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/employee/document', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
                $size = Cloudder::getResult()['bytes'];
                $type = $document->getClientOriginalExtension();
            }

            $input = $request->all();
            $input['path'] = $document_name;
            $input['size'] = $size;
            $input['type'] = $type;
            $model = new EmployeeDocument;
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
     * @param  \App\EmployeeDocument  $employee_document
     * @return \Illuminate\Http\Response
     */
    public function template($id, EmployeeDocument $employee_document)
    {
        $data['employee_id'] = $id;

        return view('pages.employee.document', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, EmployeeDocument $employeeDocument, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $employeeDocument->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
