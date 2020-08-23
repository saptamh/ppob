<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class MappingController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
         return view('pages.mapping.main');
    }

    public function dataGrid(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/mapping/customer', [
               'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' =>  $request->rows,
                    'page' => $request->page,
                    'user' => $request->user
                ]
            ]);

            $response = $request->getBody()->getContents();
            return $response;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
}
