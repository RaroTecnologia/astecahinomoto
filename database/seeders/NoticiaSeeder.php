<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Noticia;
use App\Models\Categoria;
use Illuminate\Support\Str;

class NoticiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter uma categoria de teste
        $categoria = Categoria::firstOrCreate(['nome' => 'Notícias Gerais', 'slug' => Str::slug('Notícias Gerais'), 'tipo' => 'noticias']);

        // Criar algumas notícias de exemplo
        Noticia::create([
            'titulo' => 'Nova Parceria Anunciada',
            'slug' => Str::slug('Nova Parceria Anunciada'),
            'conteudo' => 'Conteúdo da notícia sobre a nova parceria anunciada pela empresa.',
            'imagem' => 'https://via.placeholder.com/600x400',
            'categoria_id' => $categoria->id,
            'publicado_em' => now(),
        ]);

        Noticia::create([
            'titulo' => 'Lançamento de Produto Inovador',
            'slug' => Str::slug('Lançamento de Produto Inovador'),
            'conteudo' => 'Detalhes sobre o novo produto inovador que será lançado em breve.',
            'imagem' => 'https://via.placeholder.com/600x400',
            'categoria_id' => $categoria->id,
            'publicado_em' => now(),
        ]);

        Noticia::create([
            'titulo' => 'Expansão Internacional da Empresa',
            'slug' => Str::slug('Expansão Internacional da Empresa'),
            'conteudo' => 'A empresa anunciou planos de expansão para novos mercados internacionais.',
            'imagem' => 'https://via.placeholder.com/600x400',
            'categoria_id' => $categoria->id,
            'publicado_em' => now(),
        ]);
    }
}
