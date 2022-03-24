<?php

namespace App\Services;

use App\Entities\CommonResponseEntity;
use App\Models\UrlRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UrlShortenerService
{
    protected $exceptionMessage;
    protected $exceptionStatus;
    protected $characters;
    public function __construct()
    {
        $this->characters = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
        $this->exceptionMessage = "Something went wrong. Please try again later.";
        $this->exceptionStatus = 500;
    }

    public function shortenUrl(Request $request): CommonResponseEntity{
        $response = new CommonResponseEntity();
        $response->status = 422;
        try {
            $reqUrl = stripslashes(trim($request->url));

            $prevUrl = UrlRequest::where('request_url', $reqUrl)->first();
            if($prevUrl){
                $response->errorMessage = "This URL is already shortend to: $prevUrl->shorten_url";
                return $response;
            }

            $response->data = $this->generateRandomValue();
            $response->status = 200;
            return $response;
        } catch (\Throwable $ex) {
            Log::error(' : Found Exception [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $ex->getFile() . '-' . $ex->getLine() . ']' . $ex->getMessage());
            $response->errorMessage = $this->exceptionMessage;
            $response->status = $this->exceptionStatus;

            return $response;
        }
    }

    public function generateRandomValue()
    {
        $charSet = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $randStr = [];
        for ($i = 0; $i < 6; $i ++)
        {
            $randStr[] = $charSet[rand(0, (strlen($charSet) - 1))];
        }

        $randStrSerial = implode('', $randStr);
        $randStr = str_shuffle($randStrSerial);

        return $randStr;
    }
}
