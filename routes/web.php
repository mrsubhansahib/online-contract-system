
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTemplateController;
use App\Http\Controllers\DemoMailController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login/check', [AuthController::class, 'check'])->name('login.check');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => ['admin', 'auth']], function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

Route::group(['prefix' => 'users', 'middleware' => ['admin', 'auth']], function () {

    Route::get('/', [UserController::class, 'index'])->name('user');
    Route::get('add', [UserController::class, 'add'])->name('user.add');
    Route::post('store', [UserController::class, 'store'])->name('user.store');
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('update', [UserController::class, 'update'])->name('user.update');
    Route::get('delete/{id}', [UserController::class, 'delete'])->name('user.delete');
});
Route::group(['prefix' => 'contracts', 'middleware' => ['admin', 'auth']], function () {

    Route::group(['prefix' => 'category'], function () {

        Route::get('/', [CategoryController::class, 'index'])->name('category');
        Route::get('add', [CategoryController::class, 'add'])->name('category.add');
        Route::post('store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('update', [CategoryController::class, 'update'])->name('category.update');
        Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
        Route::get('/{status}', [CategoryController::class, 'show'])->name('category.view');
    });
    Route::get('add', [ContractController::class, 'add'])->name('contract.add');
    Route::get('add-test', [ContractController::class, 'addTest'])->name('contract.addTest');

    Route::post('store', [ContractController::class, 'store'])->name('contract.store');
    Route::get('edit/{id}', [ContractController::class, 'edit'])->name('contract.edit');
    Route::post('update', [ContractController::class, 'update'])->name('contract.update');
    Route::get('delete/{id}', [ContractController::class, 'delete'])->name('contract.delete');
    Route::get('view/{id}', [ContractController::class, 'view'])->name('contract.view');
    Route::post('/save/pdf',[ContractController::class,'savePdf'])->name('save.pdf');
    Route::get('copy/{id}', [ContractController::class, 'copy'])->name('contract.copy');
    Route::get('/{status?}', [ContractController::class, 'index'])->name('contract');
});
Route::get('/sendMail', [DemoMailController::class, 'sendMail']);
Route::group(['prefix' => 'templates', 'middleware' => 'admin'], function () {

    Route::get('/', [ContractTemplateController::class, 'index'])->name('template');
    Route::get('add', [ContractTemplateController::class, 'add'])->name('template.add');
    Route::post('store', [ContractTemplateController::class, 'store'])->name('template.store');
    Route::get('edit/{id}', [ContractTemplateController::class, 'edit'])->name('template.edit');
    Route::post('update', [ContractTemplateController::class, 'update'])->name('template.update');
    Route::get('delete/{id}', [ContractTemplateController::class, 'delete'])->name('template.delete');
    Route::get('/{id}', [ContractTemplateController::class, 'template'])->name('template.template');
});
Route::group(['prefix' => 'notification', 'middleware' => 'auth'], function () {
    Route::get('/mark-as-readed', [ContractController::class, 'mark_as_readed'])->name('mark-as-readed');
});
// User Routes
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {

    Route::get('my-contracts', [ContractController::class, 'myContracts'])->name('contract.my-contracts');
    Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('update-profile', [UserController::class, 'update_profile'])->name('user.update_profile');
    Route::get('view/{id}', [ContractController::class, 'view'])->name('contract.view');
    Route::get('print/{id}', [ContractController::class, 'print'])->name('contract.print');
});
Route::group(['middleware' => 'auth'], function () {
    Route::post('contracts/saveSignature', [ContractController::class, 'saveSignature'])->name('contract.saveSignature');
    Route::post('contracts/saveDetails', [ContractController::class, 'saveDetails'])->name('contract.saveDetails');
    Route::post('contracts/saveImage', [ContractController::class, 'saveImage'])->name('contract.saveImage');
});

Route::get('/', function (Request $request) {

    // $host = $request->getHost();
    // return $subdomain = explode('.', $host)[0];

    return view('auth.login');
});
