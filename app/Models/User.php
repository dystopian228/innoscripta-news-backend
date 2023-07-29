<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Entities\ArticleAuthorDefinition;
use App\Entities\UserCategoryDefinition;
use App\Entities\UserDefinition;
use App\Entities\UserSourceDefinition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = UserDefinition::FILLABLES;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = UserDefinition::HIDDEN;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        UserDefinition::EMAIL_VERIFIED_AT => 'datetime',
        UserDefinition::PASSWORD => 'hashed',
    ];


    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(Source::class, UserSourceDefinition::TABLE_NAME);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(UserCategory::class);
    }
}
