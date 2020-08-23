<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class TransactionController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function latePay(Request $request)
    {
         return view('pages.transaction.late-pay');
    }

    public function latePayDataGrid(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/transaction/late-pay', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' =>  $request->rows,
                    'page' => $request->page,
                    'customer_name' => $request->user,
                    'plate_number' => $request->plate_number
                ]
            ]);

            $response = $request->getBody()->getContents();
            return $response;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
}
