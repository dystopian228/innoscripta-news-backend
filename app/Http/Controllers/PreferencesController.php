<?php

namespace App\Http\Controllers;

use App\Entities\Response\PreferenceItem;
use App\Entities\SourceDefinition;
use App\Http\Requests\Preferences\UpdatePreferencesRequest;
use App\Services\APIs\IAggregatorNewsService;
use App\Services\Preferences\IPreferencesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PreferencesController extends BaseController
{
    private IPreferencesService $preferencesService;
    private IAggregatorNewsService $aggregatorNewsService;
    public function __construct(IPreferencesService $preferencesService_, IAggregatorNewsService $aggregatorNewsService_)
    {
        $this->preferencesService = $preferencesService_;
        $this->aggregatorNewsService = $aggregatorNewsService_;
    }

    public function index(): JsonResponse
    {
        $userCategories = $this->preferencesService->getUserCategories(Auth::user()->id);
        $userSources = $this->preferencesService->getUserSources(Auth::user()->id);

        $categories = $this->aggregatorNewsService->getDistinctCategories();
        $sources = $this->preferencesService->getSources();

        $categoriesPreferences = [];
        foreach ($categories as $category) {
            $categoriesPreferences[] = new PreferenceItem(symbol:  $category, text: $category, checked: in_array($category, $userCategories));
        }

        $sourcesPreferences = [];
        foreach ($sources as $source) {
            $sourcesPreferences[] = new PreferenceItem(symbol:  $source[SourceDefinition::SYMBOL], text: $source[SourceDefinition::NAME], checked: in_array($source[SourceDefinition::SYMBOL], $userSources));
        }

        return $this->ok(['sources' => $sourcesPreferences, 'categories' => $categoriesPreferences]);

    }

    public function update(UpdatePreferencesRequest $request): JsonResponse
    {
        $fields = $request->validated();

        $this->preferencesService->updatePreferences(Auth::user()->id, $fields['categories'], $fields['sources']);

        return self::ok(null);
    }
}
