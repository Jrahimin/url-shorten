<?php

namespace App\Services;

use App\Entities\CommonResponseEntity;
use App\Models\UrlRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UrlShortenerService
{
    public function __construct()
    {
        $this->exceptionMessage = "Something went wrong. Please try again later.";
        $this->exceptionStatus = 500;
    }

    public function shortenUrl(Request $request): CommonResponseEntity{
        $response = new CommonResponseEntity();
        $response->status = 422;
        try {
            $reqUrl = stripslashes(trim($_REQUEST['longurl']));

            if(!preg_match('|^[0-9a-zA-Z]{1,6}$|', $reqUrl)){
                $response->errorMessage = "This is not a valid URL";
                return $response;
            }

            $prevUrl = UrlRequest::where('request_url', $reqUrl)->first();
            if($prevUrl){
                $response->errorMessage = "This URL is already shortend to: $prevUrl->shorten_url";
                return $response;
            }

            $response->status = 200;
            return $response;
        } catch (\Throwable $ex) {
            Log::error(' : Found Exception [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $ex->getFile() . '-' . $ex->getLine() . ']' . $ex->getMessage());
            $response->errorMessage = $this->exceptionMessage;
            $response->status = $this->exceptionStatus;

            return $response;
        }
    }
}
