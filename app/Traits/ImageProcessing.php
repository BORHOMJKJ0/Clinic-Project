<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait ImageProcessing
{
    public function get_mime($mime)
    {
        switch ($mime) {
            case 'image/jpeg':
                $ext = '.jpg';
                break;
            case 'image/png':
                $ext = '.png';
                break;
            case 'image/gif':
                $ext = '.gif';
                break;
            case 'image/bmp':
                $ext = '.bmp';
                break;
            case 'image/webp':
                $ext = '.webp';
                break;
            case 'image/tiff':
                $ext = '.tiff';
                break;
            case 'image/svg+xml':
                $ext = '.svg';
                break;
            case 'image/x-icon':
                $ext = '.ico';
                break;
            default:
                $ext = '';
                break;
        }
        return $ext;
    }

    public function saveImage($file)
    {
        $image= Image::make($file);

        $ext = $this->get_mime($image->mime());
        $random_str= Str::random(8);
        $image_path= $random_str.time().$ext;

        $save_path= public_path('images');
        if (!file_exists($save_path)) {
            mkdir($save_path,0777,true);
        }
        $image->save($save_path.'/'.$image_path);

        return url('images/'.$image_path);
    }
}
