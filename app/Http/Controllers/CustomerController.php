<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class CustomerController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
         return view('pages.customer.main');
    }

    public function dataGrid(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/customer', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' =>  $request->rows,
                    'page' => $request->page
                ]
            ]);

            $response = $request->getBody()->getContents();
            return $response;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function store(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request= $client->request('POST', config('app.api') . '/customer/', [
                'form_params' => [
                    'id' => $request->id,
                    'nik' => $request->nik,
                    'name' => $request->name,
                    'address' => $request->address,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'village_id' => $request->village_id
                ],
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
            ]);

            $response = $request->getBody()->getContents();
            return $response;
        } catch(\Exception $ex) {
            dd('error ,' . $ex);
        }
    }

    public function destroy(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $url = config('app.api').'/customer/' . $request->id;
            $request = $client->request('DELETE', $url,[
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
            ]);
            $response = $request->getBody()->getContents();
            return $response;
        } catch(\Exception $ex) {
            dd('error ,' . $ex);
        }
    }

    public function vehicle(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/customer/vehicles', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' =>  $request->rows,
                    'page' => $request->page,
                    'nik' => $request->nik
                ]
            ]);

            $response = $request->getBody()->getContents();
            return $response;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
}
