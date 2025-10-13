<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ExternalApiService
{
    private $listingsUrl;
    private $spaceAvailableUrl;
    private $listingDetailUrl;
    private $serviceListingDetailUrl;

    public function __construct()
    {
        $this->listingsUrl = config('services.external_apis.pertare_listings_url');
        $this->spaceAvailableUrl = config('services.external_apis.pertare_space_available_url');
        $this->listingDetailUrl = config('services.external_apis.pertare_listing_detail_url');
        $this->serviceListingDetailUrl = config('services.external_apis.service_listing_detail_url');
    }

    /**
     * Fetch all listings from external API
     */
    public function fetchListings(): array
    {
        try {
            // Use file cache instead of database cache
            return Cache::store('file')->remember('external_listings', 1800, function () {
                Log::info('Fetching listings from URL: ' . $this->listingsUrl);
                $response = Http::withOptions([
                    'verify' => false,
                    'timeout' => 30,
                ])->get($this->listingsUrl);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Successfully fetched listings from external API', [
                        'count' => count($data['data'] ?? []),
                        'url' => $this->listingsUrl
                    ]);
                    return $data['data'] ?? [];
                }

                Log::error('Failed to fetch listings from external API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $this->listingsUrl
                ]);
                return [];
            });
        } catch (\Exception $e) {
            Log::error('Exception while fetching listings from external API', [
                'message' => $e->getMessage(),
                'url' => $this->listingsUrl
            ]);
            return [];
        }
    }

    /**
     * Fetch space available data from external API
     */
    public function fetchSpaceAvailable(): array
    {
        try {
            // Use file cache instead of database cache
            return Cache::store('file')->remember('external_spaces', 1800, function () {
                $response = Http::withOptions([
                    'verify' => false,
                    'timeout' => 30,
                ])->get($this->spaceAvailableUrl);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Successfully fetched spaces from external API', [
                        'count' => count($data['data'] ?? []),
                        'url' => $this->spaceAvailableUrl
                    ]);
                    return $data['data'] ?? [];
                }

                Log::error('Failed to fetch spaces from external API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $this->spaceAvailableUrl
                ]);
                return [];
            });
        } catch (\Exception $e) {
            Log::error('Exception while fetching spaces from external API', [
                'message' => $e->getMessage(),
                'url' => $this->spaceAvailableUrl
            ]);
            return [];
        }
    }

    /**
     * Fetch detailed listing information
     */
    public function fetchListingDetail(string $listingId): array
    {
        try {
            $url = str_replace('{listing_id}', $listingId, $this->listingDetailUrl);
            
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Successfully fetched listing detail from external API', [
                    'listing_id' => $listingId,
                    'url' => $url
                ]);
                return $data;
            }

            Log::error('Failed to fetch listing detail from external API', [
                'listing_id' => $listingId,
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $url
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching listing detail from external API', [
                'listing_id' => $listingId,
                'message' => $e->getMessage(),
                'url' => $url ?? 'unknown'
            ]);
            return [];
        }
    }

    /**
     * Fetch service listing detail
     */
    public function fetchServiceListingDetail(string $listingId): array
    {
        try {
            $url = str_replace('{listing_id}', $listingId, $this->serviceListingDetailUrl);
            
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Successfully fetched service listing detail from external API', [
                    'listing_id' => $listingId,
                    'url' => $url
                ]);
                return $data;
            }

            Log::error('Failed to fetch service listing detail from external API', [
                'listing_id' => $listingId,
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $url
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching service listing detail from external API', [
                'listing_id' => $listingId,
                'message' => $e->getMessage(),
                'url' => $url ?? 'unknown'
            ]);
            return [];
        }
    }

    /**
     * Get all location data from external APIs
     */
    public function getAllLocationData(): array
    {
        $allLocations = [];
        
        // Fetch listings data
        $listings = $this->fetchListings();
        if (!empty($listings)) {
            $transformedListings = $this->transformListingsData($listings);
            $allLocations = array_merge($allLocations, $transformedListings);
        }
        
        // Fetch spaces data
        $spaces = $this->fetchSpaceAvailable();
        if (!empty($spaces)) {
            $transformedSpaces = $this->transformSpacesData($spaces);
            $allLocations = array_merge($allLocations, $transformedSpaces);
        }
        
        return $allLocations;
    }

    /**
     * Transform listings data to match internal format
     */
    private function transformListingsData(array $listings): array
    {
        $transformedLocations = [];
        
        foreach ($listings as $listing) {
            $transformedLocations[] = [
                'id' => (string) ($listing['listing_id'] ?? ''),
                'name' => $listing['name'] ?? 'Unknown Listing',
                'address' => $listing['address'] ?? '',
                'city' => $this->extractCityFromAddress($listing['address'] ?? ''),
                'cover' => $this->getFirstPhotoUrl($listing['photos'] ?? []),
                'spaces' => [
                    [
                        'name' => $listing['name'] ?? 'Space',
                        'price' => isset($listing['starting_price']) ? (float) $listing['starting_price'] : null,
                        'price_type' => $listing['starting_price_type'] ?? 'lot',
                        'space_size' => null,
                        'description' => '',
                        'source' => 'pms',
                        'spaces_count' => $listing['total_space'] ?? 0,
                        'city_name' => $listing['city_name'] ?? '',
                        'area_name' => $listing['area_name'] ?? '',
                        'available' => $listing['available'] ?? 0,
                        'total_space_available' => $listing['total_space_available'] ?? 0
                    ]
                ]
            ];
        }
        
        return $transformedLocations;
    }

    /**
     * Transform spaces data to match internal format
     */
    private function transformSpacesData(array $spaces): array
    {
        $groupedByListing = [];
        
        foreach ($spaces as $space) {
            $listingId = (string) ($space['listing']['listing_id'] ?? 'unknown');
            
            if (!isset($groupedByListing[$listingId])) {
                $groupedByListing[$listingId] = [
                    'id' => $listingId,
                    'name' => $space['listing']['listing_name'] ?? 'Space Listing ' . $listingId,
                    'address' => $space['listing']['listing_address'] ?? '',
                    'city' => $this->extractCityFromAddress($space['listing']['listing_address'] ?? ''),
                    'cover' => $this->getFirstPhotoUrl($space['listing']['listing_photos'] ?? []),
                    'spaces' => []
                ];
            }
            
            $groupedByListing[$listingId]['spaces'][] = [
                'name' => $space['name'] ?? ($space['code'] ?? 'Space'),
                'price' => isset($space['price']) ? (float) $space['price'] : null,
                'price_type' => $space['price_type'] ?? 'm2',
                'space_size' => isset($space['size_sqm']) ? (float) $space['size_sqm'] : null,
                'description' => $space['description'] ?? '',
                'source' => 'private_sector',
                'type' => $space['type']['name'] ?? '',
                'min_period' => $space['min_period'] ?? '',
                'code' => $space['code'] ?? '',
                'available' => $space['available'] ?? 0
            ];
        }
        
        return array_values($groupedByListing);
    }

    /**
     * Extract city name from address string
     */
    private function extractCityFromAddress(string $address): string
    {
        $knownCities = [
            'jakarta', 'bogor', 'depok', 'tangerang', 'bandung', 'bengkulu', 
            'jember', 'banjarmasin', 'cilacap', 'ciamis', 'serang', 'bsd city',
            'kuningan', 'cilincing', 'ancol', 'wanareja', 'bojongmengger',
            'medan', 'palembang', 'denpasar', 'yogyakarta', 'solo', 'malang',
            'surabaya', 'manado', 'bekasi', 'pekanbaru', 'padang', 'semarang',
            'makassar', 'balikpapan'
        ];
        
        $lowerAddress = mb_strtolower($address, 'UTF-8');
        
        foreach ($knownCities as $city) {
            if (str_contains($lowerAddress, $city)) {
                return ucfirst($city);
            }
        }
        
        return 'Unknown';
    }

    /**
     * Get first photo URL from photos array
     */
    private function getFirstPhotoUrl(array $photos): string
    {
        if (empty($photos)) {
            return '';
        }
        
        // Handle different photo formats
        if (is_string($photos[0])) {
            return $photos[0];
        }
        
        if (is_array($photos[0]) && isset($photos[0]['image_url'])) {
            return $photos[0]['image_url'];
        }
        
        return '';
    }

    /**
     * Get first media URL from media array
     */
    private function getFirstMediaUrl(array $media): string
    {
        if (empty($media)) {
            return '';
        }
        
        $firstMedia = $media[0] ?? null;
        if ($firstMedia && isset($firstMedia['url'])) {
            return $firstMedia['url'];
        }
        
        return '';
    }

    /**
     * Clear cache for external data
     */
    public function clearCache(): void
    {
        Cache::store('file')->forget('external_listings');
        Cache::store('file')->forget('external_spaces');
        Log::info('External API cache cleared');
    }
}
