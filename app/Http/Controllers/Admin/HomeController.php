<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use App\Models\HomeDestaque;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = HomeBanner::orderBy('ordem')->get();
        $destaques = HomeDestaque::with('produto')->orderBy('ordem')->get();
        $produtos = Produto::all();

        return view('web-admin.home.index', compact('banners', 'destaques', 'produtos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $type = $request->input('type');

            if ($type === 'banner') {
                return $this->storeBanner($request);
            } else if ($type === 'destaque') {
                return $this->storeDestaque($request);
            }

            return redirect()->back()->with('error', 'Tipo inválido');
        } catch (\Exception $e) {
            Log::error('Erro ao salvar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao salvar');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Se for atualização de ordem
            if ($request->has('items')) {
                return $this->updateOrdem($request);
            }

            $banner = HomeBanner::findOrFail($id);

            $bannerData = $request->validate([
                'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'titulo' => 'nullable|string|max:255',
                'subtitulo' => 'nullable|string|max:255',
                'link' => 'nullable|string|max:255',
                'ordem' => 'required|integer|min:0'
            ]);

            if ($request->hasFile('imagem')) {
                // Deleta imagens antigas
                $this->deleteImage($banner);

                // Upload nova imagem
                $imagePath = $request->file('imagem')->store('banners', 'public');
                $imageName = basename($imagePath);
                $bannerData['imagem'] = $imageName;

                // Cria thumbnail
                $thumbnailPath = storage_path('app/public/thumbnails/' . $imageName);
                $this->resizeImage(
                    $request->file('imagem')->getRealPath(),
                    $thumbnailPath,
                    300,
                    200
                );
            }

            $banner->update($bannerData);

            return response()->json([
                'success' => true,
                'message' => 'Banner atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar banner:', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar banner'
            ], 500);
        }
    }

    /**
     * Update order of items.
     */
    private function updateOrdem(Request $request)
    {
        try {
            $items = $request->get('items');
            $type = $request->get('type');

            foreach ($items as $index => $item) {
                if ($type === 'banner') {
                    HomeBanner::where('id', $item)->update(['ordem' => $index]);
                } else {
                    HomeDestaque::where('id', $item)->update(['ordem' => $index]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar ordem:', [
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar ordem'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $type = request()->input('type');

            if ($type === 'banner') {
                $banner = HomeBanner::findOrFail($id);
                $this->deleteImage($banner);
                $banner->delete();
            } else if ($type === 'destaque') {
                $destaque = HomeDestaque::findOrFail($id);
                $destaque->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Item excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $banner = HomeBanner::findOrFail($id);
            return response()->json([
                'success' => true,
                'banner' => $banner
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar banner:', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar banner'
            ], 500);
        }
    }

    /**
     * Store a new banner.
     */
    private function storeBanner(Request $request)
    {
        try {
            $request->validate([
                'imagem_desktop' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'imagem_mobile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'link' => 'nullable|string|max:255',
                'ordem' => 'required|integer|min:0'
            ]);

            $bannerData = $request->only(['link', 'ordem']);

            // Upload imagem desktop
            if ($request->hasFile('imagem_desktop')) {
                $imagePath = $request->file('imagem_desktop')->store('banners', 'public');
                $bannerData['imagem_desktop'] = basename($imagePath);
            }

            // Upload imagem mobile
            if ($request->hasFile('imagem_mobile')) {
                $imagePath = $request->file('imagem_mobile')->store('banners', 'public');
                $bannerData['imagem_mobile'] = basename($imagePath);
            }

            $banner = HomeBanner::create($bannerData);
            return redirect()->back()->with('success', 'Banner adicionado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar banner:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao criar banner: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store a new destaque.
     */
    private function storeDestaque(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'ordem' => 'required|integer|min:0'
        ]);

        HomeDestaque::create($request->all());
        return redirect()->back()->with('success', 'Produto em destaque adicionado com sucesso!');
    }

    /**
     * Resize and save image.
     */
    private function resizeImage($sourcePath, $destinationPath, $width, $height)
    {
        try {
            Log::info('Iniciando resize da imagem', [
                'source' => $sourcePath,
                'destination' => $destinationPath
            ]);

            // Detecta o tipo de imagem
            $imageInfo = getimagesize($sourcePath);
            $mime = $imageInfo['mime'];

            Log::info('Tipo de imagem detectado:', ['mime' => $mime]);

            // Cria a imagem source baseado no tipo
            switch ($mime) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($sourcePath);
                    break;
                default:
                    throw new \Exception("Tipo de imagem não suportado: {$mime}");
            }

            list($originalWidth, $originalHeight) = $imageInfo;
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

            Log::info('Novas dimensões calculadas:', [
                'width' => $width,
                'height' => $height
            ]);

            $newImage = imagecreatetruecolor($width, $height);

            // Preserva transparência para PNGs
            if ($mime === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
            }

            imagecopyresampled(
                $newImage,
                $sourceImage,
                0,
                0,
                0,
                0,
                $width,
                $height,
                $originalWidth,
                $originalHeight
            );

            // Salva a imagem no formato correto
            switch ($mime) {
                case 'image/jpeg':
                    $result = imagejpeg($newImage, $destinationPath, 90);
                    break;
                case 'image/png':
                    $result = imagepng($newImage, $destinationPath, 9);
                    break;
                case 'image/gif':
                    $result = imagegif($newImage, $destinationPath);
                    break;
            }

            Log::info('Resultado do salvamento:', ['success' => $result]);

            imagedestroy($newImage);
            imagedestroy($sourceImage);

            Log::info('Resize concluído com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro no resize da imagem:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
    }

    /**
     * Delete image and thumbnail.
     */
    private function deleteImage($banner)
    {
        if ($banner->imagem) {
            Storage::delete([
                'public/banners/' . $banner->imagem,
                'public/thumbnails/' . $banner->imagem
            ]);
        }
    }
}
