<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreProductsSeeder extends Seeder
{
    private $servers = ['SlighTech', 'JustPvP', 'Event'];

    private function randServer()
    {
        return $this->servers[rand(0, count($this->servers) - 1)];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('store_products')->insert([
            'category_id' => 1,
            'servername' => $this->randServer(),
            'name' => 'Камень',
            'name_en' => 'Stone',
            'descr' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, deserunt, quia. Asperiores assumenda dolorum earum id, iusto nemo, nesciunt nobis, quos reiciendis sit suscipit tenetur voluptatibus. A assumenda beatae molestiae.',
            'descr_en' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, deserunt, quia. Asperiores assumenda dolorum earum id, iusto nemo, nesciunt nobis, quos reiciendis sit suscipit tenetur voluptatibus. A assumenda beatae molestiae.',
            'price_rub' => null,
            'price_coins' => 1,
            'data' => '1',
            'amount' => 64,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 1,
            'servername' => $this->randServer(),
            'name' => 'Блок травы',
            'name_en' => 'Grass',
            'descr' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, deserunt, quia. Asperiores assumenda dolorum earum id, iusto nemo, nesciunt nobis, quos reiciendis sit suscipit tenetur voluptatibus. A assumenda beatae molestiae.',
            'descr_en' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, deserunt, quia. Asperiores assumenda dolorum earum id, iusto nemo, nesciunt nobis, quos reiciendis sit suscipit tenetur voluptatibus. A assumenda beatae molestiae.',
            'price_rub' => null,
            'price_coins' => 2,
            'data' => '2',
            'amount' => 64,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 1,
            'servername' => $this->randServer(),
            'name' => 'Земля',
            'name_en' => 'Dirt',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => null,
            'price_coins' => 1,
            'data' => '3',
            'amount' => 64,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 1,
            'servername' => $this->randServer(),
            'name' => 'Булыжник',
            'name_en' => 'Cobblestone',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => null,
            'price_coins' => 1,
            'data' => '4',
            'amount' => 64,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 1,
            'servername' => $this->randServer(),
            'name' => 'Доски',
            'name_en' => 'Planking',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => null,
            'price_coins' => 3,
            'data' => '5',
            'amount' => 64,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 2,
            'servername' => null,
            'name' => 'Vip',
            'name_en' => 'Vip',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => 150,
            'price_coins' => null,
            'data' => 'vip',
            'amount' => 1,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 2,
            'servername' => null,
            'name' => 'Premium',
            'name_en' => 'Premium',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => 300,
            'price_coins' => null,
            'data' => 'premium',
            'amount' => 1,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 3,
            'servername' => null,
            'name' => 'Золотой кейс',
            'name_en' => 'Golden Case',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => null,
            'price_coins' => 200,
            'data' => '1',
            'amount' => 1,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 3,
            'servername' => null,
            'name' => 'Золотые кейсы',
            'name_en' => 'Golden Cases',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => null,
            'price_coins' => 900,
            'data' => '1',
            'amount' => 5,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
        DB::table('store_products')->insert([
            'category_id' => 4,
            'servername' => null,
            'name' => 'Монеты',
            'name_en' => 'Coins',
            'descr' => '',
            'descr_en' => '',
            'price_rub' => 100,
            'price_coins' => null,
            'data' => '',
            'amount' => 1000,
            'enabled' => 1,
            'discount_id' => null,
            'img' => '',
            'count_buys' => 0,
            'created_at' => '2020-05-23 01:01:01'
        ]);
    }
}
