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
            $request->validate([
                'nome' => 'required|string|max:255',
                'quantidade' => 'nullable|string',
                'unidade' => 'nullable|string',
                'ean' => 'nullable|string',
                'dun' => 'nullable|string',
                'porcao_tabela' => 'nullable|string',
                'quantidade_inner' => 'nullable|string',
                'codigo_sku' => 'nullable|string',
                'is_active' => 'nullable|boolean', // Validação do status
            ]);

            $sku = Sku::findOrFail($id);

            // Processa a imagem se houver upload
            if ($request->hasFile('imagem')) {
                if ($sku->imagem && Storage::exists('public/skus/' . $sku->imagem)) {
                    Storage::delete('public/skus/' . $sku->imagem);
                }
                $imagePath = $request->file('imagem')->store('skus', 'public');
                $sku->imagem = basename($imagePath);
            }

            // Atualiza os dados do SKU
            $sku->update([
                'nome' => $request->input('nome'),
                'slug' => Str::slug($request->input('nome')),
                'quantidade' => $request->input('quantidade'),
                'unidade' => $request->input('unidade'),
                'ean' => $request->input('ean'),
                'dun' => $request->input('dun'),
                'porcao_tabela' => $request->input('porcao_tabela'),
                'quantidade_inner' => $request->input('quantidade_inner'),
                'codigo_sku' => $request->input('codigo_sku'),
                'is_active' => $request->boolean('is_active'), // Usa o helper boolean()
            ]);

            return response()->json(['success' => true, 'message' => 'SKU atualizado com sucesso.']);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar SKU: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar SKU.'], 500);
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
