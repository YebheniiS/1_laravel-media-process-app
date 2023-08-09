<?php

namespace App\Helper;

use Aws\S3\S3Client;
use Illuminate\Http\Request;

class PreSignS3RequestHelper
{
    /**
     * @var S3Client
     */
    protected $s3;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string|null
     */
    protected $clientPrivateKey = null;

    /**
     * @var string|null
     */
    protected $expectedBucketName = null;

    /**
     * @var string
     */
    protected $expectedHostname = 's3-us-east-2.amazonaws.com/public-uploads.interactr.io';

    /**
     * @var null
     */
    protected $expectedMaxSize = null;

    /**
     * PreSignS3RequestHelper constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->expectedBucketName = env('S3_PUBLIC_UPLOADS_BUCKET');
        $this->expectedHostname =  's3.' . env('S3_PUBLIC_UPLOADS_REGION') . '.amazonaws.com/' . $this->expectedBucketName;
        $this->clientPrivateKey = env('S3_PUBLIC_UPLOADS_SECRET');

        $this->s3 = new S3Client([
            'region' => env('S3_PUBLIC_UPLOADS_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('S3_PUBLIC_UPLOADS_KEY'),
                'secret' => env('S3_PUBLIC_UPLOADS_SECRET'),
            ],
        ]);
    }

    /**
     * @return array
     */
    public function signRequest()
    {
        header('Content-Type: application/json');
        $responseBody = file_get_contents('php://input');
        $contentAsObject = json_decode($responseBody, true);
        $jsonContent = json_encode($contentAsObject);
        if (isset($contentAsObject['headers'])) {
            return $this->signRestRequest($contentAsObject['headers']);
        }

        return $this->signPolicy($jsonContent);
    }

    /**
     * @return array
     */
    public function verifyFileInS3()
    {
        $bucket = $this->request->get('bucket');
        $key = $this->request->get('key');
        // If utilizing CORS, we return a 200 response with the error message in the body
        // to ensure Fine Uploader can parse the error message in IE9 and IE8,
        // since XDomainRequest is used on those browsers for CORS requests.  XDomainRequest
        // does not allow access to the response body for non-success responses.
        if (isset($this->expectedMaxSize) && $this->getObjectSize($bucket, $key) > $this->expectedMaxSize) {
            // You can safely uncomment this next line if you are not depending on CORS
            header("HTTP/1.0 500 Internal Server Error");
            return array("error" => "File is too big!", "preventRetry" => true);
        }

        $link = $this->getTempLink($bucket, $key);
        $response = array("tempLink" => $link);
        return $response;
    }

    /**
     * @param string $policyStr
     * @return array
     */
    protected function signPolicy($policyStr)
    {
        $policyObj = json_decode($policyStr, true);
        if ($this->isPolicyValid($policyObj)) {
            $encodedPolicy = base64_encode($policyStr);
            if (isset($_REQUEST["v4"])) {
                return array('policy' => $encodedPolicy, 'signature' => $this->signV4Policy($encodedPolicy, $policyObj));
            }

            return array('policy' => $encodedPolicy, 'signature' => $this->sign($encodedPolicy));
        }

        return array("invalid" => true);
    }

    /**
     * @param string $stringToSign
     * @param array $policyObj
     * @return string
     */
    protected function signV4Policy($stringToSign, $policyObj)
    {
        foreach ($policyObj["conditions"] as $condition) {
            if (isset($condition["x-amz-credential"])) {
                $credentialCondition = $condition["x-amz-credential"];
            }
        }

        if (empty($credentialCondition)) {
            return '';
        }

        $pattern = "/.+\/(.+)\\/(.+)\/s3\/aws4_request/";
        preg_match($pattern, $credentialCondition, $matches);
        $dateKey = hash_hmac('sha256', $matches[1], 'AWS4' . $this->clientPrivateKey, true);
        $dateRegionKey = hash_hmac('sha256', $matches[2], $dateKey, true);
        $dateRegionServiceKey = hash_hmac('sha256', 's3', $dateRegionKey, true);
        $signingKey = hash_hmac('sha256', 'aws4_request', $dateRegionServiceKey, true);
        return hash_hmac('sha256', $stringToSign, $signingKey);
    }

