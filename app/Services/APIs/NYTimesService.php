<?php

namespace App\Services\APIs;

use App\Entities\ArticleDefinition;
use App\Entities\AuthorDefinition;
use App\Entities\SourceDefinition;
use App\Enums\NewsProvider;
use App\Models\Article;
use App\Models\Author;
use App\Models\Source;
use App\Services\APIs\Base\BaseNewsService;
use App\Transformers\ITransformer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class NYTimesService extends BaseNewsService implements INYTimesService
{
    protected string $logFile = 'services logs/ny_times.log';
    protected string $providerName = 'New York Times API';

    public function populateNews(int $pageSize, int $page, \DateTime $startDate, \DateTime $endDate): void
    {
        try {
            $response = Http::acceptJson()->withQueryParameters([
                'api-key' => Config::get('news_service.NYTimesAPIKey'),
                'page' => $page,
                'begin_date' => $startDate->format('Ymd'),
                'end_date' => $endDate->format('Ymd')
            ])->get(Config::get('news_service.NYTimesBaseUrl') . '/articlesearch.json');

            if ($response->successful()) {
                foreach ($response->json()['response']['docs'] as $articleJson) {
                    $transformResult = ITransformer::transform($articleJson, NewsProvider::NY_TIMES);

                    /** @var Article $article */
                    $article = $transformResult['article'];
                    /** @var array<Author> $author */
                    $authors = $transformResult['authors'];
                    /** @var Source $source */
                    $source = $transformResult['source'];

                    if ($this->articleRepository->exists([ArticleDefinition::ARTICLE_URL => $article[ArticleDefinition::ARTICLE_URL]])) {
                        continue;
                    }

                    $existingSource = $this->sourceRepository->findByProperty(SourceDefinition::SYMBOL, $source[SourceDefinition::SYMBOL]);
                    if (!$existingSource->isEmpty()) {
                        $article->source()->associate($existingSource[0]);
                    } else {
                        $this->sourceRepository->save($source);
                        $article->source()->associate($source);
                    }

                    $this->articleRepository->save($article);

                    $authorField = AuthorDefinition::ORGANIZATION;
                    if (isset($authors[0][AuthorDefinition::NAME])) {
                        $authorField = AuthorDefinition::NAME;
                    }
                    if (!empty($authors)) {
                        $existingAuthor = $this->authorRepository->findByProperty($authorField, $authors[0][$authorField]);
                        if ($existingAuthor) {
                            $authors[0] = $existingAuthor;
                        }
                        $article->authors()->attach($authors[0]);
                    }

                }
            } else {
                $this->writeToLog($response->body());
            }
        } catch (\Exception $exception) {
            report($exception);
            $this->writeToLog($exception);
        }
    }
}
