<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});


//Dashboard
Route::get('/dashboard', [\App\Http\Controllers\DashBoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::post('/orders/update/{id}', [\App\Http\Controllers\DashBoardController::class, 'update'])->name('Orders/Update') ;

Route::get('/Order/Edit/{id}', [\App\Http\Controllers\DashBoardController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('Order/Edit');





//Categories
Route::get('/Categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('Categories/Index') ->middleware(['auth', 'verified']);
Route::get('/Categories/Create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('Categories/Create') ->middleware(['auth', 'verified']);
Route::post('/Categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('Categories/Store') ->middleware(['auth', 'verified']);
Route::put('/Categories/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('Categories/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Categories/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('Categories/Delete') ->middleware(['auth', 'verified']);

//Tables
Route::get('/Tables', [\App\Http\Controllers\TableController::class, 'index'])->name('Tables/Index') ->middleware(['auth', 'verified']);
Route::get('/Tables/Create', [\App\Http\Controllers\TableController::class, 'create'])->name('Tables/Create') ->middleware(['auth', 'verified']);
Route::post('/Tables', [\App\Http\Controllers\TableController::class, 'store'])->name('Tables/Store') ->middleware(['auth', 'verified']);
Route::put('/Tables/edit/{id}', [\App\Http\Controllers\TableController::class, 'update'])->name('Tables/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Tables/{id}', [\App\Http\Controllers\TableController::class, 'destroy'])->name('Tables/Delete') ->middleware(['auth', 'verified']);

//Rooms
Route::get('/Rooms', [\App\Http\Controllers\RoomController::class, 'index'])->name('Rooms/Index') ->middleware(['auth', 'verified']);
Route::get('/Rooms/Create', [\App\Http\Controllers\RoomController::class, 'create'])->name('Rooms/Create') ->middleware(['auth', 'verified']);
Route::post('/Rooms', [\App\Http\Controllers\RoomController::class, 'store'])->name('Rooms/Store') ->middleware(['auth', 'verified']);
Route::put('/Rooms/edit/{id}', [\App\Http\Controllers\RoomController::class, 'update'])->name('Rooms/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Rooms/{id}', [\App\Http\Controllers\RoomController::class, 'destroy'])->name('Rooms/Delete') ->middleware(['auth', 'verified']);

//Sales
Route::get('/Sales', [\App\Http\Controllers\SalesController::class, 'index'])->name('Sales/Index') ->middleware(['auth', 'verified']);
Route::post('/Sales/Create', [\App\Http\Controllers\SalesController::class, 'store'])->name('Sales/Store') ->middleware(['auth', 'verified']);
Route::get('/Sales-for-cutoff', [\App\Http\Controllers\SalesController::class, 'getSalesForCutOff'])->name('Sales/ForCutOff') ->middleware(['auth', 'verified']);

//Menu
Route::get('/Menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('Menu/Index') ->middleware(['auth', 'verified']);
Route::get('/Menu/Create', [\App\Http\Controllers\MenuController::class, 'create'])->name('Menu/Create') ->middleware(['auth', 'verified']);
Route::post('/Menu', [\App\Http\Controllers\MenuController::class, 'store'])->name('Menu/Store') ->middleware(['auth', 'verified']);
Route::post('/Menu/edit/{id}', [\App\Http\Controllers\MenuController::class, 'update'])->name('Menu/Edit') ->middleware(['auth', 'verified']);
Route::delete('/Menu/{id}', [\App\Http\Controllers\MenuController::class, 'destroy'])->name('Menu/Delete') ->middleware(['auth', 'verified']);

// Products
Route::get('/Products/get', [\App\Http\Controllers\Products\ProductController::class, 'index'])->name('products.get') ->middleware(['auth', 'verified']);

//Orders
Route::get('/Orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('Orders/Index') ->middleware(['auth', 'verified']);
Route::post('/Orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('Orders/Store') ->middleware(['auth', 'verified']);
Route::put('/Order/Update/{id}', [\App\Http\Controllers\OrderController::class, 'update'])->name('Order/Update');

Route::post('/Order/AddTip/{id}', [\App\Http\Controllers\OrderController::class, 'addTip'])->name('Order/AddTip');

//CashAudit
Route::group(['middleware' => ['role:Admin|Cajero']], function (){
    Route::get('/CashAudit', [\App\Http\Controllers\CashAuditController::class, 'index'])->name('CashAudit/Index') ->middleware(['auth', 'verified']);
    Route::post('/CashAudit', [\App\Http\Controllers\CashAuditController::class, 'store'])->name('CashAudit/Store') ->middleware(['auth', 'verified']);
});

//Users
Route::group(['middleware' => ['role:Admin']], function (){
    Route::get('/Users', [\App\Http\Controllers\UserController::class, 'index'])->name('Users/Index') ->middleware(['auth', 'verified']);
    Route::post('/Role/Update', [\App\Http\Controllers\UserController::class, 'update'])->name('/Role/Update') ->middleware(['auth', 'verified']);
    Route::post('/Role/Activate', [\App\Http\Controllers\UserController::class, 'activate'])->name('/Role/Activate') ->middleware(['auth', 'verified']);
});

//CashFloat
Route::post('/CashFloat/Store', [\App\Http\Controllers\CashFloatController::class, 'store'])->name('CashFloat/Store') ->middleware(['auth', 'verified']);
Route::post('/CashFloat/Update', [\App\Http\Controllers\CashFloatController::class, 'update'])->name('CashFloat/Update') ->middleware(['auth', 'verified']);


//Config
Route::get('/Config', [\App\Http\Controllers\ConfigController::class, 'index'])->name('Config/Index') ->middleware(['auth', 'verified']);
Route::post('/Config/Update-Shift', [\App\Http\Controllers\ConfigController::class, 'updateShift'])->name('config.update-shift')->middleware(['auth', 'verified']);
Route::post('/Config/Update-Room-Service', [\App\Http\Controllers\ConfigController::class, 'updateRoomService'])->name('config.update-room-service')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php'; 

Route::get('print-ticket/', function (Request $request) {
    $ticketrequest = request()->all()['data'];
    
    // Convertir todos los datos a JSON string
    $ticketData = json_encode($ticketrequest['ticketData']);
    $ticketItems = json_encode($ticketrequest['ticketItems']); // Corregido: convertir a JSON string
    $businessInfo = json_encode($ticketrequest['businessInfo']);
    
    // Concatenar las cadenas JSON con las barras
    $printData = $ticketData . "|" . $ticketItems . "|" . $businessInfo;
    
    // Comprimir y codificar en Base64
    $printData = base64_encode(gzdeflate($printData));

    // Devolver la respuesta al cliente
    return response()->json(['printData' => $printData]);
})->name('print.ticket');

Route::get('print-cut-off/', function (Request $request) {
    // Obtener los datos del request
    $cutOffData = request()->all()['data'];
    // Convertir las claves numéricas a cadenas de texto 99 a "99.99"
    $cutOffData['totalVentas'] = number_format((float)$cutOffData['totalVentas'], 2, '.', '');
    $cutOffData['montoFinal'] = number_format((float)$cutOffData['montoFinal'], 2, '.', '');
    $cutOffData['totalPropinas'] = number_format((float)$cutOffData['totalPropinas'], 2, '.', '');
    $cutOffData['fecha'] = date('Y-m-d', strtotime($cutOffData['fecha']));
    $cutOffData['turno'] = $cutOffData['turno'] === 'Matutino' ? 'M' : 'V';
    // Convertir a JSON, comprimir y codificar en Base64
    $printData = json_encode($cutOffData);
    $printData = base64_encode(gzdeflate($printData));

    return response()->json(['printData' => $printData, 'rawData' => $cutOffData]);
})->name('print.cutOff');