    /**
     * @param array $policy
     * @return bool
     */
    protected function isPolicyValid($policy)
    {
        $conditions = $policy["conditions"];
        $bucket = null;
        $parsedMaxSize = null;
        for ($i = 0; $i < count($conditions); ++$i) {
            $condition = $conditions[$i];
            if (isset($condition["bucket"])) {
                $bucket = $condition["bucket"];
            } else if (isset($condition[0]) && $condition[0] == "content-length-range") {
                $parsedMaxSize = $condition[2];
            }
        }
        \Log::info('stuff', ['bucket' => $bucket, 'expectedBucketName' => $this->expectedBucketName, 'expectedhostname' => $this->expectedHostname]);
        return $bucket == $this->expectedBucketName && $parsedMaxSize == (string) $this->expectedMaxSize;
    }

    /**
     * @param string $headersStr
     * @return array
     */
    protected function signRestRequest($headersStr)
    {
        $version = $this->request->has('v4') ? 4 : 2;
        if ($this->isValidRestRequest($headersStr, $version)) {
            if ($version == 4) {
                return array('signature' => $this->signV4RestRequest($headersStr));
            }

            return array('signature' => $this->sign($headersStr));
        }

        return array("invalid" => true);
    }

    /**
     * @param string $headersStr
     * @param int $version
     * @return bool
     */
    protected function isValidRestRequest($headersStr, $version)
    {
//        if ($version === 2) {
////            $pattern = "/\/$this->expectedBucketName\/.+$/";
////        } else {
////            $pattern = "/host:$this->expectedHostname/";
////        }
//////        print_r($pattern);
//////        print_r($headersStr);
//////        die();
////
////        preg_match($pattern, $headersStr, $matches);
////        return count($matches) > 0;
    return true;
    }

    /**
     * @param string $rawStringToSign
     * @return string
     */
    protected function signV4RestRequest($rawStringToSign)
    {
        $pattern = "/.+\\n.+\\n(\\d+)\/(.+)\/s3\/aws4_request\\n(.+)/s";
        preg_match($pattern, $rawStringToSign, $matches);
        $hashedCanonicalRequest = hash('sha256', $matches[3]);
        $stringToSign = preg_replace("/^(.+)\/s3\/aws4_request\\n.+$/s", '$1/s3/aws4_request' . "\n" . $hashedCanonicalRequest, $rawStringToSign);
        $dateKey = hash_hmac('sha256', $matches[1], 'AWS4' . $this->clientPrivateKey, true);
        $dateRegionKey = hash_hmac('sha256', $matches[2], $dateKey, true);
        $dateRegionServiceKey = hash_hmac('sha256', 's3', $dateRegionKey, true);
        $signingKey = hash_hmac('sha256', 'aws4_request', $dateRegionServiceKey, true);
        return hash_hmac('sha256', $stringToSign, $signingKey);
    }

    /**
     * @param string $stringToSign
     * @return string
     */
    protected function sign($stringToSign)
    {
        return base64_encode(hash_hmac(
            'sha1',
            $stringToSign,
            $this->clientPrivateKey,
            true
        ));
    }

    /**
     * @param string $bucket
     * @param string $key
     * @return mixed
     */
    protected function getTempLink($bucket, $key)
    {
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $key
        ]);
        $request = $this->s3->createPresignedRequest($cmd, '+15 minutes');
        return (string) $request->getUri();
    }

    /**
     * @param string $bucket
     * @param string $key
     * @return mixed
     */
    protected function getObjectSize($bucket, $key)
    {
        $objInfo = $this->s3->headObject(array(
            'Bucket' => $bucket,
            'Key' => $key
        ));
        return $objInfo['ContentLength'];
    }
}
