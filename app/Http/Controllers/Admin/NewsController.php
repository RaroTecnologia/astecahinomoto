<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Iniciando listagem de notícias', [
            'filtros' => $request->all()
        ]);

        $query = Noticia::with('categoria');

        if ($request->filled('busca')) {
            Log::info('Aplicando filtro de busca', ['termo' => $request->busca]);
            $query->where('titulo', 'like', '%' . $request->busca . '%');
        }

        $noticias = $query->paginate(10);

        Log::info('Notícias carregadas', [
            'total' => $noticias->total(),
            'por_pagina' => $noticias->perPage(),
            'pagina_atual' => $noticias->currentPage()
        ]);

        return view('web-admin.noticias.index', compact('noticias'));
    }

    public function create()
    {
        Log::info('Acessando formulário de criação de notícia');
        $categorias = Categoria::where('tipo', 'noticia')->get();

        Log::info('Categorias carregadas', [
            'total_categorias' => $categorias->count(),
            'categorias' => $categorias->pluck('nome', 'id')
        ]);

        return view('web-admin.noticias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Iniciando criação de notícia', [
                'dados' => $request->all(),
                'headers' => $request->headers->all(),
                'method' => $request->method(),
                'url' => $request->url(),
                'query_params' => $request->query()
            ]);

            // Validação
            $validator = validator($request->all(), [
                'titulo' => 'required|string|max:255',
                'conteudo' => 'nullable|string',
                'status' => 'nullable|string|max:20',
                'categoria_id' => 'nullable|exists:categorias,id',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'publicado_em' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                Log::error('Erro de validação', [
                    'erros' => $validator->errors()->toArray()
                ]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();
            $data['slug'] = Str::slug($data['titulo']);

            // Define status padrão se não foi enviado
            $data['status'] = $data['status'] ?? 'rascunho';

            Log::info('Dados validados', ['data' => $data]);

            // Criar a notícia
            $noticia = Noticia::create($data);

            Log::info('Notícia criada com sucesso', [
                'id' => $noticia->id,
                'titulo' => $noticia->titulo,
                'slug' => $noticia->slug
            ]);

            if ($request->query('redirect') === 'edit') {
                $redirectUrl = route('web-admin.noticias.edit', $noticia->id);
                Log::info('Redirecionando para edição', ['url' => $redirectUrl]);

                return redirect($redirectUrl)
                    ->with('success', 'Notícia criada com sucesso e pronta para edição.');
            }

            Log::info('Redirecionando para listagem');
            return redirect()->route('web-admin.noticias.index')
                ->with('success', 'Notícia criada com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar notícia', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors('Ocorreu um erro ao criar a notícia: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Noticia $noticia)
    {
        Log::info('Acessando edição de notícia', ['id' => $noticia->id]);
        $categorias = Categoria::where('tipo', 'noticia')->get();

        Log::info('Dados carregados para edição', [
            'noticia' => $noticia->toArray(),
            'total_categorias' => $categorias->count()
        ]);

        return view('web-admin.noticias.edit', compact('noticia', 'categorias'));
    }

    public function update(Request $request, Noticia $noticia)
    {
        Log::info('Iniciando atualização da notícia', [
            'id' => $noticia->id,
            'dados_atuais' => $noticia->toArray(),
            'dados_novos' => $request->except('imagem'),
            'tem_imagem_nova' => $request->hasFile('imagem')
        ]);

        try {
            $data = $request->validate([
                'titulo' => 'required|string|max:255',
                'conteudo' => 'nullable|string',
                'status' => 'nullable|string|max:20',
                'categoria_id' => 'nullable|exists:categorias,id',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'publicado_em' => 'nullable|date',
            ]);

            $data['slug'] = Str::slug($data['titulo']);

            if ($request->hasFile('imagem')) {
                Log::info('Processando nova imagem', [
                    'imagem_antiga' => $noticia->imagem
                ]);

                $this->deleteImage($noticia);
                Log::info('Imagem antiga deletada');

                $file = $request->file('imagem');
                $fileName = $file->hashName();

                Log::info('Salvando nova imagem', [
                    'nome_original' => $file->getClientOriginalName(),
                    'nome_hash' => $fileName
                ]);

                $file->storeAs('noticias', $fileName, 'public');
                $data['imagem'] = $fileName;

                $thumbnailPath = storage_path('app/public/noticias/thumbnails/' . $fileName);
                $this->resizeImage($file->getPathname(), $thumbnailPath, 300, 300);
                Log::info('Nova thumbnail criada');
            }

            $noticia->update($data);
            Log::info('Notícia atualizada com sucesso', [
                'id' => $noticia->id,
                'dados_atualizados' => $data
            ]);

            return redirect()->route('web-admin.noticias.index')
                ->with('success', 'Notícia atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar notícia', [
                'id' => $noticia->id,
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('Ocorreu um erro ao atualizar a notícia.');
        }
    }

    public function destroy(Noticia $noticia)
    {
        try {
            Log::info('Iniciando exclusão de notícia', [
                'id' => $noticia->id,
                'dados' => $noticia->toArray()
            ]);

            $this->deleteImage($noticia);
            Log::info('Imagens da notícia deletadas');

            $noticia->delete();
            Log::info('Notícia excluída com sucesso');

            return redirect()->route('web-admin.noticias.index')
                ->with('success', 'Notícia excluída com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir notícia', [
                'id' => $noticia->id,
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('web-admin.noticias.index')
                ->withErrors('Ocorreu um erro ao excluir a notícia.');
        }
    }

    private function resizeImage($sourcePath, $destinationPath, $width, $height)
    {
        $imageType = exif_imagetype($sourcePath);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new \Exception("Tipo de imagem não suportado");
        }

        list($originalWidth, $originalHeight) = getimagesize($sourcePath);
        $aspectRatio = $originalWidth / $originalHeight;

        if ($width / $height > $aspectRatio) {
            $width = $height * $aspectRatio;
        } else {
            $height = $width / $aspectRatio;
        }

        $newImage = imagecreatetruecolor($width, $height);

        if ($imageType == IMAGETYPE_PNG) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $destinationPath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $destinationPath);
                break;
            case IMAGETYPE_GIF:
                imagegif($newImage, $destinationPath);
                break;
        }

        imagedestroy($newImage);
        imagedestroy($sourceImage);
    }

    private function deleteImage($noticia)
    {
        if ($noticia->imagem && Storage::exists('public/noticias/' . $noticia->imagem)) {
            Storage::delete('public/noticias/' . $noticia->imagem);
        }

        if (Storage::exists('public/noticias/thumbnails/' . $noticia->imagem)) {
            Storage::delete('public/noticias/thumbnails/' . $noticia->imagem);
        }
    }
}
