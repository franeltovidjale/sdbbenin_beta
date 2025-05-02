<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ShowUsersController;
use App\Http\Controllers\InvitationCodeController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\Articles\ArticleController;
use App\Http\Controllers\Productions\ProductionController;
use App\Http\Controllers\Articles\ArticleMovementController;

use App\Http\Controllers\Articles\CategorieArticleController;
use App\Http\Controllers\Productions\ProductionSaleController;
use App\Http\Controllers\Productions\TypeProductionController;
use App\Http\Controllers\Productions\ProductionLaborController;
use App\Http\Controllers\Productions\ProductionOutputController;
use App\Http\Controllers\Productions\ProductionArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes administrateur
// Route::middleware(['auth', 'admin'])->group(function () {
    // Gestion des catégories
  

    // Gestion des articles (création, modification, suppression)
    // Route::get('/glsam/ajouter-articles', [ArticleController::class, 'create'])->name('ajoutarticle');
    // Route::get('/glsam/admin/liste-articles', [ArticleController::class, 'showAllArticle'])->name('adminlistarticles');
    // Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    // Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::delete('/articles/deleteImage/{path}', [ArticleController::class, 'deleteImage'])->name('articles.deleteImage');

    // Modification et suppression des achats
    // Route::get('/purchases/edit/{input}', [InputOutputController::class, 'editpurchase'])->name('purchase.edit');
    // Route::put('/purchases/update/{id}', [InputOutputController::class, 'updateInput'])->name('purchases.update');
    // Route::delete('/purchases/destroy/{id}', [InputOutputController::class, 'deleteInput'])->name('purchases.destroy');

    // // Modification et suppression des ventes
    // Route::get('/sales/edit/{output}', [InputOutputController::class, 'editPageSales'])->name('sales.edit');
    // Route::put('/sales/update/{id}', [InputOutputController::class, 'updateOutput'])->name('sales.update');
    // Route::delete('/sales/destroy/{id}', [InputOutputController::class, 'deleteInput'])->name('sales.destroy');

    // Gestion des utilisateurs (codes d'invitation)
    Route::prefix('admin')->group(function () {
        Route::get('/invitation-codes', [InvitationCodeController::class, 'index'])->name('invitation-codes.index');
        Route::post('/invitation-codes', [InvitationCodeController::class, 'store'])->name('invitation-codes.store');
        Route::delete('/invitation-codes/{invitationCode}', [InvitationCodeController::class, 'destroy'])->name('invitation-codes.destroy');
    });
// });


// Routes accessibles à tous les utilisateurs authentifiés
// Route::middleware(['auth'])->group(function () {

   

    Route::get('/glsam/ajouter-articles', [ArticleController::class, 'create'])->name('ajoutarticle');
    Route::get('/glsam/admin/liste-articles', [ArticleController::class, 'showAllArticle'])->name('adminlistarticles');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

    Route::prefix('glsam/categories')->group(function () {
        // Route::get('/', [CategorieController::class, 'index'])->name('categories.index');
       
    });

    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    
// });

// Routes pour la gestion des utilisateurs (à ajouter dans web.php)
// Route::middleware(['auth', 'admin'])->prefix('')->group(function () {
    Route::get('/users/liste', [ShowUsersController::class, 'index'])->name('users.index');
    Route::patch('/{user}/block', [ShowUsersController::class, 'block'])->name('users.block');
    Route::patch('/{user}/activate', [ShowUsersController::class, 'activate'])->name('users.activate');
    Route::delete('/{user}', [ShowUsersController::class, 'destroy'])->name('users.destroy');
// });

// Route d'accueil
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('achatvente');
    }
    return redirect()->route('login');
})->name('home');

// Vérification OTP
Route::get('/otp-verification', [OtpVerificationController::class, 'show'])->name('verification.otp');
Route::post('/otp-verification', [OtpVerificationController::class, 'verify'])->name('verification.verifyOtp');
Route::post('/otp-verification/resend', [OtpVerificationController::class, 'resend'])->name('verification.resendOtp');



// nouvelles routes


// Routes principales
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
// Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
// Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');

