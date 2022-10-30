<?php

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Controller\InvoiceController;

Route::get("/", [InvoiceController::class, "index"]);
Route::get("/{id}", [InvoiceController::class, "show"]);
Route::post("/", [InvoiceController::class, "store"]);
Route::delete("/{id}", [InvoiceController::class, "destroy"]);
Route::patch("/{id}/attach", [InvoiceController::class, "attach"]);
Route::patch("/{id}/detach", [InvoiceController::class, "detach"]);
