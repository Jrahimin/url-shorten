<?php


namespace App\Http\Traits;


use GuzzleHttp\Client;

trait CheckUrlSafety
{
    public function checkIfSafe($url)
    {
        $data = "{
                'client': {
                'clientId': 'shorturl',
                'clientVersion': '1.1'
               },
            'threatInfo': {
            'threatTypes':      ['MALWARE', 'SOCIAL_ENGINEERING'],
            'platformTypes':    ['WINDOWS'],
            'threatEntryTypes': ['URL'],
            'threatEntries': [{'url': '{$url}'}]
            }
            }";

        $apiUri = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyD309bhGqy_643HNas3HCInwQ5zWVcnoMU";

        $responseData = $this->getApiResponse($apiUri, $data);

        return $responseData == false; //$responseData is [] when safe
    }

    public function getApiResponse($uri, $data)
    {
        $client = new Client(['headers' => [
            "Content-Type" => "application/json"
        ],
            'verify' => false,
        ]);
        $responseData = $client->post($uri,
            ['body' => $data]
        );

        $responseData = json_decode($responseData->getBody(), true);

        return $responseData;
    }
}
