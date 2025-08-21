<?php

namespace App\Services;

use App\Models\WeatherData;
use App\Models\VietnamCity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.api_key');
    }

    /**
     * Get current weather by coordinates from session storage.
     */
    public function getCurrentWeatherByCoordinates($latitude, $longitude, $cityCode = null, $cityName = null)
    {
        try {
            Log::info($this->apiKey);
            $response = Http::withOptions([
                'verify' => false,
            ])->get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'vi'
            ]);
           

            if ($response->successful()) {
                $data = $response->json();
                
                // Create a temporary city object for processing if not provided
                $city = $cityCode && $cityName ? 
                    (object)['code' => $cityCode, 'name' => $cityName, 'latitude' => $latitude, 'longitude' => $longitude] :
                    (object)['code' => 'COORDS', 'name' => 'Unknown', 'latitude' => $latitude, 'longitude' => $longitude];
                
                Log::info('Weather API response by coordinates', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'city_code' => $cityCode,
                    'city_name' => $cityName,
                    'data' => $data
                ]);
                
                return $this->processWeatherDataByCoordinates($city, $data, $latitude, $longitude);
            }

            Log::error('OpenWeatherMap API error by coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Weather API request exception by coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city_code' => $cityCode,
                'city_name' => $cityName,
                'exception_message' => $e->getMessage(),
                'request_url' => "{$this->baseUrl}/weather",
                'timestamp' => now()->toISOString()
            ]);

            return null;
        }
    }

    /**
     * Get current weather for a city.
     */
    public function getCurrentWeather(VietnamCity $city)
    {
        try {
            $response = Http::get("{$this->baseUrl}/weather", [
                'lat' => $city->latitude,
                'lon' => $city->longitude,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'vi'
            ]);
            

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Weather API response', [
                    'city_id' => $city->id,
                    'city_name' => $city->name,
                    'city_code' => $city->code,
                    'data' => $data
                ]);
                return $this->processWeatherData($city, $data);
            }

          

            return null;

        } catch (\Exception $e) {
            // Log chi tiết exception với stack trace
            Log::error('Weather API request exception', [
                'city_id' => $city->id,
                'city_name' => $city->name,
                'city_code' => $city->code,
                'coordinates' => [
                    'lat' => $city->latitude,
                    'lon' => $city->longitude
                ],
                'exception_class' => get_class($e),
                'exception_message' => $e->getMessage(),
                'exception_code' => $e->getCode(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_url' => "{$this->baseUrl}/weather",
                'timestamp' => now()->toISOString()
            ]);

            return null;
        }
    }

    /**
     * Get 5-day forecast by coordinates from session storage.
     */
    public function getForecastByCoordinates($latitude, $longitude, $cityCode = null, $cityName = null)
    {
        try {
            $response = Http::get("{$this->baseUrl}/forecast", [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'vi'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OpenWeatherMap Forecast API error by coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city_code' => $cityCode,
                'city_name' => $cityName,
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Weather Forecast API request failed by coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city_code' => $cityCode,
                'city_name' => $cityName,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Get 5-day forecast for a city.
     */
    public function getForecast(VietnamCity $city)
    {
        try {
            $response = Http::get("{$this->baseUrl}/forecast", [
                'lat' => $city->latitude,
                'lon' => $city->longitude,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'vi'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OpenWeatherMap Forecast API error', [
                'city' => $city->name,
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Weather Forecast API request failed', [
                'city' => $city->name,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Process and save weather data by coordinates.
     */
    protected function processWeatherDataByCoordinates($city, array $data, $latitude, $longitude)
    {
        $weatherData = [
            'city_name' => $city->name,
            'city_code' => $city->code,
            'temperature' => $data['main']['temp'],
            'feels_like' => $data['main']['feels_like'],
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'] ?? 0,
            'weather_condition' => $data['weather'][0]['main'],
            'weather_description' => $data['weather'][0]['description'],
            'weather_icon' => $data['weather'][0]['icon'],
            'pressure' => $data['main']['pressure'],
            'visibility' => $data['visibility'] ?? 10000,
            'uv_index' => null, // UV index requires separate API call
            'forecast_data' => null,
            'last_updated' => now(),
            'weather_category' => $this->getWeatherCategory($data['weather'][0]['main']),
            'description' => $data['weather'][0]['description'],
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        // Save weather data with coordinates
        WeatherData::updateOrCreate(
            ['city_code' => $city->code, 'last_updated' => now()->startOfHour()],
            $weatherData
        );

        return $weatherData;
    }

    /**
     * Process and save weather data.
     */
    protected function processWeatherData(VietnamCity $city, array $data)
    {
        $weatherData = [
            'city_name' => $city->name,
            'city_code' => $city->code,
            'temperature' => $data['main']['temp'],
            'feels_like' => $data['main']['feels_like'],
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'] ?? 0,
            'weather_condition' => $data['weather'][0]['main'],
            'weather_description' => $data['weather'][0]['description'],
            'weather_icon' => $data['weather'][0]['icon'],
            'pressure' => $data['main']['pressure'],
            'visibility' => $data['visibility'] ?? 10000,
            'uv_index' => null, // UV index requires separate API call
            'forecast_data' => null,
            'last_updated' => now(),
            'weather_category' => $this->getWeatherCategory($data['weather'][0]['main']),
            'description' => $data['weather'][0]['description']
        ];

        // Save weather data
        WeatherData::updateOrCreate(
            ['city_code' => $city->code, 'last_updated' => now()->startOfHour()],
            $weatherData
        );

        return $weatherData;
    }

    /**
     * Update weather data for all cities.
     */
    public function updateAllCitiesWeather()
    {
        $cities = VietnamCity::active()->get();
        $updatedCount = 0;

        foreach ($cities as $city) {
            $weatherData = $this->getCurrentWeather($city);
            if ($weatherData) {
                $updatedCount++;
            }

            // Add delay to avoid rate limiting
            usleep(100000); // 0.1 second delay
        }

        Log::info("Weather data updated for {$updatedCount} cities");

        return $updatedCount;
    }

    /**
     * Refresh weather data from session coordinates and update cache.
     */
    public function refreshWeatherFromSession()
    {
        if (!session('user_location')) {
            Log::warning('No user location found in session for weather refresh');
            return null;
        }

        $userLocation = session('user_location');
        $latitude = $userLocation['latitude'] ?? null;
        $longitude = $userLocation['longitude'] ?? null;
        $cityCode = $userLocation['nearest_city_code'] ?? null;
        $cityName = $userLocation['nearest_city_name'] ?? null;

        if (!$latitude || !$longitude) {
            Log::warning('Invalid coordinates in session for weather refresh', ['user_location' => $userLocation]);
            return null;
        }

        // Clear any existing cache for this location
        $cacheKey = "weather_coords_{$latitude}_{$longitude}";
        Cache::forget($cacheKey);

        // Get fresh weather data
        $weatherData = $this->getCurrentWeatherByCoordinates($latitude, $longitude, $cityCode, $cityName);

        if ($weatherData) {
            // Cache the fresh data
            Cache::put($cacheKey, $weatherData, 1800); // 30 minutes
            Log::info('Weather data refreshed and cached from coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city_code' => $cityCode
            ]);
        }

        return $weatherData;
    }

    /**
     * Get cached weather data by coordinates.
     */
    public function getCachedWeatherByCoordinates($latitude, $longitude)
    {
        $cacheKey = "weather_coords_{$latitude}_{$longitude}";

        return Cache::remember($cacheKey, 1800, function () use ($latitude, $longitude) { // 30 minutes cache
            // Try to get from session if available
            if (session('user_location')) {
                $userLocation = session('user_location');
                $cityCode = $userLocation['nearest_city_code'] ?? null;
                $cityName = $userLocation['nearest_city_name'] ?? null;
                
                return $this->getCurrentWeatherByCoordinates($latitude, $longitude, $cityCode, $cityName);
            }
            
            return $this->getCurrentWeatherByCoordinates($latitude, $longitude);
        });
    }

    /**
     * Get cached weather data for a city.
     */
    public function getCachedWeather(VietnamCity $city)
    {
        $cacheKey = "weather_{$city->code}";

        return Cache::remember($cacheKey, 1800, function () use ($city) { // 30 minutes cache
            return $city->latestWeatherData;
        });
    }

    /**
     * Get current weather from session storage coordinates.
     */
    public function getCurrentWeatherFromSession()
    {
        if (!session('user_location')) {
            Log::warning('No user location found in session');
            return null;
        }

        $userLocation = session('user_location');
        $latitude = $userLocation['latitude'] ?? null;
        $longitude = $userLocation['longitude'] ?? null;
        $cityCode = $userLocation['nearest_city_code'] ?? null;
        $cityName = $userLocation['nearest_city_name'] ?? null;

        if (!$latitude || !$longitude) {
            Log::warning('Invalid coordinates in session', ['user_location' => $userLocation]);
            return null;
        }

        return $this->getCurrentWeatherByCoordinates($latitude, $longitude, $cityCode, $cityName);
    }

    /**
     * Get forecast from session storage coordinates.
     */
    public function getForecastFromSession()
    {
        if (!session('user_location')) {
            Log::warning('No user location found in session');
            return null;
        }

        $userLocation = session('user_location');
        $latitude = $userLocation['latitude'] ?? null;
        $longitude = $userLocation['longitude'] ?? null;
        $cityCode = $userLocation['nearest_city_code'] ?? null;
        $cityName = $userLocation['nearest_city_name'] ?? null;

        if (!$latitude || !$longitude) {
            Log::warning('Invalid coordinates in session', ['user_location' => $userLocation]);
            return null;
        }

        return $this->getForecastByCoordinates($latitude, $longitude, $cityCode, $cityName);
    }

    /**
     * Get weather data for multiple nearby locations around coordinates.
     */
    public function getNearbyLocationsWeather($latitude, $longitude, $radius = 50)
    {
        // Find cities within the specified radius (in kilometers)
        $nearbyCities = VietnamCity::active()
            ->selectRaw('*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', 
                [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit(5)
            ->get();

        $weatherData = [];

        foreach ($nearbyCities as $city) {
            $weather = $this->getCachedWeather($city);
            if ($weather) {
                $weatherData[] = [
                    'city' => $city,
                    'weather' => $weather,
                    'distance' => round($city->distance, 2)
                ];
            }
        }

        return $weatherData;
    }

    /**
     * Handle location update and refresh weather data.
     */
    public function handleLocationUpdate($latitude, $longitude, $cityCode = null, $cityName = null)
    {
        // Clear old cache entries
        if (session('user_location')) {
            $oldLocation = session('user_location');
            $oldLat = $oldLocation['latitude'] ?? null;
            $oldLon = $oldLocation['longitude'] ?? null;
            
            if ($oldLat && $oldLon) {
                $oldCacheKey = "weather_coords_{$oldLat}_{$oldLon}";
                Cache::forget($oldCacheKey);
            }
        }

        // Update session with new location
        session([
            'user_location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'nearest_city_code' => $cityCode,
                'nearest_city_name' => $cityName,
                'updated_at' => now()->toISOString()
            ]
        ]);

        // Get fresh weather data for new location
        $weatherData = $this->getCurrentWeatherByCoordinates($latitude, $longitude, $cityCode, $cityName);

        if ($weatherData) {
            Log::info('Location updated and weather data refreshed', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city_code' => $cityCode,
                'city_name' => $cityName
            ]);
        }

        return $weatherData;
    }

    /**
     * Get weather data for current user location with automatic fallback.
     */
    public function getCurrentUserWeather()
    {
        // First try to get from session coordinates
        if (session('user_location')) {
            $userLocation = session('user_location');
            $latitude = $userLocation['latitude'] ?? null;
            $longitude = $userLocation['longitude'] ?? null;
            $cityCode = $userLocation['nearest_city_code'] ?? null;
            $cityName = $userLocation['nearest_city_name'] ?? null;

            if ($latitude && $longitude) {
                // Try cached data first
                $cachedWeather = $this->getCachedWeatherByCoordinates($latitude, $longitude);
                if ($cachedWeather) {
                    return $cachedWeather;
                }

                // If no cache, get fresh data
                $freshWeather = $this->getCurrentWeatherByCoordinates($latitude, $longitude, $cityCode, $cityName);
                if ($freshWeather) {
                    return $freshWeather;
                }
            }
        }

        // Fallback: try to get weather for default city (HCM)
        $defaultCity = VietnamCity::where('code', 'HCM')->first();
        if ($defaultCity) {
            return $this->getCachedWeather($defaultCity);
        }

        Log::warning('No weather data available for current user location');
        return null;
    }

    /**
     * Get weather data for multiple cities.
     */
    public function getMultipleCitiesWeather(array $cityCodes)
    {
        $weatherData = [];

        foreach ($cityCodes as $cityCode) {
            $city = VietnamCity::findByCode($cityCode);
            if ($city) {
                $weatherData[$cityCode] = $this->getCachedWeather($city);
            }
        }

        return $weatherData;
    }

    /**
     * Get weather statistics.
     */
    public function getWeatherStats()
    {
        $stats = Cache::remember('weather_stats', 3600, function () {
            $totalCities = VietnamCity::active()->count();
            $citiesWithWeather = WeatherData::recent(6)->distinct('city_code')->count();
            $lastUpdate = WeatherData::latest('last_updated')->first();

            return [
                'total_cities' => $totalCities,
                'cities_with_weather' => $citiesWithWeather,
                'coverage_percentage' => $totalCities > 0 ? round(($citiesWithWeather / $totalCities) * 100, 2) : 0,
                'last_update' => $lastUpdate ? $lastUpdate->last_updated : null
            ];
        });

        return $stats;
    }

    /**
     * Get cities with outdated weather data.
     */
    public function getCitiesWithOutdatedWeather($hours = 6)
    {
        $citiesWithRecentWeather = WeatherData::recent($hours)
            ->pluck('city_code')
            ->toArray();

        return VietnamCity::active()
            ->whereNotIn('code', $citiesWithRecentWeather)
            ->get();
    }

    /**
     * Clean old weather data.
     */
    public function cleanOldWeatherData($days = 7)
    {
        $deletedCount = WeatherData::where('last_updated', '<', now()->subDays($days))
            ->delete();

        Log::info("Cleaned {$deletedCount} old weather records");

        return $deletedCount;
    }

    /**
     * Get weather category from weather condition.
     */
    protected function getWeatherCategory($condition)
    {
        $condition = strtolower($condition);

        if (str_contains($condition, 'rain') || str_contains($condition, 'drizzle')) {
            return 'rainy';
        }

        if (str_contains($condition, 'snow')) {
            return 'snowy';
        }

        if (str_contains($condition, 'cloud')) {
            return 'cloudy';
        }

        if (str_contains($condition, 'clear') || str_contains($condition, 'sun')) {
            return 'sunny';
        }

        if (str_contains($condition, 'storm') || str_contains($condition, 'thunder')) {
            return 'stormy';
        }

        return 'normal';
    }
}