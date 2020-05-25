<?php

namespace App\Http\Controllers;

use App\Family;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $id = $request->employee_id;
            $data = Family::where('employee_id', $id)->orderBy('birth_date', 'asc');

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
            $model = new Family;
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
     * @param  \App\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function template($id, Family $family)
    {
        $data['relation_type'] = ['children','husband','wife'];
        $data['employee_id'] = $id;

        return view('pages.employee.family', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Family $family, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = $family->find($id);
                $model->delete();

            }
        } catch (Exception $e ) {

            return back()->withError($e->getMessage());
        }

        return response()->json(array('success'=>true));
    }
}
