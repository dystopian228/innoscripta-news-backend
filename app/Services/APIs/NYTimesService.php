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

        $response = Http::acceptJson()->withQueryParameters([
            'api-key' => Config::get('NYTimesAPIKey'),
            'pageSize' => $pageSize,
            'page' => $page,
            'begin_date' => $startDate->format('Ymd'),
            'end_date' => $endDate->format('Ymd')
        ])->get(Config::get('NYTimesBaseUrl').'/articlesearch.json');

        if ($response->successful()) {
            foreach ($response->json()['response']['docs'] as $articleJson) {
                $transformResult = ITransformer::transform($articleJson, NewsProvider::NY_TIMES);

                /** @var Article $article */
                $article = $transformResult['article'];
                /** @var array<Author> $author */
                $authors = $transformResult['authors'];
                /** @var Source $source*/
                $source = $transformResult['article'];

                if ($this->articleRepository->exists([ArticleDefinition::ARTICLE_URL => $article[ArticleDefinition::ARTICLE_URL]])) {
                    continue;
                }

                if ($existingSource = $this->sourceRepository->findByProperty(SourceDefinition::SYMBOL, $source[SourceDefinition::SYMBOL])) {
                    $article->source()->associate($existingSource);
                } else {
                    $article->source()->associate($source);
                }

                $existingAuthor = $this->authorRepository->findByProperty(AuthorDefinition::NAME, $authors[0][AuthorDefinition::NAME]);
                if ($existingAuthor) {
                    $authors[0] = $existingAuthor;
                }

                $article->authors()->attach($authors[0]);

                $this->articleRepository->save($article);
            }
        } else {
            $this->writeToLog($response->body());
        }
    }
}
