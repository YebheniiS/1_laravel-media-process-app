<?php
namespace App\Lib;

use Illuminate\Support\Facades\Http;

class FileHelper {
    
  public static function getRemoteFileSize($url)
  {
    try {
      $response = Http::head($url);
      if ($response->hasHeader('Content-Length')) {
        // return length in KB
        return (int)$response->header('Content-Length') / 1024;
      }
    } catch (\Exception $e) {
        // Handle exception if needed
    }

    return 0;
  }
}