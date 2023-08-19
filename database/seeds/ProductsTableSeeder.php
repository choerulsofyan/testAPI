<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'name' => $faker->unique()->word,
                'description' => $faker->unique()->sentence,
                'qty' => $faker->randomDigitNot(0)
            ]);
        }
    }
}
