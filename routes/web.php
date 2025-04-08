<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUpdateController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\UserController;


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
Route::get('/mon-compte', function () {
    if (!Auth::check()) {
        return redirect(route('login'));
    }
    return view('account');
})->name('account');
Route::post('/user/become-creator', [UserController::class, 'becomeCreator'])->name('user.become-creator');

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