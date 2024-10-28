<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        // Carrega apenas categorias principais (sem parent_id)
        $categorias = Categoria::with('subcategorias')->whereNull('parent_id')->get();

        return view('web-admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        // Carrega todas as categorias principais (que não possuem parent_id)
        $categorias = Categoria::whereNull('parent_id')->get();

        return view('web-admin.categorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {

        //dd($request->all());

        // Gerar o slug automaticamente
        $slug = Str::slug($request->input('nome'));

        // Criar a categoria com o tipo, slug e nível gerados
        Categoria::create([
            'nome' => $request->input('nome'),
            'slug' => $slug,
            'descricao' => $request->input('descricao'),
            'parent_id' => $request->input('parent_id'),
            'tipo' => $request->input('tipo'),
            'nivel' => $request->input('nivel'),
        ]);

        return redirect()->route('web-admin.categorias.index')->with('success', 'Categoria criada com sucesso');
    }


    public function edit($id)
    {
        // Recupera a categoria que está sendo editada
        $categoria = Categoria::findOrFail($id);

        // Carrega todas as categorias com as subcategorias
        $categorias = Categoria::with('subcategorias')->whereNull('parent_id')->get();

        return view('web-admin.categorias.edit', compact('categoria', 'categorias'));
    }


    public function update(Request $request, Categoria $categoria)
    {
        Log::info('Atualizando categoria existente', ['id' => $categoria->id]);

        // Validação dos campos, incluindo o campo de imagem
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'tipo' => 'required|string|max:255',
            'nivel' => 'nullable|string|in:marca,produto,linha',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Valida a imagem
        ]);

        // Gerar o slug automaticamente
        $slug = Str::slug($request->input('nome'));

        // Verificar se o slug já existe para outra categoria que não seja a atual
        $slugCount = Categoria::where('slug', $slug)
            ->where('id', '!=', $categoria->id)  // Garante que a categoria atual não seja considerada
            ->count();

        if ($slugCount > 0) {
            $slug = $slug . '-' . uniqid(); // Gerar um slug único
        }

        // Atribuir manualmente os dados recebidos
        $categoria->nome = $request->input('nome');
        $categoria->slug = $slug;
        $categoria->descricao = $request->input('descricao');
        $categoria->parent_id = $request->input('parent_id');
        $categoria->tipo = $request->input('tipo');
        $categoria->nivel = $request->input('nivel');  // 'nivel' pode ser null

        // Tratamento da imagem
        if ($request->hasFile('imagem')) {
            // Exclui a imagem anterior, se houver
            if ($categoria->imagem) {
                Storage::delete('public/categorias/' . $categoria->imagem);
            }

            // Faz o upload da nova imagem
            $imagePath = $request->file('imagem')->store('categorias', 'public');
            $categoria->imagem = basename($imagePath);

            // Redimensiona a imagem (opcional)
            $thumbnailPath = storage_path('app/public/categorias/' . $categoria->imagem);
            $this->resizeImage($request->file('imagem')->getRealPath(), $thumbnailPath, 300, 300);
        }

        // Salvando manualmente
        $categoria->save();  // Isso garantirá que o registro existente seja atualizado

        return redirect()->route('web-admin.categorias.index')->with('success', 'Categoria atualizada com sucesso');
    }

    /**
     * Redimensiona a imagem
     */
    private function resizeImage($sourcePath, $destinationPath, $width, $height)
    {
        list($originalWidth, $originalHeight) = getimagesize($sourcePath);

        $aspectRatio = $originalWidth / $originalHeight;
        if ($width / $height > $aspectRatio) {
            $width = $height * $aspectRatio;
        } else {
            $height = $width / $aspectRatio;
        }

        $newImage = imagecreatetruecolor($width, $height);
        $sourceImage = imagecreatefromjpeg($sourcePath);

        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
        imagejpeg($newImage, $destinationPath, 90);

        imagedestroy($newImage);
        imagedestroy($sourceImage);
    }


    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('web-admin.categorias.index')->with('success', 'Categoria excluída com sucesso');
    }
}
