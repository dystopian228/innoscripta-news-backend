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
use App\Transformers\NewsAPITransformer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class NewsAPIService extends BaseNewsService implements INewsAPIService
{
    protected string $logFile = 'services logs/news_api.log';
    protected string $providerName = 'News API';

    public function populateNews(int $pageSize, int $page, \DateTime $startDate, \DateTime $endDate): void
    {

        try {
            $response = Http::acceptJson()->withHeader('X-Api-Key', Config::get('news_service.NewsAPIKey'))->withQueryParameters([
                'pageSize' => $pageSize,
                'page' => $page,
                'from' => $startDate->format(\DateTime::ATOM),
                'to' => $endDate->format(\DateTime::ATOM),
                'language' => 'en'
            ])->get(Config::get('news_service.NewsAPIBaseUrl') . '/top-headlines');

            if ($response->successful()) {
                foreach ($response->json()['articles'] as $articleJson) {
                    $transformResult = ITransformer::transform($articleJson, NewsProvider::NEWS_API);
                    /** @var Article $article */
                    $article = $transformResult['article'];
                    /** @var array<Author> $author */
                    $authors = $transformResult['authors'];
                    /** @var Source $source */
                    $source = $transformResult['source'];


                    if ($this->articleRepository->exists([ArticleDefinition::ARTICLE_URL => $article[ArticleDefinition::ARTICLE_URL]])) {
                        continue;
                    }

                    $sourceField = SourceDefinition::NAME;
                    if (isset($source[SourceDefinition::SYMBOL])) {
                        $sourceField = SourceDefinition::SYMBOL;
                    }

                    $existingSource = $this->sourceRepository->findByProperty($sourceField, $source[$sourceField]);
                    if (!$existingSource->isEmpty()) {
                        $article->source()->associate($existingSource[0]);
//                        dd($article);
                        if (isset($existingSource[0][SourceDefinition::MAIN_CATEGORY])) {
                            $article[ArticleDefinition::CATEGORY] = $existingSource[0][SourceDefinition::MAIN_CATEGORY];
                        }
                    } else {
//                        dd($source);
                        $this->sourceRepository->save($source);
                        $article->source()->associate($source);
//                        dd($article);
                    }

                    $this->articleRepository->save($article);

                    if (!empty($authors)) {
                        $authorField = AuthorDefinition::ORGANIZATION;
                        if (isset($authors[0][AuthorDefinition::NAME])) {
                            $authorField = AuthorDefinition::NAME;
                        }

                        $existingAuthor = $this->authorRepository->findByProperty($authorField, $authors[0][$authorField]);
                        if (!$existingAuthor->isEmpty()) {
                            $authors[0] = $existingAuthor[0];
                        }

                        $upsertedAuthor = $this->authorRepository->save($authors[0]);
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

    function populateSources(): void
    {
        try {
            $response = Http::acceptJson()->withHeader('X-Api-Key', Config::get('news_service.NewsAPIKey'))
                ->get(Config::get('news_service.NewsAPIBaseUrl') . '/top-headlines/sources');


            if ($response->successful()) {
                foreach ($response->json()['sources'] as $sourceJson) {
                    $source = NewsAPITransformer::transformSource($sourceJson);

                    //Check if the source already exists
                    $field = SourceDefinition::NAME;
                    if (isset($source[SourceDefinition::SYMBOL])) {
                        $field = SourceDefinition::SYMBOL;
                    }
                    $sources = $this->sourceRepository->findByProperty($field, $source[$field]);
                    if ($sources->isEmpty()) {
                        //If it doesn't exist, insert it into database.
                        $source = $this->sourceRepository->save($source);
                    } else {
                        //Otherwise, update existing fields and save it.
                        $sources[0][SourceDefinition::NAME] = $source[SourceDefinition::NAME];

                        if (!empty($source[SourceDefinition::DESCRIPTION])) {
                            $sources[0][SourceDefinition::DESCRIPTION] = $source[SourceDefinition::DESCRIPTION];
                        }

                        if (!empty($source[SourceDefinition::URL])) {
                            $sources[0][SourceDefinition::URL] = $source[SourceDefinition::URL];
                        }

                        if (!empty($source[SourceDefinition::MAIN_CATEGORY])) {
                            $sources[0][SourceDefinition::MAIN_CATEGORY] = $source[SourceDefinition::MAIN_CATEGORY];
                        }

                        if (!empty($source[SourceDefinition::COUNTRY])) {
                            $sources[0][SourceDefinition::COUNTRY] = $source[SourceDefinition::COUNTRY];
                        }

                        $this->sourceRepository->save($sources[0]);
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
