<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHelper
{

    private static $disk = 'public';
    private static $path = 'uploads';


    /**
     * Upload and store a file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string $disk
     * @param string|null $name
     * @return string
     */
    public static function uploadFile(UploadedFile $file, $path = null, $name = null, $disk = null)
    {
        // Validate the file type and size
        self::validateFile($file);

        // Set the file name
        $filename = is_null($name) ? self::generateFilename($file) : self::setName($name, $file);
        //Set path
        $path = is_null($path) ? self::$path : $path;
        //Set Disk
        $disk = is_null($disk) ? self::$disk : $disk;

        // Store the file to the specified disk and path
        $file->storeAs($path, $filename, $disk);

        // Return the full URL of the file
        // return Storage::disk($disk)->url($path . '/' . $filename);
        return $path . '/' . $filename;
    }

    /**
     * Validate the file's type and size.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @throws \InvalidArgumentException
     */
    protected static function validateFile(UploadedFile $file)
    {
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'image/webp',
            'image/svg+xml',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/x-icon',
            'image/vnd.microsoft.icon',
            'video/mp4', 

        ];
        $maxSize = 204845; // Maximum file size (KB)

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid file type.');
        }

        if ($file->getSize() > $maxSize * 1024) {
            throw new \InvalidArgumentException('File size exceeds the maximum limit.');
        }
    }

    public static function getFileUrl($filename, $path = null, $disk = null)
    {
        // Set path
        $path = is_null($path) ? self::$path : $path;

        // Set disk
        $disk = is_null($disk) ? self::$disk : $disk;

        // Check if file exists
        if (!Storage::disk($disk)->exists($path . '/' . $filename)) {
            throw new \InvalidArgumentException('File does not exist.');
        }

        // Return the full URL of the file
        return Storage::disk($disk)->url($path . '/' . $filename);
    }

    /**
     * Generate a unique file name.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected static function generateFilename(UploadedFile $file)
    {
        // Get the file extension
        $extension = $file->getClientOriginalExtension();

        // Create a unique file name
        return Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
    }

    /**
     * Set a custom file name.
     *
     * @param string $name
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected static function setName($name, UploadedFile $file)
    {
        // Get the file extension
        $extension = $file->getClientOriginalExtension();

        // Create a custom file name
        return Str::slug($name) . '.' . $extension;
    }

    /**
     * Delete a file.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public static function deleteFile($path, $disk = 'public')
    {
        return Storage::disk($disk)->delete($path);
    }
}