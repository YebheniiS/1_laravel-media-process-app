<?php


namespace App\Lib;


use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

class S3ClientHelper
{
    private $s3Client;
    private $bucket;
    private $objects;

    public function __construct()
    {
        $awsKey = env('AWS_KEY');
        $awsSecret = env('AWS_SECRET');
        $awsRegion = env('AWS_REGION');
        $defaultBucket = env('AWS_BUCKET');

        $credentials = new Credentials($awsKey, $awsSecret);

        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => $awsRegion,
            'credentials' => $credentials
        ]);

        $this->bucket = $defaultBucket;
    }

    public function getBucketObjects($bucket = null)
    {
        $this->setBucket($bucket);

        try {
            return $this->getObjects();
        } catch (S3Exception $e) {
            $this->logErrorMessage($e);
        }
    }

    public function setBucket($bucket)
    {
        if (isset($bucket) && !empty($bucket)) {
            $this->bucket = $bucket;
        }
    }

    public function getObjects()
    {
        $objects = $this->s3Client->listObjects([
            'Bucket' => $this->bucket
        ]);

        $objectsContents = $objects['Contents'];

        if (isset($objectsContents) && is_array($objectsContents) && count($objectsContents)) {
            $this->objects = array_column($objectsContents, 'Key');

            return $this;
        }

        return null;
    }

    public function loop(callable $callback)
    {
        foreach ($this->objects as $object) {
            $callback($object);
        }
    }

    public function logErrorMessage(S3Exception $e)
    {
        $message = $e->getMessage();

        Log::error('S3ClientHelper Error | Unable get Bucket objects: ' . $message);
    }

}