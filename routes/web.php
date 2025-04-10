<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUpdateController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('home');
})->name('home');

//Routes PAGES
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');

//Routes USER

// Routes du tableau de bord utilisateur
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'creator' => redirect()->route('creator.dashboard'),
            default => redirect()->route('backer.dashboard'),
        };
    })->name('dashboard')->middleware('auth');
    
    Route::get('/account', function () { return view('account.account_backer');})->name('backer.dashboard');
    Route::get('/account/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/account/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::get('/account/profile/show', [UserController::class, 'show'])->name('profile.show');
    
    Route::get('/account/contributions', function () {
        return view('user_contributions');
    })->name('user.contributions');
    
    Route::get('/account/favorites', function () {
        return view('user_favorites');
    })->name('user.favorites');
    
    Route::post('/account/become-creator', 'App\Http\Controllers\UserController@becomeCreator')->name('become.creator');

    
    Route::middleware(['creator'])->group(function () {
        Route::get('/creator/dashboard', function () {
            return view('account.account_creator');
        })->name('creator.dashboard');
        
        Route::get('/creator/projects', function () {
            return view('creator_projects');
        })->name('creator.projects');
        
        Route::get('/creator/project/create', function () {
            return view('creator_project_create');
        })->name('creator.project.create');
        
        Route::get('/creator/project/{id}/edit', function ($id) {
            return view('creator_project_edit');
        })->name('creator.project.edit');
        
        Route::get('/creator/project/{id}/stats', function ($id) {
            return view('creator_project_stats');
        })->name('creator.project.stats');
        
        Route::get('/creator/analytics', function () {
            return view('creator_analytics');
        })->name('creator.analytics');
    });
    
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('account.account_admin');
        })->name('admin.dashboard');
        
        Route::get('/admin/users', function () {
            return view('admin_users');
        })->name('admin.users');
        
        Route::resource('admin/categories', CategoryController::class)->names([
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'show' => 'admin.categories.show',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'destroy' => 'admin.categories.destroy',
        ]);

        
        Route::get('/admin/projects', function () {
            return view('admin_projects');
        })->name('admin.projects');
        
        Route::get('/admin/contributions', function () {
            return view('admin_contributions');
        })->name('admin.contributions');
        
        Route::get('/admin/settings', function () {
            return view('admin_settings');
        })->name('admin.settings');
        
        Route::get('/admin/logs', function () {
            return view('admin_logs');
        })->name('admin.logs');
    });
});



//Routes PROJECTS
Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');

// Placer la route 'create' AVANT la route avec paramètre {slug}
Route::middleware(['auth'])->group(function () {
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{slug}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{slug}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{slug}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    
    Route::post('projects/{project}/images', [ProjectImageController::class, 'store'])->name('project.images.store');
    Route::delete('project-images/{image}', [ProjectImageController::class, 'destroy'])->name('project.images.destroy');
    Route::post('project-images/update-order', [ProjectImageController::class, 'updateOrder'])->name('project.images.update-order');
    
    Route::post('projects/{project}/updates', [ProjectUpdateController::class, 'store'])->name('project.updates.store');
    Route::get('project-updates/{update}/edit', [ProjectUpdateController::class, 'edit'])->name('project.updates.edit');
    Route::put('project-updates/{update}', [ProjectUpdateController::class, 'update'])->name('project.updates.update');
    Route::delete('project-updates/{update}', [ProjectUpdateController::class, 'destroy'])->name('project.updates.destroy');
});

// Mettre cette route en dernier
Route::get('projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');