<?php

namespace App\Http\Controllers;

use App\Helper\PermissionHelper;
use App\Repositories\ElementRepository;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    /**
     * @var ElementRepository
     */
    protected $elementRepo;

    /**
     * @var PermissionHelper
     */
    protected $elementPermissionHelper;

    public function __construct(ElementRepository $elementRepo, PermissionHelper $elementPermissionHelper)
    {
        $this->elementRepo = $elementRepo;
        $this->elementPermissionHelper = $elementPermissionHelper;
    }

    public function applyTemplate(Request $request)
    {
        $data = $request->all();
        return $this->elementRepo->applyTemplate($data);
    }
}
