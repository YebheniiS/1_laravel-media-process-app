<?php

namespace App;

use App\Jobs\EncodeVideo;
use App\Jobs\NewVideoUploaded;
use App\Jobs\UploadVideo;
use App\Lib\Media\MediaEncodedEraser;
use App\Lib\Media\MediaStandardEraser;
use App\Lib\PushNotification;
use App\Lib\FileHelper;
use App\Models\User;
use App\Repositories\MediaRepository;
use App\Services\AnalyticsApi;
use App\Scopes\MediaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Illuminate\Events\queueable;
class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    protected $guarded = ['id'];

    protected static function booted()
    {
        // This adds the ->where('user_id', userId) to
        // all queries
        static::addGlobalScope(new MediaScope());

        // Add auth user id to new model instance
        static::creating(function ($media) {
            $media->user_id = auth()->id();
            $media->created_at = new \DateTime();
            $media->updated_at = new \DateTime();
            if($media->temp_storage_url) {
                // check if there is enough storage available for this media.
                $fileSize = FileHelper::getRemoteFileSize($media->temp_storage_url); // file size is in KB
                $analyticsApi = new AnalyticsApi();
                $isStorageLeft = $analyticsApi->isStorageLeft($media->user_id, $fileSize);
                if(! $isStorageLeft) throw new \Exception("No storage left");
            }
        });

        // This calls MediaEraser lib, to delete all media files from S3 bucket
//        static::deleting(function ($media) {
//            $mediaEraser = self::getMediaEraser($media);
//
//            $mediaEraser->eraseMedia();
//        });

        // If media->url is updating, generate thumbnails
        static::updating(function ($media) {
//            $updatingFields = $media->getDirty();
//            $url = $updatingFields['url'] ?? null;
//
//            if (!empty($url) && $media->url !== $url && $url) {
//                MediaRepository::generateThumbnailFromUrl($url, $media->id);
//            }
        });

        static::created(function($media){
          
            if(! $media->is_image && $media->manifest_url == "") {
                $media->load('project');

                if($media->project && $media->project->video_encoding_resolution) {
                    // Here we upload the video to the bunnycdn stream service that
                    // automatically encodes all uploaded videos
                    EncodeVideo::dispatch($media);
                }
                else {
                    // Here we just upload to a bunny cdn storage zone. this is for
                    // when users have chosen not to have us encode any media.
                    UploadVideo::dispatch($media);
                }
            }
        });
    }



    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function encode_task() : HasOne
    {
        return $this->hasOne(QencodeTask::class)->latest();
    }

    //
//    public function toArray()
//    {
//        $response = parent::toArray();
//
//        $response['hidden'] = (int) $response['hidden'];
//        return $response;
//    }

    public static function getMediaEraser($media)
    {
        $mediaEraserLib = null;

        if ( !empty($media->manifest_url)) {
            $mediaEraserLib = new MediaEncodedEraser($media);
        } else if ( !empty($media->url)) {
            $mediaEraserLib = new MediaStandardEraser($media);
        }

        return $mediaEraserLib;
    }

    /**
     * This method used for checking, does media linked to another media items
     * If it's -> we can't erase S3 files.
     * @param $s3Key
     * @return int
     */
    public static function hasS3KeyMatches($s3Key)
    {
        $searchKeyWord = '%'.$s3Key.'%';

        return self::query()
                    ->where('url', 'like', $searchKeyWord)
                    ->orWhere('manifest_url', 'like', $searchKeyWord)
                    ->orWhere('compressed_url', 'like', $searchKeyWord)
                    ->orWhere('compressed_mp4', 'like', $searchKeyWord)
                    ->orWHere('thumbnail_url', 'like', $searchKeyWord)
                    ->count();
    }

    public static function syncUpdateWithFe(Media $media, $newData)
    {
        $media->update($newData);

        $pusher = new PushNotification();
        $pusher->channel('media')->event('update')->push([
            'id' => $media->id,
            'data' => $newData
        ]);
    }
}
