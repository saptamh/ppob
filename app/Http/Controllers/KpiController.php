<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Kpi;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:kpi-list', ['only' => ['index']]);
         $this->middleware('permission:kpi-create', ['only' => ['create','store']]);
         $this->middleware('permission:kpi-edit', ['only' => ['edit','store']]);
         $this->middleware('permission:kpi-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Kpi::select('*')
            ->with('Employee');

           return DataTables::of($data)->make(true);
        } else {
            return view('pages.kpi.main');
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

        return view('pages.kpi.add', $data);
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
            'date' => 'required',
            'employee_id' => 'required',
            'job_percentage' => 'required|numeric',
            'quality_percentage' => 'required|numeric',
            'attitude_percentage' => 'required|numeric'
        ]);

        try {
            $input = $request->all();
            $date = $this->__validateDate($input['date']);
            $input['start_date'] = $date[0];
            $input['end_date'] = $date[1];
            $input['result'] = ceil(($input['job_percentage'] + $input['quality_percentage'] + $input['attitude_percentage']) / 3);
            $model = new Kpi;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            }

            $model->fill($input);
            $save = $model->save();

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/kpi')->with('message', 'Success!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kpi  $kpi
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Kpi $kpi)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['edit'] = $kpi->where('id', $id)->first();
        $data['edit']['date'] = $this->_reverseDate($data['edit']['start_date'], $data['edit']['end_date']);

        return view('pages.kpi.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kpi  $kpi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Kpi $kpi, Request $request)
    {
        if ($request->ajax()) {
            $model = $kpi->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    public function kpiEmployee(Request $request) {
        if($request->ajax()){
            $data = [];
            if ($request->employee_id) {
                $data = Kpi::select('*')->where('employee_id', $request->employee_id);
            }
           return DataTables::of($data)->make(true);
        }
    }

    private function __getDropdown() {
        $data['employees'] = Employee::select('id','name')->where('status', 'phl')->get()->pluck('name', 'id');

        return $data;
    }

    private function __validateDate($date) {
        $explode_date = explode(" - ", $date);
        $data = [];
        foreach($explode_date as $date) {
            $data[] = date("Y-m-d", strtotime(str_replace(["/", " "],["-", ""], $date)));
        }

        return $data;
    }

    private function _reverseDate($start_date, $end_date) {
        $start_date = date("d/m/Y", strtotime($start_date));
        $end_date = date("d/m/Y", strtotime($end_date));

        return $start_date . ' - ' . $end_date;
    }
}
