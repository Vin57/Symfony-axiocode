<?php


namespace App\Domain\Product\Service;


use App\Entity\Picture;

interface PictureServiceInterface
{
    /**
     * Set given {@see $new_main_picture} as the new main one
     * of his own {@see Picture::$product}.
     */
    public function setAsMainPicture(Picture $new_main_picture);
}