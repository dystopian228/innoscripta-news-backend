<?php

namespace App\Models;

use App\Entities\ArticleDefinition;
use App\Entities\AuthorDefinition;
use App\Entities\SourceDefinition;
use App\Entities\ArticleAuthorDefinition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ArticleDefinition::FILLABLES;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ArticleDefinition::HIDDEN;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    protected $with = [AuthorDefinition::TABLE_NAME, 'source'];

    public function authors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Author::class, ArticleAuthorDefinition::TABLE_NAME);
    }

    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
