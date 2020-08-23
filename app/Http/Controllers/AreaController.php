<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class AreaController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexProvince(Request $request)
    {
         return view('pages.area.province');
    }

    public function comboProvince(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/province', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' => 100,
                    'page' => 1
                ]
            ]);

            $response = $request->getBody();
            $decode = json_decode($request->getBody(), true);
            return json_encode($decode['rows']);
        } catch (\Exception $ex) {
            dd('Error ,' . $ex);
        }
    }

    public function dataGridProvince(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/province', [
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
            dd('Error ,' . $ex);
        }
    }

    public function storeProvince(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request= $client->request('POST', config('app.api') . '/area/province/store', [
                'form_params' => [
                    'id' => $request->id,
                    'name' => $request->name
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

    public function destroyProvince(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $url = config('app.api').'/area/province/destroy/' . $request->id;
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

    public function indexCity(Request $request)
    {
         return view('pages.area.city');
    }

    public function comboCity(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/city', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' => 200,
                    'page' => 1,
                    'province' => $request->province
                ]
            ]);

            $response = $request->getBody();
            $decode = json_decode($request->getBody(), true);
            return json_encode($decode['rows']);
        } catch (\Exception $ex) {
            dd('Error ,' . $ex);
        }
    }

    public function dataGridCity(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/city', [
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
            dd('Error ,' . $ex);
        }
    }

    public function storeCity(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request= $client->request('POST', config('app.api') . '/area/city/store', [
                'form_params' => [
                    'id' => $request->id,
                    'name' => $request->name,
                    'province_id' => $request->province_id
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

    public function destroyCity(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $url = config('app.api').'/area/city/destroy/' . $request->id;
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

    public function indexDistrict(Request $request)
    {
         return view('pages.area.district');
    }

    public function comboDistrict(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/district', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' => 200,
                    'page' => 1,
                    'city' => $request->city
                ]
            ]);

            $response = $request->getBody();
            $decode = json_decode($request->getBody(), true);
            return json_encode($decode['rows']);
        } catch (\Exception $ex) {
            dd('Error ,' . $ex);
        }
    }

    public function dataGridDistrict(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/district', [
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
            dd('Error ,' . $ex);
        }
    }

    public function storeDistrict(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request= $client->request('POST', config('app.api') . '/area/district/store', [
                'form_params' => [
                    'id' => $request->id,
                    'name' => $request->name,
                    'city_id' => $request->city_id,
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

    public function destroyDistrict(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $url = config('app.api').'/area/district/destroy/' . $request->id;
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

    public function indexVillage(Request $request)
    {
         return view('pages.area.village');
    }

    public function comboVillage(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/village', [
                'headers' => [
                    'Authorization' => 'Bearer '. $session['access_token'],
                ],
                'query' => [
                    'rows' => 200,
                    'page' => 1,
                    'name' => $request->q
                ]
            ]);

            $response = $request->getBody();
            $decode = json_decode($request->getBody(), true);
            return json_encode($decode['rows']);
        } catch (\Exception $ex) {
            dd('Error ,' . $ex);
        }
    }

    public function dataGridVillage(Request $request)
    {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request = $client->request('GET', config('app.api').'/area/village', [
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
            dd('Error ,' . $ex);
        }
    }

    public function storeVillage(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $request= $client->request('POST', config('app.api') . '/area/village/store', [
                'form_params' => [
                    'id' => $request->id,
                    'name' => $request->name,
                    'district_id' => $request->district_id,
                    'rt' => $request->rt,
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

    public function destroyVillage(Request $request) {
        $client = new Client();
        $session = json_decode(\Session::get('user_profile')[0], true);

        try {
            $url = config('app.api').'/area/village/destroy/' . $request->id;
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
}
