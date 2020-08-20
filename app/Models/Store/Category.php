<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $distributor
 * @property bool $enabled
 * @property bool $need_auth
 * @property string $name
 * @property string $name_en
 * @method static Builder isEnabled(bool $enabled = true)
 */
class Category extends Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'store_categories';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'distributor', 'enabled', 'need_auth', 'name', 'name_en'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'distributor'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $casts = [
        'enabled' => 'boolean',
        'need_auth' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function scopeIsEnabled(Builder $query, bool $enabled = true): Builder
    {
        return $query->where('enabled', $enabled);
    }

    /**
     * Получить название товара.
     * Можно указать язык $lang, например: en | ru ...
     *
     * @param string|null $lang
     * @return string
     */
    public function getName(?string $lang = null): string
    {
        if (is_null($lang)) {
            return $this->attributes['name'];
        }

        return $this->attributes['name_' . $lang] ?? $this->attributes['name'];
    }
}
