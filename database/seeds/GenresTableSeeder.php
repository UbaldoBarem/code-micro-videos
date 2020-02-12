<?php

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Genre::class,1)->create(['name'=>'Ação','description'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.']);
        factory(Genre::class,1)->create(['name'=>'Aventura','description'=>'Sed vel metus sed enim faucibus laoreet vitae suscipit tellus.',]);
        factory(Genre::class,1)->create(['name'=>'Cinema de arte','description'=>'Curabitur dignissim purus sem, vitae ultricies arcu sodales ac.',]);
        factory(Genre::class,1)->create(['name'=>'Chanchada','description'=>' Phasellus malesuada, nisi in porttitor tincidunt, mi felis tempor lorem, in ornareante in ex.',]);
        factory(Genre::class,1)->create(['name'=>'Comédia','description'=>'Donec in nibh dapibus, porttitor lacus sit amet, sodales risus.',]);
        factory(Genre::class,1)->create(['name'=>'Comédia romântica','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Comédia dramática','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Comédia de ação','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Dança','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Documentário','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Docuficção','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Drama','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Espionagem','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Escolar','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Faroeste (ou western)','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Fantasia científica','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Ficção científica','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Filmes de guerra','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Fantasia','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Guerra','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Musical','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Filme policial','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Romance','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Seriado','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Suspense','description'=>'',]);
        factory(Genre::class,1)->create(['name'=>'Terror','description'=>'']);
    }
}
