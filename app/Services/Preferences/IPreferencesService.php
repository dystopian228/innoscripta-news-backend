<?php

namespace App\Services\Preferences;

interface IPreferencesService
{

    /**
     * @param int $userId
     * @param array<string> $categories
     * @param array<string> $sources
     * @return void
     */
    public function updatePreferences(int $userId, array $categories, array $sources): void;

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserCategories($userId);

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserSources($userId);

    /**
     * @return mixed
     */
    public function getSources();
}
