<?php

namespace App\Models;

use App\Helpers\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $image
 * @property string $title
 * @property string $short_content
 * @property string $content
 * @property \DateTimeImmutable $created_at
 */
class News extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = [
        'title', 'short_content', 'content'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $dates = [
        'created_at'
    ];

    /**
     * @inheritDoc
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function (Model $model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
