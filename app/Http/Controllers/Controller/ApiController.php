<?php

namespace App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{

    protected $etc_api_login, $tps_api_login;

    protected $etc_api_info, $tps_api_info;

    protected $etc_organization_code, $tps_organization_code;

    public function __construct()
    {

        $this->etc_organization_code = env('ETC_ORGANIZATION_CODE');
        $this->etc_api_login = env('ETC_API_LOGIN');
        $this->etc_api_info = env('ETC_API_INFO');


        $this->tps_organization_code = env('TPS_ORGANIZATION_CODE');
        $this->tps_api_login = env('TPS_API_INFO');
        $this->tps_api_info = env('TPS_API_INFO');

    }


    public function login()
    {
        try {

            $response = Http::asForm()->post($this->etc_api_login, [
                'p_Organization_Code' => $this->etc_organization_code,
                'p_Login' => 310022694,
                'p_Password' => 310022694,
            ]);

            return $response;
        }
        catch (\Exception $exception) {
            return response($exception->getMessage());
        }
    }

    public function info($login, $password, $code)
    {
        try {

            $response = Http::post($this->etc_api_info, [
                'p_Organization_Code' => $this->etc_organization_code,
                'p_Login' => 310022694,
                'p_Password' => 310022694,
            ]);

            return $response;

        }
        catch (\Exception $exception) {
            return response($exception->getMessage());
        }
    }


}
