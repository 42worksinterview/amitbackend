<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\NotesController;

Route::get('/notes', [NotesController::class, 'index']);
Route::post('/notes', [NotesController::class, 'store']);
Route::put('/notes/{id}', [NotesController::class, 'update']);
Route::delete('/notes/{id}', [NotesController::class, 'destroy']);

// Bonus
Route::post('/notes/{id}/summarize', [NotesController::class, 'summarize']);
