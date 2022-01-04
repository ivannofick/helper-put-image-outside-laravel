<?php

namespace App\Helpers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 *
 * @author ivannofick
 * ruangapp.com
 */
class CustomHelpersByIvan
{

    public static function handlerUploadFiles($files, $pathId, $nameFile=''){
        // $path = public_path();
        $path = env("APP_FOLDER")."/image/blog";
        if (strpos($files, 'assets') == false) {
            [$namePath, $randomNameFile] = self::handlerMakeNameFile($files, $nameFile);
            $path_iso_file = ($pathId == '') ? $namePath : $namePath.$pathId;
            $data =  $files;
            $data = explode(',', $data)[1];
            if (preg_match('/^(?:[data]{4}:(image)\/[a-z]*)/', $files)){
                File::isDirectory($path . $path_iso_file) or File::makeDirectory($path . $path_iso_file, 0777, true, true);
                file_put_contents($path . $path_iso_file . $randomNameFile, base64_decode($data));
                return $path_iso_file.$randomNameFile;
            } else {
                File::isDirectory($path . $path_iso_file . '/' . $randomNameFile . '/' ) or File::makeDirectory($path . $path_iso_file . '/' . $randomNameFile . '/', 0777, true, true);
                file_put_contents($path . $path_iso_file . '/' . $randomNameFile . '/' . $nameFile, base64_decode($data));
                return $path_iso_file.'/'.$randomNameFile . '/' . $nameFile;
            }
        }
        return $files;
    }


    /**
     * handler make a name file
     * @param string $files
     * @param string $nameFile
     * @return array path_file
     */
    public static function handlerMakeNameFile($files, $nameFile)
    {
        $currentTime = Carbon::now();
        $random = Str::random(15).'-'.$currentTime->format('YmdHis');
        if ($nameFile != '') {
            $pecahNameFile = explode(".", $nameFile);
            $getExtensiFile = count($pecahNameFile) - 1;
            // $randomNameFile = $random.'.'.$pecahNameFile[$getExtensiFile];
            $randomNameFile = $random;
        } else {
            $img = explode(',', $files);
            $ini = substr($img[0], 11);
            $type = explode(';', $ini);
            $randomNameFile = $random.'.'.$type[0];
        }
        return ['/assets/', $randomNameFile];
    }
}
