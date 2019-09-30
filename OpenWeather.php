<?php


class OpenWeather
{
    private $appkey;


    public function __construct(string $appkey)
    {
        $this->appkey = $appkey;
    }

    /**
     * @param string $city
     * @return array
     */
    public function getForeCast(string $city)
    {
        $endpoint = "forecast/daily?q=$city";

        $curl = $this->callApi($endpoint);

        if ($curl['err']) {
            die("cURL Error #:" . $curl['err']);
        }

        $data = $curl['data'];

        if($data['message']){

            die($data['message']);

        }else {

            $response = [];
            foreach ($data['list'] as $day) {
                $response[] = [
                    'temp' => $day['temp']['day'],
                    'description' => $day['weather'][0]['description'],
                    'main' => $day['weather'][0]['main'],
                    'date' => date('d/m/Y', $day['dt']),
                ];
            }
            return $response;
        }
    }

    /**
     * @param string $city
     * @return array
     */
    public function getToDay(string $city) {

        $endpoint = "weather?q=$city";

        $curl = $this->callApi($endpoint);


        if ($curl['err']) {
             die("cURL Error #:" . $curl['err']);
        }

        $data = $curl['data'];

        if($data['message']){

            die($data['message']);

        }else{

            return  [
                'temp' => $data['main']['temp'],
                'description' => $data['weather'][0]['description'],
                'image' => $data['weather'][0]['icon'],
                'city' => $data['name'],
                'country' => $data['sys']['country'],
                'date' => date('d/m/Y à H:i:s', $data['dt']),
            ];
        }

    }

    /**
     * @param string $endpoint
     * @return array|null
     */
    private function callApi(string $endpoint): ?array{

        $endpoint = "api.openweathermap.org/data/2.5/{$endpoint}&appid={$this->appkey}&units=metric&lang=fr";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER =>false,
            //CURLOPT_CAINFO => dirname(__DIR__).DIRECTORY_SEPARATOR."cert.cer",
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $data = json_decode(curl_exec($curl), true);
        $err = curl_error($curl);

        curl_close($curl);

        return ['data' => $data, 'err' => $err];
    }
}