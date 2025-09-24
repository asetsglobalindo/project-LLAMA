<?php

namespace App\Http\Controllers;

use App\Services\NewsApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService)
    {
        $this->newsApiService = $newsApiService;
    }

    public function index(): View
    {
        $filePath = base_path('resources/views/data/non-pms.json');
        $jsonData = file_get_contents($filePath);
        $dataNonPMS = json_decode($jsonData, true);
        
        // Load commercial locations data
        $commercialLocationsPath = base_path('resources/views/data/commercial-locations.json');
        $commercialLocationsData = file_get_contents($commercialLocationsPath);
        $commercialLocations = json_decode($commercialLocationsData, true);
        
        $response = $this->newsApiService->getAllNews();
        $articles = $response['status'] ? array_slice($response['data'], 0, 3) : [];
        return view('home', compact('dataNonPMS', 'articles', 'commercialLocations'));
    }

    public function GetListingDataAjax(Request $request): JsonResponse
    {
        $responseListings = Http::get(config('services.external_apis.pertare_listings_url'));

        $data = $responseListings->json();

        $listingData = $data['data'] ?? [];

        return response()->json([
            'success' => true,
            'data' => $listingData,
        ]);
    }

    public function GetAllSpaceAvailable(Request $request): JsonResponse
    {
        $responseSpaceAvailable = Http::get(config('services.external_apis.pertare_space_available_url'));

        $data = $responseSpaceAvailable->json();

        $spaceData = $data['data'] ?? [];

        return response()->json([
            'success' => true,
            'data' => $spaceData,
        ]);
    }

    public function GetDetailSpbu($listing_id): View
    {
        $apiUrl = str_replace('{listing_id}', $listing_id, config('services.external_apis.pertare_listing_detail_url'));

        // Fetch listing detail
        $response = Http::get($apiUrl);
        if ($response->failed() || !$response->json()) {
            abort(404, 'Listing not found');
        }
        $listingDetail = $response->json();


        return view('detail-page-spbu', compact('listingDetail'));
    }

    public function GetDetailListing($listing_id): View
    {
        $apiUrl = str_replace('{listing_id}', $listing_id, config('services.external_apis.service_listing_detail_url'));

        // Fetch listing detail
        $response = Http::get($apiUrl);
        if ($response->failed() || !$response->json()) {
            abort(404, 'Listing not found');
        }
        $listingDetail = $response->json();


        return view('detail-page-spbu', compact('listingDetail'));
    }

    // public function GetAreaSizePrice(Request $request)
    // {
    //     $allAssets = [];
    //     $currentPage = 1;
    //     $baseUrl = 'https://pertare.asets.id/api/space-available';

    //     do {
    //         $response = Http::get($baseUrl, ['page' => $currentPage]);
    //         $responseData = $response->json();
    //         $assets = $responseData['data'] ?? [];
    //         $pagination = $responseData['pagination'] ?? null;

    //         $allAssets = array_merge($allAssets, $assets);
    //         $currentPage++;
    //     } while ($pagination && $pagination['next_page_url']);

    //     $minAmount = $request->input('min_amount');
    //     $maxAmount = $request->input('max_amount');
    //     $area = $request->input('area');

    //     $sizeRanges = [
    //         'all_size' => [0, null],
    //         '0-20' => [0, 20],
    //         '20-50' => [20, 50],
    //         '50-100' => [50, 100],
    //         '100-200' => [100, 200],
    //         '200+' => [200, null],
    //     ];

    //     $luas = $request->input('size');
    //     $minLuas = $sizeRanges[$luas][0] ?? '';
    //     $maxLuas = $sizeRanges[$luas][1] ?? '';

    //     $filteredAssets = collect($allAssets)->filter(function ($asset) use ($minAmount, $maxAmount, $area, $minLuas, $maxLuas) {
    //         $price = (int) $asset['price'];
    //         $name = strtolower($asset['listing']['listing_address']);
    //         $size = (int) $asset['size_sqm'];

    //         $matchMinAmount = !$minAmount || $price >= (int)$minAmount;
    //         $matchMaxAmount = !$maxAmount || $price <= (int)$maxAmount;
    //         $matchArea = !$area || str_contains($name, strtolower($area));
    //         $matchMinLuas = !$minLuas || $size >= (int)$minLuas;
    //         $matchMaxLuas = !$maxLuas || $size <= (int)$maxLuas;

    //         return $matchMinAmount && $matchMaxAmount && $matchArea && $matchMinLuas && $matchMaxLuas;
    //     });

    //     return view('space-results', [
    //         'assets' => $filteredAssets,
    //         'input' => $request->all()
    //     ]);
    // }

    public function GetAreaSizePrice(Request $request)
    {
        $city = $request->input('city');
        $type = $request->input('type');

        return view('space-search', ['city' => $city, 'type' => $type]);
    }

    public function GetDataNonPMS()
    {
        $filePath = base_path('data/non-pms.json');

        // Pastikan file ada
        if (file_exists($filePath)) {
            // Ambil isi file JSON
            $jsonData = file_get_contents($filePath);

            // Decode JSON menjadi array PHP
            $dataNonPMS = json_decode($jsonData, true);


            // Kirim data ke view
            return view('home', compact('dataNonPMS'));
            dd($data['nonpms']);
        } else {
            return abort(404, 'File JSON tidak ditemukan.');
        }
    }
}
