<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUpdateController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\CreatorDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

// page
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'creator' => redirect()->route('creator.dashboard'),
            default => redirect()->route('backer.dashboard'),
        };
    })->name('dashboard');
    Route::get('/account', function () { return view('account.account_backer'); })->name('backer.dashboard');
    
    // profil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'showProfile'])->name('show');
        Route::get('/edit', [UserController::class, 'editProfile'])->name('edit');
        Route::put('/update', [UserController::class, 'updateProfile'])->name('update');
        Route::post('/become-creator', [UserController::class, 'becomeCreator'])->name('become-creator');
        Route::get('/my-projects', [UserController::class, 'myProjects'])->name('my-projects');
    Route::post('/projects/{project}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('project.favorite.toggle');
    });
    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('project.show');
    Route::get('/projects/{slug}/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::put('/projects/{slug}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/projects/{slug}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('projects/{project}/images', [ProjectImageController::class, 'store'])->name('project.images.store');
    Route::delete('project-images/{image}', [ProjectImageController::class, 'destroy'])->name('project.images.destroy');
    Route::post('project-images/update-order', [ProjectImageController::class, 'updateOrder'])->name('project.images.update-order');
    Route::prefix('projects')->name('project.')->group(function () {
        Route::post('/{project}/updates', [ProjectUpdateController::class, 'store'])->name('updates.store');
        Route::get('/updates/{update}/edit', [ProjectUpdateController::class, 'edit'])->name('updates.edit');
        Route::put('/updates/{update}', [ProjectUpdateController::class, 'update'])->name('updates.update');
        Route::delete('/updates/{update}', [ProjectUpdateController::class, 'destroy'])->name('updates.destroy');
    });
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });
    Route::get('/account/contributions', [ContributionController::class, 'userContributions'])->name('user.contributions');
    Route::get('/account/contributions/receipt/{id}', [ContributionController::class, 'downloadReceipt'])->name('user.contribution.receipt');
});

// créateurs
Route::middleware(['auth', 'creator'])->prefix('creator')->name('creator.')->group(function () {
    Route::get('/dashboard', function () { return view('account.account_creator'); })->name('dashboard');
    Route::get('/projects', [ProjectController::class, 'myProjects'])->name('projects');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});

Route::get('/dashboard/creator', [CreatorDashboardController::class, 'index'])
    ->name('creator.dashboard')
    ->middleware(['auth']);

Route::get('/dashboard/creator', [ProjectController::class, 'creatorDashboard'])
->name('creator.dashboard')
->middleware(['auth']);

// administrateurs
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('account.account_admin'); })->name('dashboard');
    Route::get('/projects', function () { return view('admin_projects'); })->name('projects');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
});

Route::get('/project/{slug}/support', [App\Http\Controllers\ProjectController::class, 'support'])->name('project.support')->middleware('auth');
Route::post('/project/{slug}/support', [App\Http\Controllers\ProjectController::class, 'processSupport'])->name('project.processSupport')->middleware('auth');
Route::get('/project/{slug}/support/success', [App\Http\Controllers\ProjectController::class, 'supportSuccess'])->name('project.supportSuccess')->middleware('auth');
