<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        Log::info('Iniciando criação de receita');
        Log::info('Dados recebidos:', $request->all());

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
            'status' => 'nullable|string'
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

        try {
            $receita = Receita::create($data);
            Log::info('Receita criada com sucesso', ['id' => $receita->id]);

            if ($request->query('redirect') === 'edit') {
                return redirect()
                    ->route('web-admin.receitas.edit', $receita->id)
                    ->with('success', 'Receita criada com sucesso e pronta para edição.');
            }

            return redirect()
                ->route('web-admin.receitas.index')
                ->with('success', 'Receita criada com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar receita: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar receita. Por favor, tente novamente.']);
        }
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
        Log::info('Iniciando atualização de receita', ['id' => $id]);
        Log::info('Dados recebidos:', $request->all());
        Log::info('Files recebidos:', $request->allFiles());

        try {
            $receita = Receita::findOrFail($id);
            Log::info('Receita encontrada:', ['receita' => $receita->toArray()]);

            $data = $request->validate([
                'nome' => 'required|string|max:255',
                'chamada' => 'nullable|string',
                'categoria_id' => 'nullable|exists:categorias,id',
                'dificuldade' => 'nullable|string',
                'tempo_preparo' => 'nullable|string',
                'ingredientes' => 'nullable|string',
                'modo_preparo' => 'nullable|string',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'video_url' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            $data['slug'] = Str::slug($data['nome']);

            if ($request->hasFile('imagem')) {
                Log::info('Processando upload de imagem');
                $file = $request->file('imagem');
                $fileName = $file->hashName();
                Log::info('Nome do arquivo gerado:', ['fileName' => $fileName]);

                if ($receita->imagem) {
                    Log::info('Deletando imagem antiga:', ['imagem' => $receita->imagem]);
                    Storage::delete([
                        'public/receitas/' . $receita->imagem,
                        'public/receitas/thumbnails/' . $receita->imagem
                    ]);
                }

                try {
                    $file->storeAs('receitas', $fileName, 'public');
                    Log::info('Imagem original salva com sucesso');

                    $data['imagem'] = $fileName;

                    if (!Storage::exists('public/receitas/thumbnails')) {
                        Storage::makeDirectory('public/receitas/thumbnails');
                        Log::info('Diretório de thumbnails criado');
                    }

                    $thumbnailPath = storage_path('app/public/receitas/thumbnails/' . $fileName);
                    Log::info('Iniciando redimensionamento da imagem', ['thumbnailPath' => $thumbnailPath]);

                    $this->resizeImage($file->getPathname(), $thumbnailPath, 300, 300);
                    Log::info('Thumbnail gerada com sucesso');
                } catch (\Exception $e) {
                    Log::error('Erro no processamento da imagem:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            DB::beginTransaction();
            try {
                $receita->update($data);
                DB::commit();
                Log::info('Receita atualizada com sucesso', ['id' => $id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Receita atualizada com sucesso!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro na transação do banco:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar receita:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar receita: ' . $e->getMessage()
            ], 422);
        }
    }

    private function resizeImage($sourcePath, $destinationPath, $width, $height)
    {
        Log::info('Iniciando redimensionamento', [
            'sourcePath' => $sourcePath,
            'destinationPath' => $destinationPath,
            'width' => $width,
            'height' => $height
        ]);

        try {
            $imageType = exif_imagetype($sourcePath);
            Log::info('Tipo de imagem detectado:', ['imageType' => $imageType]);

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
                    throw new \Exception("Tipo de imagem não suportado: " . $imageType);
            }

            list($originalWidth, $originalHeight) = getimagesize($sourcePath);
            Log::info('Dimensões originais:', [
                'width' => $originalWidth,
                'height' => $originalHeight
            ]);

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

            Log::info('Redimensionamento concluído com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro no redimensionamento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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
