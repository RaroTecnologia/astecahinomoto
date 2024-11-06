<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Receita::with('categoria');

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        $receitas = $query->latest()->paginate(10);

        $categorias = Categoria::where('tipo', 'receita')
            ->orderBy('nome')
            ->get();

        return view('web-admin.receitas.index', compact('receitas', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::where('tipo', 'receita')
            ->orderBy('nome')
            ->get();

        return view('web-admin.receitas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'chamada' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video_url' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['nome']);

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $fileName = $file->hashName();

            $file->storeAs('receitas', $fileName, 'public');
            $data['imagem'] = $fileName;

            // Criar diretório para thumbnails se não existir
            if (!Storage::exists('public/receitas/thumbnails')) {
                Storage::makeDirectory('public/receitas/thumbnails');
            }

            $thumbnailPath = storage_path('app/public/receitas/thumbnails/' . $fileName);
            $this->resizeImage($file->getPathname(), $thumbnailPath, 300, 300);
        }

        $receita = Receita::create($data);

        if ($request->query('redirect') === 'edit') {
            return redirect()
                ->route('web-admin.receitas.edit', $receita->id)
                ->with('success', 'Receita criada com sucesso e pronta para edição.');
        }

        return redirect()
            ->route('web-admin.receitas.index')
            ->with('success', 'Receita criada com sucesso.');
    }

    public function edit($id)
    {
        $receita = Receita::findOrFail($id);

        $categorias = Categoria::where('tipo', 'receita')
            ->orderBy('nome')
            ->get();

        return view('web-admin.receitas.edit', compact('receita', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $receita = Receita::findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'chamada' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
            'dificuldade' => 'nullable|string',
            'tempo_preparo' => 'nullable|string',
            'ingredientes' => 'nullable|string',
            'modo_preparo' => 'nullable|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video_url' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['nome']);

        try {
            if ($request->hasFile('imagem')) {
                // Deletar imagem antiga
                if ($receita->imagem) {
                    Storage::delete([
                        'public/receitas/' . $receita->imagem,
                        'public/receitas/thumbnails/' . $receita->imagem
                    ]);
                }

                $file = $request->file('imagem');
                $fileName = $file->hashName();

                $file->storeAs('receitas', $fileName, 'public');
                $data['imagem'] = $fileName;

                $thumbnailPath = storage_path('app/public/receitas/thumbnails/' . $fileName);
                $this->resizeImage($file->getPathname(), $thumbnailPath, 300, 300);
            }

            $receita->update($data);

            return redirect()
                ->route('web-admin.receitas.index')
                ->with('success', 'Receita atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar receita. Por favor, tente novamente.');
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

    public function destroy($id)
    {
        try {
            $receita = Receita::findOrFail($id);

            // Deletar imagens
            if ($receita->imagem) {
                Storage::delete([
                    'public/receitas/' . $receita->imagem,
                    'public/receitas/thumbnails/' . $receita->imagem
                ]);
            }

            $receita->delete();

            return redirect()
                ->route('web-admin.receitas.index')
                ->with('success', 'Receita excluída com sucesso.');
        } catch (\Exception $e) {
            return redirect()
                ->route('web-admin.receitas.index')
                ->withErrors('Ocorreu um erro ao excluir a receita.');
        }
    }
}
