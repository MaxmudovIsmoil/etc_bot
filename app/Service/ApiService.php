<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected ?string $etc_api_login;
    protected ?string $etc_api_info;
    protected ?string $etc_organization_code;

    public function __construct()
    {
        $this->etc_organization_code = env('ETC_ORGANIZATION_CODE');
        $this->etc_api_login = env('ETC_API_LOGIN');
        $this->etc_api_info = env('ETC_API_INFO');
    }

    protected function login($username, $password = null): object
    {
        return Http::asForm()->post($this->etc_api_login, [
            'p_Organization_Code' => $this->etc_organization_code,
            'p_Login' => $username, // 310022694,
            'p_Password' => $password // 310022694,
        ]);
    }

    public function check_username($username): array
    {
        $response = $this->login($username);

        if ($response->ok())
        {
            $error_text = $this->cut_text($response);

            if ($error_text == "Логин не найден")
                return [
                    'status' => false,
                    'error_text' => $error_text,
                ];

            return [
                'status' => true,
                'error_text' => $error_text,
            ];
        }
        return [ 'status' => false, 'error_text' => 'Логин не найден.'];
    }


    public function check_password($username, $password): array
    {
        $response = $this->login($username, $password);

        if ($response->ok())
        {
            $error_text = $this->cut_text($response);

            if ($error_text == "Пароль неверный")
                return [
                    'status' => false,
                    'error_text' => $error_text,
                ];

            return [
                'status' => true,
                'error_text' => $error_text,
                'subject_id' => $this->subject_id($response)
            ];
        }
        return [ 'status' => false, 'error_text' => 'Пароль неверный.'];
    }

    public function cut_text($response): string
    {
        preg_match("/<o_Out_Text>(.*?)<\/o_Out_Text>/", $response, $matches);
        return $matches[1];
    }

    public function subject_id($response): int
    {
        preg_match('/<o_Subject_Id>\s*([A-Z0-9]+)/i', $response, $subject_id);
        return $subject_id[1];
    }


    public function info($username, $password, $subject_id): array
    {
        try {
            $response = Http::asForm()->post($this->etc_api_info, [
                'p_Organization_Code' => $this->etc_organization_code,
                'p_Login' => $username, // 310022694,
                'p_Password' => $password,
                'p_Subject_Id' => $subject_id
            ]);

            if ($response->ok())
                return $this->cut_xml($response);
            else
                return ['status' => false];
        }
        catch (\Exception $exception) {
            return [$exception->getMessage()];
        }
    }


    public function cut_xml($xml): array
    {
        $data = [];
        preg_match("/<full_name>(.*?)<\/full_name>/", $xml, $data['full_name']);

        preg_match('/<address>(.*?)<\/address>/', $xml, $data['address']);

        preg_match_all('/<saldo>(.*?)<\/saldo>/', $xml, $data['balance'], PREG_OFFSET_CAPTURE);

        preg_match('/<tariff_code>(.*?)<\/tariff_code>/', $xml, $data['tariff_code']);

        preg_match_all('/<status>(.*?)<\/status>/', $xml, $data['status'], PREG_OFFSET_CAPTURE);

        return [
            'status' => true,
            'full_name' => $data['full_name'][1],
            'address' => $data['address'][1],
            'balance' => $data['balance'][1][1][0],
            'tariff_code' => $data['tariff_code'][1],
            'tariff_status' => $data['status'][1][1][0]
        ];
    }


}