// Routes pour la gestion des ventes de production
Route::prefix('productions')->name('productions.')->group(function () {
    Route::get('/sales', [ProductionSaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [ProductionSaleController::class, 'store'])->name('sales.store');
    Route::put('/sales/{sale}', [ProductionSaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{sale}', [ProductionSaleController::class, 'destroy'])->name('sales.destroy');
    Route::post('/sales/validate', [ProductionSaleController::class, 'validateProductionSale'])->name('sales.validate');
    Route::post('/sales/reject', [ProductionSaleController::class, 'reject'])->name('sales.reject');
    Route::get('/sales/reports', [ProductionSaleController::class, 'reports'])->name('sales.reports');
});

// Routes pour les catégories 
Route::prefix('categories')->group(function () {
    Route::get('/', [CategorieArticleController::class, 'index'])->name('categories.index');
    Route::post('/store', [CategorieArticleController::class, 'store'])->name('categories.store');
    Route::put('/{id}/update', [CategorieArticleController::class, 'update'])->name('categories.update');
    Route::delete('/{categorie}', [CategorieArticleController::class, 'destroy'])->name('categories.destroy');
});

// Routes pour les types de production
Route::prefix('types')->group(function () {
    Route::get('/', [TypeProductionController::class, 'index'])->name('types.index');
    Route::post('/store', [TypeProductionController::class, 'store'])->name('types.store');
    Route::put('/{id}/update', [TypeProductionController::class, 'update'])->name('types.update');
    Route::delete('/{typeProduction}', [TypeProductionController::class, 'destroy'])->name('types.destroy');
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/store', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::get('/search', [ArticleController::class, 'search'])->name('articles.search');
});



// Routes pour les productions
Route::prefix('productions')->group(function () {
    Route::get('/', [ProductionController::class, 'index'])->name('production.index');
    Route::get('/create', [ProductionController::class, 'create'])->name('production.create');
    Route::post('/store', [ProductionController::class, 'store'])->name('production.store');
    Route::get('/{production}', [ProductionController::class, 'show'])->name('production.show');
    Route::get('/{production}/edit', [ProductionController::class, 'edit'])->name('production.edit');
    Route::put('/{production}', [ProductionController::class, 'update'])->name('production.update');
    Route::delete('/{production}', [ProductionController::class, 'destroy'])->name('production.destroy');
    Route::get('/search', [ProductionController::class, 'search'])->name('production.search');
    Route::get('/stats', [ProductionController::class, 'stats'])->name('production.stats');
    Route::post('/{production}/verify', [ProductionController::class, 'verify'])->name('production.verify');
});

// Routes pour les articles utilisés dans une production
Route::prefix('production-articles')->group(function () {
    Route::post('/', [ProductionArticleController::class, 'store']);
    Route::put('/{id}', [ProductionArticleController::class, 'update']);
    Route::delete('/{id}', [ProductionArticleController::class, 'destroy']);
});

// Routes pour la main d'œuvre utilisée dans une production
Route::prefix('production-labor')->group(function () {
    Route::post('/', [ProductionLaborController::class, 'store']);
    Route::put('/{id}', [ProductionLaborController::class, 'update']);
    Route::delete('/{id}', [ProductionLaborController::class, 'destroy']);
});

// Routes pour la production obtenue (cartons)
Route::prefix('production-output')->group(function () {
    Route::post('/', [ProductionOutputController::class, 'store']);
    Route::put('/{id}', [ProductionOutputController::class, 'update']);
    Route::delete('/{id}', [ProductionOutputController::class, 'destroy']);
});


// Routes pour la gestion des mouvements d'articles
Route::prefix('articles')->name('stock.')->group(function () {
    Route::get('/movements', [ArticleMovementController::class, 'index'])->name('movements.index');
    Route::post('/movements', [ArticleMovementController::class, 'store'])->name('movements.store');
    Route::put('/movements/{movement}', [ArticleMovementController::class, 'update'])->name('movements.update');
    Route::delete('/movements/{movement}', [ArticleMovementController::class, 'destroy'])->name('movements.destroy');
    Route::post('/movements/validate', [ArticleMovementController::class, 'validateArticleMovement'])->name('movements.validate');
    Route::post('/movements/reject', [ArticleMovementController::class, 'reject'])->name('movements.reject');
});




 // Routes pour Personnel
// Route::get('/personnel', [PersonnelController::class, 'index'])->name('personnel.index');
// Route::get('/personnel/departments', [PersonnelController::class, 'departments'])->name('personnel.departments');

// // Route pour les paramètres du compte
// Route::get('/settings/account', [SettingController::class, 'account'])->name('settings.account');

// // Route pour la déconnexion
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes d'authentification
require __DIR__.'/auth.php';