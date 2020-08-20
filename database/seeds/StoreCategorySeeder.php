<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('store_categories')->insert([
            'name' => 'Предметы',
            'name_en' => 'Items',
            'need_auth' => 1,
            'enabled' => 1,
            'distributor' => \App\Services\Store\Distributors\ItemsDistributor::class,
        ]);
        DB::table('store_categories')->insert([
            'name' => 'Группы',
            'name_en' => 'Perks',
            'need_auth' => 0,
            'enabled' => 1,
            'distributor' => \App\Services\Store\Distributors\GroupsDistributor::class,
        ]);
        DB::table('store_categories')->insert([
            'name' => 'Кейсы',
            'name_en' => 'Cases',
            'need_auth' => 1,
            'enabled' => 1,
            'distributor' => \App\Services\Store\Distributors\ItemsDistributor::class,
        ]);
        DB::table('store_categories')->insert([
            'name' => 'Монеты',
            'name_en' => 'Coins',
            'need_auth' => 0,
            'enabled' => 1,
            'distributor' => \App\Services\Store\Distributors\CoinsDistributor::class,
        ]);
    }
}
