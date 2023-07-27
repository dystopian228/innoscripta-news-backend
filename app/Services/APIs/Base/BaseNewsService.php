<?php

namespace App\Services\APIs\Base;

use App\Repositories\Article\IArticleRepository;
use App\Repositories\Author\IAuthorRepository;
use App\Repositories\Source\ISourceRepository;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $logFile
 * @property string $providerName
 */
abstract class BaseNewsService implements IBaseNewsService
{
    /**
     * @var string $logFile
     */
    protected string $logFile;

    /**
     * @var string $providerName
     */
    protected string $providerName;

    /**
     * @var IArticleRepository $articleRepository
     */
    protected IArticleRepository $articleRepository;

    /**
     * @var IAuthorRepository $authorRepository
     */
    protected IAuthorRepository $authorRepository;

    /**
     * @var ISourceRepository $sourceRepository
     */
    protected ISourceRepository $sourceRepository;

    public function __construct(IArticleRepository $articleRepository_, IAuthorRepository $authorRepository_, ISourceRepository $sourceRepository_)
    {
        $this->articleRepository = $articleRepository_;
        $this->authorRepository = $authorRepository_;
        $this->sourceRepository = $sourceRepository_;
    }

    public abstract function populateNews(int $pageSize, int $page, \DateTime $startDate, \DateTime $endDate);

    protected function writeToLog(string $error): void
    {
        Storage::append($this->logFile, '====================================================');
        Storage::append($this->logFile, 'Provider: ' . $this->providerName . ' | ' . (new \DateTime())->format(\DateTime::ATOM));
        Storage::append($this->logFile, 'Error: ' . $error);
        Storage::append($this->logFile, '====================================================');
    }
}
