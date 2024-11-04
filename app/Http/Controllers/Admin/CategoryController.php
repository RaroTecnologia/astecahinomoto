<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categorias = Categoria::whereNull('parent_id')
                ->with('subcategorias')
                ->get();

            // Carregar e verificar os tipos
            $tipos = Tipo::orderBy('nome')->get();

            if ($tipos->isEmpty()) {
                Log::warning('Nenhum tipo encontrado na tabela tipos');
            }

            return view('web-admin.categorias.index', compact('categorias', 'tipos'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar categorias: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erro ao carregar categorias']);
        }
    }

    public function create()
    {
        $tipos = Tipo::all();
        $categorias = Categoria::all(); // para o parent_id
        return view('web-admin.categorias.create', compact('tipos', 'categorias'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'tipo' => 'required|string|in:noticia,receita,produto',
                'nivel' => 'required|string|in:marca,produto,linha',
                'parent_id' => 'nullable|exists:categorias,id',
                'tipo_id' => $request->nivel === 'marca' ? 'required|exists:tipos,id' : 'nullable',
                'is_principal' => 'boolean',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Processar imagem se foi enviada
            $imagemPath = '';  // Valor padrão vazio
            if ($request->hasFile('imagem')) {
                $image = $request->file('imagem');
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagemPath = $fileName;

                // Salvar imagem usando seu método existente
                $this->resizeImage(
                    $image->getRealPath(),
                    storage_path('app/public/categorias/' . $fileName),
                    800,
                    600
                );
            }

            // Criar a categoria
            $categoria = Categoria::create([
                'nome' => $validated['nome'],
                'descricao' => $validated['descricao'],
                'slug' => Str::slug($validated['nome']),
                'tipo' => $validated['tipo'],
                'nivel' => $validated['nivel'],
                'parent_id' => $validated['parent_id'],
                'imagem' => $imagemPath  // Adiciona o campo imagem
            ]);

            // Se for uma marca e tiver tipo_id, vincular
            if ($validated['nivel'] === 'marca' && isset($validated['tipo_id'])) {
                $categoria->tipos()->attach($validated['tipo_id'], [
                    'is_principal' => $request->boolean('is_principal', true)
                ]);
            }

            return redirect()->route('web-admin.categorias.index')
                ->with('success', 'Categoria criada com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar categoria: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar categoria. Por favor, verifique os dados e tente novamente.']);
        }
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
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Adicionado GIF
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
            $image = $request->file('imagem');

            // Gera um nome único para o arquivo
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Define o caminho completo incluindo o nome do arquivo
            $destinationPath = storage_path('app/public/categorias/' . $fileName);

            // Redimensiona e salva a imagem
            $this->resizeImage(
                $image->getRealPath(),
                $destinationPath,
                800, // width
                600  // height
            );

            // Atualiza o caminho da imagem no banco
            $categoria->imagem = $fileName;
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
        // Certifique-se que o destinationPath inclui o nome do arquivo
        $directory = dirname($destinationPath);

        // Cria o diretório se não existir
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        // Obtém informações da imagem
        list($originalWidth, $originalHeight, $type) = getimagesize($sourcePath);

        // Calcula o aspect ratio
        $aspectRatio = $originalWidth / $originalHeight;
        if ($width / $height > $aspectRatio) {
            $width = $height * $aspectRatio;
        } else {
            $height = $width / $aspectRatio;
        }

        // Cria nova imagem
        $newImage = imagecreatetruecolor($width, $height);

        // Habilita transparência para PNG e GIF
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        // Carrega a imagem baseado no tipo
        $sourceImage = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF  => imagecreatefromgif($sourcePath),
            default        => throw new \Exception('Formato de imagem não suportado'),
        };

        // Copia e redimensiona
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

        // Salva a imagem no formato correto
        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($newImage, $destinationPath, 90),
            IMAGETYPE_PNG  => imagepng($newImage, $destinationPath, 9),
            IMAGETYPE_GIF  => imagegif($newImage, $destinationPath),
            default        => throw new \Exception('Formato de imagem não suportado'),
        };

        // Libera memória
        imagedestroy($newImage);
        imagedestroy($sourceImage);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('web-admin.categorias.index')->with('success', 'Categoria excluída com sucesso');
    }
}
