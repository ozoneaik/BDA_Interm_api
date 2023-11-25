<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileService
{
    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $as
     * @param string|null $old_file
     * @param string|null $disk
     * @return array
     * @throws \Exception
     */
    public function storeFile(UploadedFile $file, string $as, string $old_file = null, string $disk = null): array
    {
        if (is_null($disk)) $disk = config('filesystems.default');

        $file_name = $file->getClientOriginalName();
        $hash_name = $file->hashName();
        $hash_name_arr = str_split($hash_name, 2);
        $file_path = "/{$as}/{$hash_name_arr[0]}/{$hash_name_arr[1]}/{$hash_name_arr[2]}";
        $created = false;

        if ($this->isImage($file->getMimeType())) {
            try {
                $image = Image::make($file)
                    ->resize(2000, 2000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->orientate()
                    ->encode($file->getMimeType(), 80);

                Storage::disk($disk)->put("{$file_path}/{$hash_name}", $image);

                $created = true;
            } catch (\Exception $exception) {
                if (config('app.debug')) {
                    throw $exception;
                }
            }
        }

        if (!$created) {
            Storage::disk($disk)->put($file_path, $file);
        }

        $file_path .= '/' . $hash_name;

        if (!is_null($old_file)) {
            $this->deleteFile($old_file, $disk);
        }

        return [$file_path, $file_name];
    }

    /**
     * @param string $path
     * @param string $disk
     */
    public function deleteFile(string $path, string $disk = null): void
    {
        if (is_null($disk)) $disk = config('filesystems.default');

        if (!filter_var($path, FILTER_VALIDATE_URL)) {
            Storage::disk($disk)->delete($path);
        }
    }

    /**
     * @param string $mimetype
     * @return bool
     */
    private function isImage(string $mimetype): bool
    {
        return $mimetype === 'image/jpg'
            || $mimetype === 'image/jpeg'
            || $mimetype === 'image/gif'
            || $mimetype === 'image/png';
    }
}
