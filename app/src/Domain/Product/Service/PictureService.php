<?php


namespace App\Domain\Product\Service;

use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;

class PictureService implements PictureServiceInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function setAsMainPicture(Picture $new_main_picture): void
    {
        $former_main_picture = $new_main_picture->getProduct()->getPicture();
        if ($former_main_picture->getId() === $new_main_picture->getId()) {
            return; // Nothing to update
        }
        $this->em->persist($former_main_picture->setIsMain(false));
        $this->em->persist($new_main_picture->setIsMain(true));
        $this->em->flush();
    }
}