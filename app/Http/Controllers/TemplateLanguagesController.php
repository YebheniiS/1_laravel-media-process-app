<?php

namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use App\TemplateLanguages;
use App\Http\Resources\TemplateLanguages as TemplateLanguagesResource;

class TemplateLanguagesController extends Controller
{
    public function index()
    {
        $templateLanguages = TemplateLanguages::all();

        return TemplateLanguagesResource::collection($templateLanguages);
    }

    public function store()
    {
        $language = TemplateLanguages::create(
            request()->validate([
                'english_name' => 'required',
                'native_name' => 'required'
            ])
        );

        return new TemplateLanguagesResource($language);
    }

    public function destroy(TemplateLanguages $language)
    {
        try {
            ProjectRepository::makeTemplateLanguagesNullable($language->templates);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        if (!$language->delete()) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Template Language successfully removed.'
        ]);
    }
}
