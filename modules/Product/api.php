<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Controller\ProductController;

Route::get("/", [ProductController::class, "index"]);
Route::get("/{id}", [ProductController::class, "show"]);
Route::post("/", [ProductController::class, "store"]);
Route::put("/{id}", [ProductController::class, "update"]);
Route::delete("/{id}", [ProductController::class, "destroy"]);
Route::patch("/{id}/activate", [ProductController::class, "activate"]);
Route::patch("/{id}/deactivate", [ProductController::class, "deactivate"]);
