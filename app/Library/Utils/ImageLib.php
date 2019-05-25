<?php

namespace App\Library\Utils;

use Config;
use Storage;

class ImageLib {

    protected $image;

    public function __construct() {
        if (isset($this->image))
            return $this->image;

        $this->image = Storage::disk('public');
    }

    /**
     * Function by upload image to S3
     * @param string $path
     * @param image $image
     * @return boolean
     */
    public function uploadImage($path, $image) {
        $resultUpload = $this->image->put($path, file_get_contents($image), 'public');

        return $resultUpload;
    }

    /**
     * Function by delete image to S3
     * @param string $path
     * @return boolean
     */
    public function deleteImage($path) {
        try {
            if ($this->image->exists($path)) {
                $resultDelete = $this->image->delete($path);
                return (($resultDelete) ? TRUE : FALSE);
            }
            return FALSE;
        } catch (\Exception $ex) {
            return FALSE;
        }
    }

    public function getPathImage($path) {
        $url = $this->image->url($path);
        return asset($url);
    }

}
