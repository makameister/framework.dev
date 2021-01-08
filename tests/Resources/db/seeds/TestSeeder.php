<?php

use Phinx\Seed\AbstractSeed;

class TestSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create('fr_FR');

        $seeds = [];
        for ($i = 0; $i < 10; $i++) {
            $seeds[] = [
                'name' => $faker->sentence(rand(1, 4)),
                'slug' => $faker->slug(4, true),
                'content' => $faker->paragraph(5, true)
            ];
        }

        $this->table('test')->insert($seeds)->saveData();
    }
}
