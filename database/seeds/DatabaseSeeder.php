<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        // LLENAMOS DATOS FICTICIOS
        DB::statement('SET FOREIGN_KEY_CHECKS=0'); // Desctivamos las llaves foraneas

        factory(\App\Directorio::class, 10)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1'); // Activamos las llaves foraneas

    }
}
