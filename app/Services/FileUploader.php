<?php

namespace App\Services;

use App\Exceptions\InvalidFile;
use Illuminate\Http\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file, string $dir = '/')
    {
        if (!$file->isValid()) throw new InvalidFile;

        return $file->store($dir, ['disk' => 'public']);
    }
}
