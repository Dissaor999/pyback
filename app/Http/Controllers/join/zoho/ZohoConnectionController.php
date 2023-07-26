<?php

namespace App\Http\Controllers\join\zoho;

use App\Http\Controllers\Controller;
use App\Models\Key;
use GuzzleHttp\Client;


class ZohoConnectionController extends Controller
{
    public function __construct()
    {
        $this->client = Key::where([['channel', 'zoho'], ['key', 'client_id']])->first()->value;
        $this->secret = Key::where([['channel', 'zoho'], ['key', 'secret_id']])->first()->value;
        $this->auth = Key::where([['channel', 'zoho'], ['key', 'oAuth']])->first()->value;
        $this->refresh = Key::where([['channel', 'zoho'], ['key', 'refresh']])->first()->value;
    }

    public function codeResponse()
    {
        $code = $_GET['code'];
        $auth = $this->zohoAuthCode($code);
        return $auth;
    }

    public function zohoAuthCode($code)
    {
        $client = new Client(array('verify' => false));
        $responseJson = $client->request('post', "https://accounts.zoho.com/oauth/v2/token?code=" . $code . "&client_id=" . $this->client . "&client_secret=" . $this->secret . "&redirect_uri=https://back.hermesmx.ml/zohoCode&grant_type=authorization_code")
            ->getBody()->getContents();
        $response = json_decode($responseJson);
        $oAuthToken = $response->access_token;
        $refresh = isset($response->refresh_token) ? $response->refresh_token : $response->access_token;
        Key::where([['channel', 'zoho'], ['key', 'oAuth']])->update(['value' => $oAuthToken]);
        Key::where([['channel', 'zoho'], ['key', 'refresh']])->update(['value' => $refresh]);
        return response()->json($response);
    }

    public function getCodezoho()
    {
        $url = "https://accounts.zoho.com/oauth/v2/auth?scope=ZohoInventory.FullAccess.all&client_id=" . $this->client . "&response_type=code&redirect_uri=https://back.hermesmx.ml/zohoCode&access_type=offline";
        return redirect()->to($url);
    }

    public function refreshTokenZoho()
    {
        $client = new Client(array('verify' => false));
        $responseJson = $client->request('post', "https://accounts.zoho.com/oauth/v2/token?refresh_token=" . $this->refresh . "&client_id=" . $this->client . "&client_secret=" . $this->secret . "&redirect_uri=https://inventory.zoho.com&grant_type=refresh_token")
            ->getBody()->getContents();
        $response = json_decode($responseJson);
        // $oAuthToken = $response->access_token;
        // key::where([['channel', 'zoho'], ['key', 'oAuth']])->update(['value' => $oAuthToken]);
        return $response;
    }
}
