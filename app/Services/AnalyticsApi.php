<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User;

class AnalyticsApi
{
    /**
     * Increases the storage for a given user by a specified amount.
     * 
     * @param int $userId The ID of the user whose storage to increase.
     * @param int $deltaAmount The amount to increase the user's storage by.
     * @return bool Returns true if the API request was successful, false otherwise.
     */
    public function increaseStorage($userId, $deltaAmount) {
        $data = [
            'user_id' => $userId,
            'delta_amount' => $deltaAmount
        ];

        $res = $this->post('increase-storage', $data);
        
        if($res) return true;

        return false;
    }
    
    /**
     * Decreases the storage for a given user by a specified amount.
     * 
     * @param int $userId The ID of the user whose storage to decrease.
     * @param int $deltaAmount The amount to decrease the user's storage by.
     * @return bool Returns true if the API request was successful, false otherwise.
     */
    public function decreaseStorage($userId, $deltaAmount) {
        $data = [
            'user_id' => $userId,
            'delta_amount' => $deltaAmount
        ];

        $res = $this->post('decrease-storage', $data);
        
        if($res) return true;

        return false;
    }

    /**
     * Decreases all the storage credit for a given user
     * 
     * @param int $userId The ID of the user whose storage to decrease.
     * @return bool Returns true if the API request was successful, false otherwise.
     */
    public function decreaseStorageAll($userId) {
        $data = [
            'user_id' => $userId
        ];

        $res = $this->post('decrease-storage-all', $data);
        
        if($res) return true;

        return false;
    }

    /**
     * Get the storage amount for the specified user ID.
     * 
     * @param int $userId The user ID.
     * @return int The amount of storage for the user, or 0 if an error occurs.
     */
    public function getStorage($userId) {
        $data = [
            'user_id' => $userId
        ];

        $res = $this->post('get-storage', $data);
        if($res) return $res['amount'];

        return 0;
    }

    /**
     * Increases the minutes for a given user by a specified amount.
     * 
     * @param int $userId The ID of the user whose minutes to increase.
     * @param int $deltaAmount The amount to increase the user's minutes by.
     * @return bool Returns true if the API request was successful, false otherwise.
     */
    public function increaseMins($userId, $deltaAmount) {
        $data = [
            'user_id' => $userId,
            'delta_amount' => $deltaAmount
        ];

        $res = $this->post('increase-mins', $data);
        
        if($res) return true;

        return false;
    }

    /**
     * Decreases all the streaming credit for a given user
     * 
     * @param int $userId The ID of the user whose streaming mins to decrease.
     * @return bool Returns true if the API request was successful, false otherwise.
     */
    public function decreaseMinsAll($userId) {
        $data = [
            'user_id' => $userId
        ];

        $res = $this->post('decrease-mins-all', $data);
        
        if($res) return true;

        return false;
    }
    
    public function isStorageLeft($userId, $fileSize) {
        $userPlan = User::where('id', $userId)->first()->plan;
        if(!$userPlan) return false;

        $storageUsed = $this->getStorageUsed($userId);
        $totalStorage = $userPlan->upload_gb * 1024 * 1024;
        $storageLeft = $totalStorage - $storageUsed;;

        if($storageLeft < $fileSize) return false;

        return true;
    }


    public function getStorageUsed($userId) {
        $now = Carbon::now('UTC');
        $firstDayOfMonth = Carbon::createFromDate($now->year, $now->month, 1, 'UTC')->startOfDay();
        $lastDayOfMonth = Carbon::createFromDate($now->year, $now->month, 1, 'UTC')->lastOfMonth()->endOfDay();

        $queries = [
            [
                'name' => 'storage_used',
                'collection' => 'UploadStorage',
                'api' => 'Interactr',
                'filters' => [
                    'user_id' => $userId
                ],
                'start_date' => $firstDayOfMonth,
                'end_date' => $lastDayOfMonth,
                'group_by' => 'user_id',
                'count' => 'storage_used'
            ]
        ];

        $res = $this->query($queries);
        if($res && $res['storage_used']) return $res['storage_used'][$userId];
        return 0;
    }

    public function recordStorageUsed($userId, $projectId, $mediaId, $storageUsed) {
        $data = [
            'project_id' => $projectId,
            'user_id' => $userId,
            'media_id' => $mediaId,
            'storage_used' => $storageUsed
        ];

        $res = $this->post('upload-storage', $data);
        if($res) return true;
        return false;
    }    

    /**
     * Sends a POST request to the specified path with the given data.
     * 
     * @param string $path The path of the API endpoint to send the request to.
     * @param array $data The data to send in the request body.
     * @return mixed Returns the JSON response from the API, or null if the request fails.
     */
    private function post($path, $data) {
        try {
            $response = Http::post($this->getUrl($path), $data);
            return $response->json();
        } catch(\Exception $exception){
            echo "Message: " . $exception->getMessage() . "   Trace: " . $exception->getTraceAsString();
        }
        return null;
    }


    private function query($data) {
        try {
            $analyticsUrl = env('ANALYTICS_URL');
            $analyticsKey = env('ANALYTICS_KEY');
            $url = $analyticsUrl.'/'.$analyticsKey."/query";
            $response = Http::post($url, $data);
            return $response->json();
        } catch(\Exception $exception){
            echo "Message: " . $exception->getMessage() . "   Trace: " . $exception->getTraceAsString();
        }
        return null;
    }

    /**
     * Constructs the URL for the given API endpoint.
     * 
     * @param string $path The path of the API endpoint to construct the URL for.
     * @return string Returns the full URL for the API endpoint.
     */
    private function getUrl($path) {

        $analyticsUrl = env('ANALYTICS_URL');
        $analyticsKey = env('ANALYTICS_KEY');
        $url = $analyticsUrl.'/'.$analyticsKey."/interactr/".$path;
        return $url;
    }

}