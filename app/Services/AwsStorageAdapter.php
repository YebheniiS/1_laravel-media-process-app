<?php


namespace App\Services;

use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AwsStorageAdapter
{
  private $disk;
  public function __construct() 
  {
    $this->disk = Storage::disk('s3');
  }

  public function delete($url)
  {
    if($url)
      $this->disk->delete($url);
  }
}

?>