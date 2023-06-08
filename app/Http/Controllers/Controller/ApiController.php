<?php

namespace App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{

    protected $etc_api_login, $tps_api_login;

    protected $etc_api_info, $tps_api_info;

    protected $etc_organization_code, $tps_organization_code;


    use CacheTrait;

    public function __construct()
    {

        $this->etc_organization_code = env('ETC_ORGANIZATION_CODE');
        $this->etc_api_login = env('ETC_API_LOGIN');
        $this->etc_api_info = env('ETC_API_INFO');

        $this->tps_organization_code = env('TPS_ORGANIZATION_CODE');
        $this->tps_api_login = env('TPS_API_INFO');
        $this->tps_api_info = env('TPS_API_INFO');

    }

    protected function api_response($login = 310022694, $password = 'password')
    {
        return Http::asForm()->post($this->etc_api_login, [
            'p_Organization_Code' => $this->etc_organization_code,
            'p_Login' => $login, // 310022694,
            'p_Password' => $password // 310022694,
        ]);
    }

    public function check_login($login)
    {
        $response = $this->api_response($login);

        if ($response->ok())
        {
            $error_text = $this->cut_text($response);
            $subject_id = $this->cut_subjectId($response);

            if ($error_text == "Логин не найден")
                return [
                    'status' => false,
                    'error_text' => $error_text,
                ];

            return [
                'status' => true,
                'error_text' => $error_text,
                'subject_id' => $subject_id,
            ];
        }
        return [ 'status' => false ];
    }


    public function login($login, $password)
    {
        $response = $this->api_response($login, $password);

        if ($response->ok())
        {
            $error_text = $this->cut_text($response);
            $subject_id = $this->cut_subjectId($response);
            $rate = $this->cut_rate($response);

            if ($error_text == "Пароль неверный")
                return [
                    'status' => false,
                    'error_text' => $error_text,
                ];

            return [
                'status' => true,
                'error_text' => $error_text,
                'subject_id' => $subject_id,
                'rate' => $rate,
            ];
        }

    }


    public function cut_text($response)
    {
        $pattern = "/<o_Out_Text>(.*?)<\/o_Out_Text>/";
        preg_match($pattern, $response, $matches);

        return $matches[1];
    }

    public function cut_subjectId($response)
    {
        $pattern = '/<o_Subject_Id>\s*([A-Z0-9]+)/i';
        preg_match($pattern, $response, $matches);

        return $matches[1];
    }

    public function cut_rate($response)
    {
        $pattern = '/<o_Rate>(.*?)<\/o_Rate>/';
        preg_match($pattern, $response, $matches);
        $rate = trim(preg_replace('/[^0-9.]/', '', $matches[1]));

        return $rate;
    }


    public function info($login, $password, $subject_id)
    {
        try {

            $response = Http::asForm()->post($this->etc_api_info, [
                'p_Organization_Code' => $this->etc_organization_code,
                'p_Login' => $login, // 310022694,
                'p_Password' => $password,
                'p_Subject_Id' => $subject_id
            ]);

            if ($response->ok())
                return $this->cut_xml($response);
            else
                return false;
        }
        catch (\Exception $exception) {
            return response($exception->getMessage());
        }
    }


    public function cut_xml($xml)
    {

        preg_match("/<full_name>(.*?)<\/full_name>/", $xml, $matches2);
        $full_name = $matches2[1];

        preg_match('/<address>(.*?)<\/address>/', $xml, $matches2);
        $address = $matches2[1];

        $pattern = '/<tariff_code>(.*?)<\/tariff_code>/';
        preg_match($pattern, $xml, $matches2);
        $tariff_code = $matches2[1];

        $pattern = '/<status>(.*?)<\/status>/';
        preg_match($pattern, $xml, $matches2);
        $status = $matches2[1];


        return [
            'status' => true,
            'full_name' => $full_name,
            'address' => $address,
            'tariff_code' => $tariff_code,
            'tariff_status' => $status
        ];

    }


}
