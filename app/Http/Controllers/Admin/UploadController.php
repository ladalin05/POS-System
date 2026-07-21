<?php

namespace App\Http\Controllers\Admin;

use App\Services\MinioStorageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\DeleteFileRequest;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(UploadFileRequest $request, MinioStorageService $storage)
    {
        $validated = $request->validated();
        $result = $storage->upload($validated['file'], $validated['folder']); 

        return $this->successResponse(__('messages.upload_successfully'), $result);
    }

    public function destroy(DeleteFileRequest $request, MinioStorageService $storage)
    {
        $validated = $request->validated();

        $storage->delete($validated['path']);

        return $this->successResponse(__('messages.file_delete_successfully'));
    }
}
