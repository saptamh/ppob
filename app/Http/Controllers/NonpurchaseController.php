<?php

namespace App\Http\Controllers;

use App\Nonpurchase;
use App\Project;
use App\Payment;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use JD\Cloudder\Facades\Cloudder;
use PaymentHelp;

class NonpurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Nonpurchase::select('*')
            ->with('Project');

           return DataTables::of($data)->make(true);
        } else {
            return view('pages.nonpurchase.main');
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

        return view('pages.nonpurchase.add', $data);
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
            'type_object' => 'required',
            'date' => 'required',
            'type' => 'required',
            'payment' => 'required',
            'nominal' => 'required',
            'description' => 'required',
        ]);

        $document_name = "";
        if ($request->hasFile('upload')) {
                $document = $request->file('upload');
                $document_name = $request->id . '-'.time().'.'.$document->getClientOriginalExtension();
                Cloudder::upload($document, $document_name,['folder'=>'REKAKOMINDO/patty_cash', 'resource_type' => 'auto', 'use_filename' => TRUE]);
                $document_name = Cloudder::getResult()['url'];
        } else {
            if (isset($request->upload_hidden) && !empty($request->upload_hidden)) {
                $document_name = $request->upload_hidden;
            }
        }

        try {
            $input = $request->all();
            $input['upload'] = $document_name;
            $input['payment_process_status'] = "PENDING";
            $model = new Nonpurchase;
            if (isset($input['id'])) {
                $model = $model::find($input['id']);
            } else {
                $input['number'] = PaymentHelp::generateCodeNonPurchase();
            }
            $model->fill($input);
            $save = $model->save();

            if ($save) {
                $type = [
                    'Overhead',
                    'Marketing',
                    'Plemary',
                    'Rumah Tangga',
                    'Lainnya',
                ];
                if (!isset($input['id'])) {
                    $dataToPayment = [
                        'payment_name' => $type[$input['type'] - 1] . ", " . $input['payment'],
                        'payment_type' => 'nonpurchase',
                        'payment_total' => $input['nominal'],
                        'payment_id' => $model->id,
                        'project_id' => isset($input['project_id']) ? $input['project_id'] : NULL,
                    ];
                    PaymentHelp::savePaymentPartial($dataToPayment);
                } else {
                    Payment::where('payment_type', 'nonpurchase')
                    ->where('payment_id', $input['id'])
                    ->update([
                        'payment_total'=> $input['nominal'],
                        'project_id' => isset($input['project_id']) ? $input['project_id'] : NULL,
                        'payment_name' => $type[$input['type'] - 1] . ", " . $input['payment'],
                        'payment_status' => $input['payment_process_status'],
                    ]);
                }
            }

        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return redirect('/nonpurchase')->with('message', 'Success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nonpurchase  $nonpurchase
     * @return \Illuminate\Http\Response
     */
    public function show(Nonpurchase $nonpurchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nonpurchase  $nonpurchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Nonpurchase $nonpurchase)
    {
        $data['select_box'] = $this->__getDropdown();
        $data['edit'] = $nonpurchase->where('id', $id)->first();

        return view('pages.nonpurchase.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nonpurchase  $nonpurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nonpurchase $nonpurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nonpurchase  $nonpurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Nonpurchase $nonpurchase, Request $request)
    {
        if ($request->ajax()) {
            $model = $nonpurchase->find($id);
            $model->delete();
            $data = [];
            $data['success'] = 'Record is successfully deleted';

            return $data;
        }
    }

    private function __getDropdown() {
        $data['object'] = ['OFFICE', 'PROJECT'];
        $data['type'] = [
            '1' => 'Overhead',
            '2' => 'Marketing',
            '3' => 'Plemary',
            '4' => 'Rumah Tangga',
            '5' => 'Other',
        ];

        return $data;
    }
}
