<?php

namespace App\Models\Store;

use App\Models\Server;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property Category $category
 * @property Server|null $server
 * @property Collection $additionals
 * @property string $servername
 * @property Discount $discount
 * @property string $name
 * @property string $name_en
 * @property string $desrc
 * @property string $desrc_en
 * @property int|null $price_rub
 * @property int|null $price_coins
 * @property string $data
 * @property int $amount
 * @property int $count_buys
 * @property bool $enabled
 * @property string $img
 * @property \DateTime $created_at
 * @method static Builder hasCategory(?Category $category)
 * @method static Builder hasServer(?Server $server)
 * @method static Builder hasDiscount(?Discount $discount)
 * @method static Builder byName(?string $name)
 * @method static Builder isEnabled(bool $enabled = true)
 * @method static Builder isEnabledFull(bool $enabled = true)
 * @method static Builder popular()
 */
class Product extends Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'store_products';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'category_id', 'servername', 'name', 'name_en', 'descr', 'descr_en', 'price_rub', 'price_coins', 'discount_id',
        'data', 'amount', 'enabled'
    ];

    protected $attributes = [
        'discount_id' => null,
        'count_buys' => 0,
        'enabled' => true,
        'img' => ''
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'data', 'discount_id', 'count_buys', /*'discount', 'server'*/
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'enabled' => 'boolean',
        'created_at' => 'datetime'
    ];

    /**
     * @inheritDoc
     */
    protected $with = [
        'category', 'discount', 'server', 'additionals'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

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

    /**
     * Получить модель категории по отношению.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Получить модель скидки по отношению.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Получить модель сервера по отношению.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class, 'servername');
    }

    public function additionals()
    {
        return $this->hasMany(AdditionalProduct::class);
    }

    public function scopeHasCategory(Builder $query, ?Category $category): Builder
    {
        if (is_null($category)) {
            return $query;
        }

        return $query->where('category_id', $category->id);
    }

    public function scopeHasServer(Builder $query, ?Server $server): Builder
    {
        if (is_null($server)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($server) {
            return $query->where('servername', $server->name)
                ->orWhereNull('servername');
        });
    }

    public function scopeHasDiscount(Builder $query, ?Discount $discount): Builder
    {
        if (is_null($discount)) {
            return $query;
        }

        return $query->where('discount_id', $discount->id);
    }

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        if (empty($name)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($name) {
			return $query->where('name', 'LIKE', "$name%")
					->orWhere('name_en', 'LIKE', "$name%");
		});
    }

    public function scopeIsEnabled(Builder $query, bool $enabled = true): Builder
    {
        return $query->where('enabled', $enabled);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->latest('count_buys');
    }

    public static function setDiscountForAll(?Discount $discount): void
    {
        DB::table('store_products')->update([
            'discount_id' => is_null($discount) ? null : $discount->id
        ]);
    }

    public static function setDiscountFor(Discount $discount, ?Category $category, ?Server $server): void
    {
        $query = null;
        if (!is_null($category)) {
            $query = self::where('category_id', $category->id);
        }

        if (!is_null($server)) {
            $query = is_null($query)
                ? self::where('servername', $server->name)
                : $query->where('servername', $server->name);
        }

        if (is_null($query)) {
            self::setDiscountForAll($discount);
        } else {
            $query->update(['discount_id' => $discount->id]);
        }
    }

    public static function setDiscountForProducts(Discount $discount, array $products): void
    {
        self::whereIn('id', $products)->update(['discount_id' => $discount->id]);
    }

    public function getPrice(): int
    {
        $price = is_null($this->price_rub) ? $this->price_coins : $this->price_rub;
        if (!is_null($this->discount) && $this->discount->isActiveNow()) {
            $price -= floor($price * ($this->discount->discount / 100));
        }

        return $price;
    }
}
