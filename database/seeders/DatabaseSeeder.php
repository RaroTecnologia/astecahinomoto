<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Receita;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder para usuários de teste (opcional)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Criando categorias
        $categoriaPratosPrincipais = Categoria::create([
            'nome' => 'Pratos Principais',
            'slug' => 'pratos-principais'
        ]);

        $categoriaOriental = Categoria::create([
            'nome' => 'Oriental',
            'slug' => 'oriental'
        ]);

        // Criando receitas para Pratos Principais
        $categoriaPratosPrincipais->receitas()->create([
            'nome' => 'Frango ao Molho de Missô',
            'slug' => 'frango-ao-molho-de-misso',
            'ingredientes' => '2 filés de peito de frango, 2 colheres de sopa de missô, 1 colher de sopa de shoyu, 1 colher de sopa de azeite, ½ xícara de água',
            'modo_preparo' => '1. Misture os ingredientes. 2. Grelhe o frango até dourar. 3. Adicione o molho e cozinhe por mais 10 minutos.'
        ]);

        $categoriaPratosPrincipais->receitas()->create([
            'nome' => 'Yakissoba de Carne',
            'slug' => 'yakissoba-de-carne',
            'ingredientes' => '200g de carne bovina, 1 pacote de macarrão para yakissoba, molho shoyu, legumes variados',
            'modo_preparo' => '1. Cozinhe o macarrão. 2. Refogue a carne e os legumes. 3. Adicione o molho e misture tudo.'
        ]);

        // Criando receitas para Oriental
        $categoriaOriental->receitas()->create([
            'nome' => 'Sushi Tradicional',
            'slug' => 'sushi-tradicional',
            'ingredientes' => 'Arroz para sushi, peixe cru (salmão, atum), alga nori, molho de soja',
            'modo_preparo' => '1. Prepare o arroz de sushi. 2. Corte o peixe em fatias finas. 3. Monte o sushi com o arroz e o peixe sobre a alga.'
        ]);

        $categoriaOriental->receitas()->create([
            'nome' => 'Sopa de Missô',
            'slug' => 'sopa-de-misso',
            'ingredientes' => '3 colheres de sopa de missô, 4 xícaras de água, tofu, cebolinha picada',
            'modo_preparo' => '1. Ferva a água e adicione o missô. 2. Acrescente o tofu em cubos e a cebolinha. 3. Cozinhe por 5 minutos.'
        ]);

        // Criando notícias
        $this->call(NoticiaSeeder::class);
    }
}
