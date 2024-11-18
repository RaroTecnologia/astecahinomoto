<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    public function generateCatalogo(Request $request)
    {
        try {
            Log::info('Iniciando geração de PDF do catálogo', [
                'filtros' => $request->all()
            ]);

            $query = Sku::with(['produto.marca', 'produto.categoria']);

            // Aplicar filtros
            if ($request->filled('marca')) {
                $query->whereHas('produto.marca', function ($q) use ($request) {
                    $q->where('slug', $request->marca);
                });
            }

            if ($request->filled('produto')) {
                $query->whereHas('produto', function ($q) use ($request) {
                    $q->where('slug', $request->produto);
                });
            }

            if ($request->filled('linha')) {
                $query->whereHas('produto.categoria', function ($q) use ($request) {
                    $q->where('slug', $request->linha);
                });
            }

            $skus = $query->get();

            Log::info('Produtos carregados para PDF', [
                'total_produtos' => $skus->count()
            ]);

            $pdf = PDF::loadView('pdfs.catalogo', [
                'skus' => $skus,
                'filtros' => [
                    'marca' => $request->marca,
                    'produto' => $request->produto,
                    'linha' => $request->linha
                ]
            ]);

            return $pdf->download('catalogo-asteca.pdf');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF do catálogo', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Erro ao gerar PDF'
            ], 500);
        }
    }
}
