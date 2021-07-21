<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class ChunkUpload
{
	public static function upload($disk, string $file, $request)
	{
		// create the file receiver
        $receiver = new FileReceiver($file, $request, HandlerFactory::classFromRequest($request));
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $save = $receiver->receive();
        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            return self::saveFile($save->getFile(), $disk);
        }
        // we are in chunk mode, lets send the current progress
        $handler = $save->handler();
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
	}

	public static function download($disk, $path)
	{
		if (Storage::disk($disk)->exists($path)) {
			return Storage::disk($disk)->download($path);
		}
	    return response()->download('images/no-image.png', 'no-image.png', ['Content-Type: application/png']);
	}

	public static function delete($disk, $path)
	{
		if (Storage::disk($disk)->exists($path)) {
			Storage::disk($disk)->delete($path);
		}
	}

	public static function url($disk, $path)
	{
		if (Storage::disk($disk)->exists($path)) {
			// if using private storage 
			// you can add logic url here
			return Storage::disk($disk)->url($path);
		}
		return asset('images/no-image.png');
	}

	// Chunk File
	// Original Pion Chunk Upload
    protected static function saveFileToS3($file)
    {
        $fileName = self::createFilename($file);
        $disk = Storage::disk('s3');
        // It's better to use streaming Streaming (laravel 5.4+)
        $disk->putFileAs('photos', $file, $fileName);
        // for older laravel
        // $disk->put($fileName, file_get_contents($file), 'public');
        $mime = str_replace('/', '-', $file->getMimeType());
        // We need to delete the file when uploaded to s3
        unlink($file->getPathname());
        return response()->json([
            'path' => $disk->url($fileName),
            'name' => $fileName,
            'mime_type' =>$mime
        ]);
    }
    
    protected static function saveFile(UploadedFile $file, $disk)
    {
        $fileName = self::createFilename($file);
        $file->move(Storage::disk($disk)->path(''), $fileName);

        return response()->json([
            'name' => $fileName,
        ]);
    }
    
    protected static function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = md5(time()) . "." . $extension;
        return $filename;
    }
}