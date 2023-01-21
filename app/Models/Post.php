<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @Post
 * @\App\Models\Post
 * @property string $title
 * @property string $site
 * @property int $score
 * @property string $author
 * @property int $created
 * @property int $comments
 */
class Post extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'site',
        'score',
        'author',
        'comments',
        'created',
        'comments',
    ];
}
