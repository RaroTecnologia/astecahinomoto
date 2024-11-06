<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            $type = $request->get('type');

            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }

            if ($type === 'receitas') {
                $results = Receita::where('nome', 'like', "%{$query}%")
                    ->orWhere('chamada', 'like', "%{$query}%")
                    ->with('categoria')
                    ->take(5)
                    ->get();

                return response()->json($results);
            }

            return response()->json([]);
        } catch (\Exception $e) {
            Log::error('Erro na busca: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}
