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

    public function populateNews(int $pageSize, int $page, \DateTime $startDate, \DateTime $endDate): array
    {

        try {
            $response = Http::acceptJson()->withHeader('X-Api-Key', Config::get('NEWS_API_API_KEY'))->withQueryParameters([
                'pageSize' => $pageSize,
                'page' => $page,
                'from' => $startDate->format(\DateTime::ATOM),
                'to' => $endDate->format(\DateTime::ATOM)
            ])->get(Config::get('NEWS_API_URL') . '/everything');

            if ($response->successful()) {
                foreach ($response->json()['articles'] as $articleJson) {
                    $transformResult = ITransformer::transform($articleJson, NewsProvider::NEWS_API);

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
                        if ($existingSource[SourceDefinition::MAIN_CATEGORY]) {
                            $article[ArticleDefinition::CATEGORY] = $existingSource[SourceDefinition::MAIN_CATEGORY];
                        }
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
        } catch (\Exception $exception) {
            report($exception);
            $this->writeToLog($exception);
        }

        return [];
    }

    function populateSources(): void
    {
        try {
            $response = Http::acceptJson()->withHeader('X-Api-Key', Config::get('NEWS_API_API_KEY'))
                ->get(Config::get('NEWS_API_URL') . '/top-headlines/sources');


            if ($response->successful()) {
                foreach ($response->json()['sources'] as $sourceJson) {
                    $sourcesResult = NewsAPITransformer::transformSource($sourceJson);

                    foreach ($sourcesResult as $source) {

                        //Check if the source already exists
                        $sources = $this->sourceRepository->findByProperty(SourceDefinition::SYMBOL, $source->symbol);
                        if ($sources->isEmpty()) {
                            //If it doesn't exist, insert it into database.
                            $this->sourceRepository->save($source);
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
