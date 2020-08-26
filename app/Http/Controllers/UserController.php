<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class UserController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
         return view('pages.user.main');
    }

    public function dataGrid(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/user', [
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
            $request= $client->request('POST', config('app.api') . '/user/profile', [
                'form_params' => [
                    'id' => $request->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'is_admin' => isset($request->is_admin) ? 1 : 0,
                    'nik' => $request->nik,
                    'address' => $request->address,
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
            $url = config('app.api').'/user/destroy/' . $request->id;
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

    public function loginVerify(Request $request) {
        $client = new Client();
        try {
            $request= $client->request('POST', config('app.api') . '/v1/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('app.client_id'),
                    'client_secret' => config('app.client_key'),
                    'password' => $request->password,
                    'username' => $request->username
                ]
            ]);

            $response = $request->getBody()->getContents();

            \Session::push('user_profile', $response);

            return redirect()->route('home');
        } catch(\Exception $ex) {
            dd('error ,' . $ex);
        }
    }

    function logout() {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        $url = config('app.api').'/user/logout';
        try {
            $res = $client->request('POST',$url, [
                'form_params' => [],
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
            ]);
            $response = $res->getBody();
        } catch(\Exception $e) {
            $response = $e->getResponse();
        }
        \Session::forget('user_profile');
        return redirect()->route('home');
    }
}
