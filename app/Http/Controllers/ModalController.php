<?php

namespace App\Http\Controllers;

use App\Helper\PermissionHelper;
use App\Modal;
use App\Repositories\ElementRepository;
use App\Repositories\ModalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModalController extends Controller
{
    protected $modalRepo;
    protected $permissionHelper;

    public function __construct(ModalRepository $modalRepo, PermissionHelper $permissionHelper)
    {
        $this->modalRepo = $modalRepo;
        $this->permissionHelper = $permissionHelper;
    }

    public function applyTemplate(Request $request)
    {
        $templateId = $request->get('templateId');
        $modalId = $request->get('modalId');
        if (!$templateId) abort(400, 'Template id missing!');
        if (!$modalId) {
            if (!$request->has('projectId')) abort(400, 'Project Id is required if the modal id is not provided');
            $modal = $this->modalRepo->createModal($request->get('projectId'));
            $modalId = $modal->id;
        }
        $this->permissionHelper->checkModalPermissions($modalId);
        return $this->modalRepo->applyTemplate($templateId, $modalId);
    }

    public function createElement($id, Request $request)
    {
        $data = $request->all();

        $this->permissionHelper->checkModalPermissions($id);

        return $this->modalRepo->createElement($id, $data);
    }
}
