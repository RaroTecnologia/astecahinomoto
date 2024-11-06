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
        $query = Noticia::with('categoria');

        if ($request->filled('busca')) {
            $query->where('titulo', 'like', '%' . $request->busca . '%');
        }

        $noticias = $query->paginate(10);

        return view('web-admin.noticias.index', compact('noticias'));
    }

    public function create()
    {
        $categorias = Categoria::where('tipo', 'noticia')->get();
        return view('web-admin.noticias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
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
                $file = $request->file('imagem');
                $fileName = $file->hashName();

                $file->storeAs('noticias', $fileName, 'public');
                $data['imagem'] = $fileName;

                $thumbnailPath = storage_path('app/public/noticias/thumbnails/' . $fileName);
                $this->resizeImage($file->getPathname(), $thumbnailPath, 300, 300);
            }

            $noticia = Noticia::create($data);

            if ($request->query('redirect') === 'edit') {
                return redirect()->route('web-admin.noticias.edit', $noticia->id)->with('success', 'Notícia criada com sucesso e pronta para edição.');
            }

            return redirect()->route('web-admin.noticias.index')->with('success', 'Notícia criada com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar notícia: ' . $e->getMessage());
            return redirect()->back()->withErrors('Ocorreu um erro ao criar a notícia.');
        }
    }

    public function edit(Noticia $noticia)
    {
        $categorias = Categoria::where('tipo', 'noticia')->get();
        return view('web-admin.noticias.edit', compact('noticia', 'categorias'));
    }

    public function update(Request $request, Noticia $noticia)
    {
        Log::info('Iniciando atualização da notícia');
        Log::info('Todos os dados recebidos:', $request->all());

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
            $this->deleteImage($noticia);

            $file = $request->file('imagem');
            $fileName = $file->hashName();

            $file->storeAs('noticias', $fileName, 'public');
            $data['imagem'] = $fileName;

            $thumbnailPath = storage_path('app/public/noticias/thumbnails/' . $fileName);
            $this->resizeImage($file->getPathname(), $thumbnailPath, 300, 300);
        }

        Log::info('Atualizando notícia com os dados:', $data);
        $noticia->update($data);
        Log::info('Notícia atualizada. Novo conteúdo:', ['conteudo' => $noticia->conteudo]);

        return redirect()->route('web-admin.noticias.index')->with('success', 'Notícia atualizada com sucesso!');
    }

    public function destroy(Noticia $noticia)
    {
        try {
            $this->deleteImage($noticia);
            $noticia->delete();

            return redirect()->route('web-admin.noticias.index')->with('success', 'Notícia excluída com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir notícia: ' . $e->getMessage());
            return redirect()->route('web-admin.noticias.index')->withErrors('Ocorreu um erro ao excluir a notícia.');
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
