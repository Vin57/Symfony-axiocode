<?php


namespace App\Domain\Product\Factory;


use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureFactory
{
    public static function buildFromFile(UploadedFile $file, bool $isMain = false): Picture {
        return (new Picture())->setFile($file)->setName($file->getFilename())->setIsMain($isMain);
    }
}