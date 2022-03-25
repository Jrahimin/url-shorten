<?php

namespace App\Services;

use App\Entities\CommonResponseEntity;
use App\Http\Traits\CheckUrlSafety;
use App\Models\UrlRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UrlShortenerService
{
    use CheckUrlSafety;

    protected $exceptionMessage;
    protected $exceptionStatus;
    protected $characters;
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = "shorturl.com/";
        $this->characters = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
        $this->exceptionMessage = "Something went wrong. Please try again later.";
        $this->exceptionStatus = 500;
    }

    public function shortenUrl(Request $request): CommonResponseEntity
    {
        $response = new CommonResponseEntity();
        $response->status = 422;
        try {
            $reqUrl = stripslashes(trim($request->url));
            $response->fullUrl = $reqUrl;

            $prevUrl = UrlRequest::where('request_url', $reqUrl)->first();
            if ($prevUrl) {
                if(!$prevUrl->is_safe){
                    $response->errorMessage = "Sorry! This URL is not safe as per Google Safe Browsing API.";
                    return $response;
                }

                $response->status = 200;
                $response->fromPrev = true;
                $response->shortCode = $prevUrl->short_code;
                $response->shortUrl = $prevUrl->shorten_url;
                $response->message = "This URL is already shortened.";

                return $response;
            }

            $isSafeUrl = $this->checkIfSafe($reqUrl);
            if(!$isSafeUrl){
                $this->insertUrlReq($reqUrl);
                $response->errorMessage = "Sorry! This URL is not safe as per Google Safe Browsing API.";
                return $response;
            }

            $shortCode = $this->generateRandomValue();
            $shortUrl = $request->sub_domain ? $request->getHttpHost() . "/{$request->sub_domain}/{$shortCode}" : $request->getHttpHost() . "/".$shortCode;

            $this->insertUrlReq($reqUrl, $shortUrl, $shortCode,true);

            $response->shortCode = $shortCode;
            $response->shortUrl = $shortUrl;
            $response->status = 200;
            return $response;
        } catch (\Throwable $ex) {
            Log::error(' : Found Exception [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $ex->getFile() . '-' . $ex->getLine() . ']' . $ex->getMessage());
            $response->errorMessage = $this->exceptionMessage;
            $response->status = $this->exceptionStatus;

            return $response;
        }
    }

    public function getFullUrl(Request $request): CommonResponseEntity
    {
        $response = new CommonResponseEntity();
        $response->status = 422;
        try {
            $prevUrl = UrlRequest::where('shorten_url', $request->short_url)->first();

            $response->status = 200;
            $response->fullUrl = $prevUrl->request_url;
            $response->shortCode = $prevUrl->short_code;
            $response->shortUrl = $prevUrl->shorten_url;

            return $response;
        } catch (\Throwable $ex) {
            Log::error(' : Found Exception [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $ex->getFile() . '-' . $ex->getLine() . ']' . $ex->getMessage());
            $response->errorMessage = $this->exceptionMessage;
            $response->status = $this->exceptionStatus;

            return $response;
        }
    }

    protected function generateRandomValue()
    {
        $charSet = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $randStr = [];
        for ($i = 0; $i < 6; $i++) {
            $randStr[] = $charSet[rand(0, (strlen($charSet) - 1))];
        }

        $randStrSerial = implode('', $randStr);
        $randStr = str_shuffle($randStrSerial);

        return $randStr;
    }

    protected function insertUrlReq($reqUrl, $shortUrl=null, $shortCode=null, $isSafeUrl=false)
    {
        UrlRequest::create([
            'request_url' => $reqUrl,
            'shorten_url' => $shortUrl,
            'short_code' => $shortCode,
            'is_safe' => $isSafeUrl
        ]);
    }
}
