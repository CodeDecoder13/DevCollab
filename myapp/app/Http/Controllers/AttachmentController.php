<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attachment\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(StoreAttachmentRequest $request, Project $project, Task $task)
    {
        $file = $request->file('file');
        $path = $file->store('attachments', 'local');

        $task->attachments()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'File uploaded.');
    }

    public function show(Attachment $attachment)
    {
        $this->authorize('view', $attachment);

        return Storage::disk('local')->download($attachment->path, $attachment->filename);
    }

    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);

        Storage::disk('local')->delete($attachment->path);
        $attachment->delete();

        return redirect()->back()->with('success', 'Attachment deleted.');
    }
}
