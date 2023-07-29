<?php

namespace App\Services\Preferences;

use App\Entities\BaseFields;
use App\Entities\SourceDefinition;
use App\Entities\UserCategoryDefinition;
use App\Entities\UserDefinition;
use App\Entities\UserSourceDefinition;
use App\Models\User;
use App\Models\UserCategory;
use App\Repositories\Source\ISourceRepository;
use App\Repositories\User\IUserCategoryRepository;
use App\Repositories\User\IUserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PreferencesService implements IPreferencesService
{
    private IUserRepository $userRepository;
    private IUserCategoryRepository $userCategoryRepository;
    private ISourceRepository $sourceRepository;

    public function __construct(IUserRepository $userRepository_, ISourceRepository $sourceRepository_, IUserCategoryRepository $userCategoryRepository_)
    {
        $this->userRepository = $userRepository_;
        $this->sourceRepository = $sourceRepository_;
        $this->userCategoryRepository = $userCategoryRepository_;
    }

    public function updatePreferences(int $userId, array $categories, array $sources): void
    {
        $usersCollection = $this->userRepository->with(tables: [UserDefinition::SOURCES_RELATION => [], UserDefinition::CATEGORIES_RELATION => []], conditions: ['id' => $userId]);
        if ($usersCollection->isEmpty()) {
            throw new ModelNotFoundException();
        }

        /**
         * @var User $user
         */
        $user = $usersCollection[0];

        $sourcesModels = $this->sourceRepository->where(inConditions: [SourceDefinition::SYMBOL => $sources]);
        $sourcesIds = $sourcesModels->map(function ($model) {
            return $model->id;
        })->toArray();
        $this->userRepository->syncRelation(UserDefinition::SOURCES_RELATION, $user, $sourcesIds);

        $currentCategories = $user->categories->map(function ($model) {
            return $model->category;
        })->toArray();

        $categoriesToDelete = array_diff($currentCategories, $categories);
        $categoriesToAdd = array_diff($categories, $currentCategories);

        $userCategories = [];
        foreach ($categoriesToAdd as $category) {
            $userCategory = new UserCategory();
            $userCategory[UserCategoryDefinition::CATEGORY] = $category;
            $userCategories[] = $userCategory;
        }

        $this->userCategoryRepository->deleteWhere([UserCategoryDefinition::USER_ID => $user->id], inConditions: [UserCategoryDefinition::CATEGORY => $categoriesToDelete]);
        $this->userRepository->saveMany(UserDefinition::CATEGORIES_RELATION, $user, $userCategories);


    }

    public function getUserSources($userId)
    {
        $usersCollection = $this->userRepository->with(tables: [UserDefinition::SOURCES_RELATION => []], conditions: ['id' => $userId], columns: [BaseFields::ID, SourceDefinition::TABLE_NAME . '.' . SourceDefinition::SYMBOL]);
        if ($usersCollection->isEmpty()) {
            throw new ModelNotFoundException();
        }

        /**
         * @var User $user
         */
        $user = $usersCollection[0];
        $sources = $user->sources->map(function ($model) {
            return $model->symbol;
        })->toArray();
        return $sources;
    }

    public function getUserCategories($userId)
    {
        $usersCollection = $this->userRepository->with(tables: [UserDefinition::CATEGORIES_RELATION => []], conditions: ['id' => $userId], columns: [BaseFields::ID, UserCategoryDefinition::TABLE_NAME . '.' . UserCategoryDefinition::CATEGORY]);
        if ($usersCollection->isEmpty()) {
            throw new ModelNotFoundException();
        }

        /**
         * @var User $user
         */
        $user = $usersCollection[0];
        $categories = $user->categories->map(function ($model) {
            return $model->category;
        })->toArray();
        return $categories;
    }

    public function getSources() {
        return $this->sourceRepository->all(order: [SourceDefinition::NAME => 'ASC']);
    }
}
