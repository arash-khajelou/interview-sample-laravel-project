<?php

use Illuminate\Support\Facades\Route;
use Modules\Person\Controller\PersonController;

Route::get("/", [PersonController::class, "index"]);
Route::get("/{id}", [PersonController::class, "show"]);
Route::post("/", [PersonController::class, "store"]);
Route::put("/{id}", [PersonController::class, "update"]);
Route::delete("/{id}", [PersonController::class, "destroy"]);
Route::patch("/{id}/activate", [PersonController::class, "activate"]);
Route::patch("/{id}/deactivate", [PersonController::class, "deactivate"]);
