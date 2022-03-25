<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetFullUrlRequest;
use App\Http\Requests\Api\UrlShortenerRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\UrlRequest;
use App\Services\UrlShortenerService;
use Illuminate\Support\Facades\Log;

class UrlShortenerController extends Controller
{
    use ApiResponseTrait;

    protected $urlShortenerService;
    protected $exceptionMessage;
    protected $baseUrl;

    public function __construct(UrlShortenerService $urlShortenerService)
    {
        $this->urlShortenerService = $urlShortenerService;
        $this->baseUrl = "shorturl.com/";

        $this->exceptionMessage = "Something went wrong. Please try again later.";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UrlShortenerRequest $request)
    {
        try{
            $response = $this->urlShortenerService->shortenUrl($request);
            $message = $response->message ?? "Url has been shortened successfully.";

            return $this->handleResponse($response, $message,true);
        } catch (\Exception $e){
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__.'@'.__FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return $this->invalidResponse($this->exceptionMessage,500);
        }
    }

    public function getFullUrl(GetFullUrlRequest $request)
    {
        try{
            $response = $this->urlShortenerService->getFullUrl($request);
            $message = $response->message ?? "Full Url is fetched successfully. Redirecting...";

            return $this->handleResponse($response, $message,true);
        } catch (\Exception $e){
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__.'@'.__FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return $this->invalidResponse($this->exceptionMessage,500);
        }
    }

    public function getFullUrlFromCodeAndRedirect($shortCode)
    {
        $prevUrl = UrlRequest::where('short_code', $shortCode)->firstOrFail();
        return redirect($prevUrl->request_url);
    }

    public function getFullUrlFromCodeDomainAndRedirect($subDomain, $shortCode)
    {
        $url = request()->getHttpHost()."/{$subDomain}/".$shortCode;
        $prevUrl = UrlRequest::where('shorten_url', $url)->firstOrFail();
        return redirect($prevUrl->request_url);
    }
}
