<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
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
        for ($i = 0; $i < 100; $i++) {
            $created_at = $faker->date('Y-m-d');
            $updated_at = $faker->date('Y-m-d', $created_at);

            $seeds[] = [
                'name' => $faker->sentence(rand(1, 4)),
                'slug' => $faker->slug(4, true),
                'content' => $faker->paragraph(5, true),
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];
        }

        $this->table('posts')->insert($seeds)->saveData();
    }
}
