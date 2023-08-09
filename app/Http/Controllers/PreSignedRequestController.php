<?php

namespace App\Http\Controllers;

use App\Helper\PreSignS3RequestHelper;
use Illuminate\Http\Request;

class PreSignedRequestController extends Controller
{
    /**
     * @var PreSignS3RequestHelper
     */
    protected $preSignS3RequestHelper;

    /**
     * PreSignedRequestController constructor.
     * @param PreSignS3RequestHelper $preSignS3RequestHelper
     */
    public function __construct(PreSignS3RequestHelper $preSignS3RequestHelper)
    {
        $this->preSignS3RequestHelper = $preSignS3RequestHelper;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post(Request $request)
    {
        if ($request->get('success')) {
            $response = $this->preSignS3RequestHelper->verifyFileInS3();
        } else {
            $response = $this->preSignS3RequestHelper->signRequest();
        }

        return response()->json($response);
    }
}
