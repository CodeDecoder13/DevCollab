<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Project $project, Task $task)
    {
        $task->comments()->create([
            'body' => $request->validated()['body'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Comment added.');
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return redirect()->back()->with('success', 'Comment updated.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted.');
    }
}
