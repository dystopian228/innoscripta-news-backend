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

class GuardianService extends BaseNewsService implements IGuardianService
{
    protected string $logFile = 'services logs/the_guardian.log';
    protected string $providerName = 'The Guardian';

    public function populateNews(int $pageSize, int $page, \DateTime $startDate, \DateTime $endDate): void
    {
        try {
            $response = Http::acceptJson()->withQueryParameters([
                'api-key' => Config::get('news_service.TheGuardianAPIKey'),
                'page-size' => $pageSize,
                'page' => $page,
                'from-date' => $startDate->format('Y-m-d'),
                'to-date	' => $endDate->format('Y-m-d')
            ])->get(Config::get('news_service.TheGuardianBaseUrl') . '/search');

            if ($response->successful()) {
                foreach ($response->json()['response']['results'] as $articleJson) {
                    $transformResult = ITransformer::transform($articleJson, NewsProvider::THE_GUARDIAN);
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

                    if (!empty($authors)) {
                        $existingAuthor = $this->authorRepository->findByProperty(AuthorDefinition::NAME, $authors[0][AuthorDefinition::NAME]);
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
