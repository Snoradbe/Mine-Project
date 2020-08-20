<?php


namespace App\Models\Store;


use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $purchaseid
 * @property string $playername
 * @property int $productid
 * @property int $amount
 * @property string $servername
 * @property bool $isGiven
 * @property \DateTime $paymentDate
 * @property \DateTime $acceptDate
 */
class MineStore extends Model
{
    protected $table = 'store_awaiting';

    protected $fillable = [
        'purchaseid', 'playername', 'productid', 'amount', 'servername',
    ];

    protected $attributes = [
        'isGiven' => false
    ];

    protected $dates = [
        'paymentDate', 'acceptDate'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::creating(function (mineStore $model) {
            $model->paymentDate = $model->freshTimestamp();
            $model->acceptDate = $model->freshTimestamp();
        });
    }
}
