<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\WebAdminController;
use App\Http\Controllers\SearchController;

// Controladores de Admin
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\NutrientController as AdminNutrientController;
use App\Http\Controllers\Admin\NutritionTableController as AdminNutritionTableController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SkuController as AdminSkuController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;

// Rotas Públicas
Route::controller(PaginaController::class)->group(function () {
    Route::get('/sobre', 'sobre')->name('sobre');
    Route::get('/fale-conosco', 'faleConosco')->name('fale-conosco');
    Route::post('/fale-conosco/enviar', 'enviarContato')->name('fale-conosco.enviar');
    Route::get('/politica-de-privacidade', 'politicaPrivacidade')->name('politica-privacidade');
});

// Rotas de Autenticado com Middleware
Route::middleware(['auth'])->prefix('web-admin')->name('web-admin.')->group(function () {
    // Dashboard - acessível para todos os usuários autenticados
    Route::get('/', [WebAdminController::class, 'index'])->name('index');

    // Rotas de Perfil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Rotas que eram apenas para administradores (agora para todos autenticados)
    Route::resource('usuarios', AdminUserController::class);
    Route::resource('categorias', AdminCategoryController::class);
    Route::resource('nutrientes', AdminNutrientController::class);
    Route::resource('tabelas-nutricionais', AdminNutritionTableController::class);

    // Rotas que eram para admins e editores (agora para todos autenticados)
    Route::resource('noticias', AdminNewsController::class);
    Route::resource('receitas', AdminRecipeController::class);
    Route::resource('produtos', AdminProductController::class);
    Route::resource('home', AdminHomeController::class);

    // Rotas SKU
    Route::post('/skus', [AdminSkuController::class, 'store'])->name('skus.store');
    Route::put('/skus/{id}', [AdminSkuController::class, 'update'])->name('skus.update');
    Route::delete('/skus/{id}', [AdminSkuController::class, 'destroy'])->name('skus.destroy');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

// Rotas para notícias (público)
Route::controller(NoticiaController::class)->group(function () {
    Route::get('/noticias', 'index')->name('noticias.index');
    Route::get('/noticias/categoria/{slug}', 'categoriasNoticias')->name('noticias.categoria');
    Route::get('/noticias/{categoria}/{slug}', 'show')->name('noticias.show');
});

// Rotas para marcas
Route::controller(MarcaController::class)->group(function () {
    Route::get('/nossas-marcas', 'listarTipos')->name('marcas');
    Route::get('/marcas', 'listarTipos')->name('marcas.tipos');
    Route::get('/marcas/{tipoSlug}', 'listarMarcasPorTipo')->name('marcas.tipo');
    Route::get('/marcas/{tipoSlug}/{slugMarca}', 'listarProdutosOuLinhasDaMarca')->name('marcas.produtos');
    Route::get('/marcas/{tipoSlug}/{slugMarca}/{slugProduto}/{slugLinha?}', 'listarLinhasOuProdutos')->name('marcas.produtos.linhas');
});

// Rota para detalhe do produto
Route::controller(ProdutoController::class)->group(function () {
    Route::get('/produto/{slugMarca}/{slugProduto}', [ProdutoController::class, 'show'])->name('produtos.show');
});


// Rotas para receitas
Route::controller(ReceitaController::class)->group(function () {
    Route::get('/receitas', 'index')->name('receitas.index');
    Route::get('/receitas/categoria/{slug}', 'categoriasReceitas')->name('receitas.categoria');
    Route::get('/receitas/{categoria}/{slug}', 'show')->name('receitas.show');
    Route::post('/receitas/{id}/curtir', [ReceitaController::class, 'curtir'])->name('receitas.curtir');
});

// API Routes
Route::get('/api/produtos/filtrar/{categoria}/{subcategoria?}', [ProdutoController::class, 'filtrarCategoria'])->name('produtos.filtrar.ajax');
Route::get('/api/subcategorias/{slug}', [CategoriaController::class, 'getSubcategorias']);
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');
Route::get('/api/global-search', [SearchController::class, 'globalSearch'])->name('api.global-search');

// Rotas de autenticação
require __DIR__ . '/auth.php';

// Rotas do Catálogo
Route::controller(CatalogoController::class)->group(function () {
    Route::get('/catalogo', 'index')->name('catalogo.index');
    Route::get('/api/catalogo/filtrar', 'filtrar')->name('catalogo.filtrar');
    Route::get('/api/catalogo/produtos/{marca}', 'getProdutos')->name('catalogo.produtos');
    Route::get('/api/catalogo/linhas/{produto}', 'getLinhas')->name('catalogo.linhas');
});
