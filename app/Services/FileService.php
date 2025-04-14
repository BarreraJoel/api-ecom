<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileService
{
    public function __construct() {}

    public static function upload(UploadedFile $file, string $path, string $name)
    {
        return $file->storeAs($path, $name, 'public');
    }

    public function generateFileName($id)
    {
        $formattedId = $this->formatId($id);
        $filename = $formattedId . '_' . now()->format('dmY_His') . '.jpg';
        return $filename;
    }

    private function formatId($id)
    {
        $formattedId = '';

        if ($id < 10) {
            $formattedId = '00';
        } else if ($id < 100) {
            $formattedId = '0';
        }

        $formattedId .= $id;

        return $formattedId;
    }
}
