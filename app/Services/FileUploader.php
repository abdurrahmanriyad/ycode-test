<?php

namespace App\Services;

use App\Exceptions\InvalidFile;
use Illuminate\Http\UploadedFile;

class FileUploader
{
    /**
     * Uploads files based on the storage driver
     * @param UploadedFile $file
     * @param string $dir
     * @return false|string
     * @throws InvalidFile
     */
    public function upload(UploadedFile $file, string $dir = '/')
    {
        if (!$file->isValid()) throw new InvalidFile;

        return $file->store($dir, ['disk' => 'public']);
    }
}
