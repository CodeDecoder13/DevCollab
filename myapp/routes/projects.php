<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::patch('projects/{project}/archive', [ProjectController::class, 'archive'])->name('projects.archive');

    Route::resource('projects.members', ProjectMemberController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['members' => 'user']);

    Route::resource('projects.tasks', TaskController::class);
    Route::patch('projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('projects.tasks.update-status');

    Route::post('projects/{project}/tasks/{task}/comments', [CommentController::class, 'store'])
        ->name('projects.tasks.comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('projects/{project}/tasks/{task}/attachments', [AttachmentController::class, 'store'])
        ->name('projects.tasks.attachments.store');
    Route::get('attachments/{attachment}', [AttachmentController::class, 'show'])->name('attachments.show');
    Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
});
