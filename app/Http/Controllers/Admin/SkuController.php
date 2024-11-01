<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SkuController extends Controller
{
    /**
     * Store a newly created SKU in storage.
     */
    public function store(Request $request)
    {
        try {
            $sku = Sku::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'SKU criado com sucesso',
                'sku' => $sku
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar SKU: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar SKU'
            ], 500);
        }
    }

    /**
     * Update the specified SKU in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $sku = Sku::findOrFail($id);
            Log::info('Request completo:', $request->all());

            $request->validate([
                'nome' => 'required|string|max:255',
                'quantidade' => 'nullable|string|max:255',
                'unidade' => 'nullable|string|max:255',
                'ean' => 'nullable|string|max:17',
                'dun' => 'nullable|string|max:18',
                'porcao_tabela' => 'nullable|string|max:60',
                'quantidade_inner' => 'nullable|string|max:60',
                'codigo_sku' => 'nullable|string|max:60',
                'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $skuData = $request->only(['nome', 'quantidade', 'unidade', 'porcao_tabela', 'quantidade_inner', 'ean', 'dun', 'codigo_sku']);

            if ($request->hasFile('imagem')) {
                $this->deleteImage($sku);

                $imagePath = $request->file('imagem')->store('skus', 'public');
                $imageName = basename($imagePath);
                $skuData['imagem'] = $imageName;

                $thumbnailPath = storage_path('app/public/thumbnails/' . $imageName);
                $this->resizeImage($request->file('imagem')->getRealPath(), $thumbnailPath, 300, 300);
            }

            $sku->update($skuData);

            Log::info('SKU atualizado com sucesso:', ['id' => $sku->id, 'dados' => $skuData]);

            return response()->json(['success' => true, 'message' => 'SKU atualizado com sucesso.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('SKU não encontrado: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'SKU não encontrado.'], 404);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar SKU: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar SKU: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified SKU from storage.
     */
    public function destroy($id)
    {
        try {
            Log::info('Iniciando exclusão do SKU:', ['id' => $id]);

            $sku = Sku::findOrFail($id);

            // Primeiro apaga as imagens físicas
            if (method_exists($this, 'deleteImage')) {
                $this->deleteImage($sku);
            }

            // Apaga o registro do banco
            $deleted = $sku->delete();

            Log::info('Resultado da exclusão:', ['success' => $deleted]);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'SKU excluído com sucesso.'
                ]);
            } else {
                throw new \Exception('Falha ao excluir o SKU');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao excluir SKU:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir SKU: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redimensiona a imagem manualmente e salva em um novo local.
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

    /**
     * Exclui a imagem e a thumbnail associadas ao SKU, se existirem.
     */
    private function deleteImage($sku)
    {
        if ($sku->imagem && Storage::exists('public/skus/' . $sku->imagem)) {
            Storage::delete('public/skus/' . $sku->imagem);
        }

        if ($sku->thumbnail && Storage::exists('public/thumbnails/' . $sku->thumbnail)) {
            Storage::delete('public/thumbnails/' . $sku->thumbnail);
        }
    }

    protected function validationRules()
    {
        return [
            // ... regras existentes ...
            'ean' => 'nullable|string|max:255',
            'dun' => 'nullable|string|max:255',
            'porcao_tabela' => 'nullable|string|max:60',
            'quantidade_inner' => 'nullable|string|max:60',
        ];
    }
}
