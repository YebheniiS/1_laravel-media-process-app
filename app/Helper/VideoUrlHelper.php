<?php
namespace App\Helper;


use App\Lib\VideoStreamHelper;
use App\Media;
use App\Models\User;
use Illuminate\Support\Str;

class VideoUrlHelper
{
    public function isValidImageUrl($url)
    {
        $isOK = false;

        if (Str::contains($url, ['.jpg', '.jpeg', '.png', '.svg', '.gif', '.peng'])) {
            $isOK = true;
        }

        return $isOK;
    }

    /**
     * Validate video URL
     *
     * @param $url
     * @return bool
     */
    public function isValidVideoUrl($url)
    {
        $isOK = false;

        if (Str::contains($url, 'youtube')) {
            $isOK = true;
        }

        if (Str::contains($url, 'vimeo')) {
            $isOK = true;
        }

        if (Str::contains($url, ['.mp4', '.m4v', '.m3u8'])) {
            $isOK = true;
        }

        // TODO: check for file:// urls?
        if (!Str::contains($url, "://")) {
            $isOK = false;
        }

        if (!$isOK) {
            return false;
        }

        if (empty($headers)) {
            // TODO: reenable? shortcutted for now
            return true;
            return false;
        }

        return Str::contains($headers[0], '200 OK');
    }

    /**
     * Validate given video URL
     *
     * @param $url
     * @return string
     * @throws \Exception
     */
    public function makeValidVideo($url)
    {
        if ($this->isValidVideoUrl($url)) {
            return $url;
        }

        $withScheme = addScheme($url);

        if ($this->isValidVideoUrl($withScheme)) {
            return $withScheme;
        }

        return false;
    }

    /**
     * Validate given image URL
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public function makeValidImage($url)
    {
        if ($this->isValidImageUrl($url)) {
            return $url;
        }

        return false;
    }

    /**
     * Get video name from url
     *
     * @param $name
     * @param $url
     * @param $projectId
     * @return bool|false|mixed|string
     */
    public function getVideoName($url, $projectId, $name = null) : string
    {
        if (isset($name) && !empty($name)){
            // Use the filename as video name if we can
            $name = substr($name, 0, -4);
        } else {
            // If it's a streamed URL try and get name from source
            $name = (new VideoStreamHelper($url))->getName();

            // Fallback to video number
            if (! $name) {
                $whereClauses = [];

                if ($projectId) {
                    $whereClauses[] = ['project_id', '=', $projectId];
                }

                $videoCount = Media::query()->where($whereClauses)->count();
                $name = "Video " . ++$videoCount;
            }
        }

        return $name;
    }

    /**
     * Video file is mp4
     * @param $url
     * @return bool
     */
    public function isMp4File($url) : bool
    {
        return (strpos($url, '.mp4') !== false);
    }

    /**
     * @param $url
     * @return bool
     */
    public function isStreamUrl($url) {
        // Is Youtube
        if (strpos($url, 'youtube.com/watch?') !== false){
            return true;
        }

        // Is Vimeo
        if (strpos($url, 'vimeo.com/') !== false){
            return true;
        }

        return '';
    }
}

function addScheme($url, $scheme = 'http://')
{
    return parse_url($url, PHP_URL_SCHEME) === null ?
        $scheme . $url : $url;
}