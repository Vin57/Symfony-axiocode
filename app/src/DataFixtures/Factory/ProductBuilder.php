<?php
namespace App\DataFixtures\Factory;

use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\User\Repository\UserRepository;
use App\Entity\Opinion;
use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\User;
use Faker\Factory as FakerF;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\File;

class ProductBuilder
{

    private int $nbOpinions;
    private int $nbPictures;
    private \Faker\Generator $faker;
    private CategoryRepository $categoryRepository;
    private UserRepository $userRepository;

    public function __construct(CategoryRepository $categoryRepository, UserRepository $userRepository) {
        $this->faker = FakerF::create();
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Build a product entity.
     * @param array|null $categories An array of category to randomly peek inside.
     * Will be used as the {@see Product::$category}
     * @param array|null $users An array of users to randomly peek inside.
     * Will be used as the {@see Opinion::$user} for the {@see Product::$opinions}
     */
    public function build(?array $categories = null, ?array $users = null): Product
    {
        $categories ??= $this->categoryRepository->findAll();
        $users ??= $this->userRepository->findAll();

        $product = new Product();
        $product->setName(implode(" ", $this->faker->unique->words(2)))
            ->setCategory($categories[$this->faker->numberBetween(0, count($categories) - 1)]);

        $this->addPicturesToProduct($product);
        $this->addOpinionsToProduct($product, $users);
        return $product;
    }

    public function withPictures(int $nbPictures = 1): self {
        $this->nbPictures = $nbPictures;
        return $this;
    }

    public function withOpinions(int $nbOpinions = 1): self {
        $this->nbOpinions = $nbOpinions;
        return $this;
    }

    private function addPicturesToProduct(Product $product)
    {
        for ($i = 0; $i <= $this->nbPictures; $i++) {
            $fakeFile = new File($this->faker->image());
            $product->addPicture((new Picture())->setFile($fakeFile)->setPath($fakeFile->getPathname()));
        }
    }

    /**
     * @param Product $product
     * @param User[] $users An array of users which will be used as opinion's
     * author.
     */
    private function addOpinionsToProduct(Product $product, array $users)
    {
        if ($this->nbOpinions > count($users)) {
            throw new InvalidArgumentException(sprintf('There are %1$s number of opinion to build but only %2$s users. Provide at least %1$s users', $this->nbOpinions, count($users)));
        }
        for ($i = 0; $i <= $this->nbOpinions; $i++) {
            $user = $this->faker->unique->randomElement($users);
            $product->addOpinion(
                (new Opinion())
                ->setComment($this->faker->realText())
                ->setRating($this->faker->numberBetween(1, 5))
                ->setProduct($product)
                ->setUser($user)
            );
        }
        $this->faker->unique(true);
    }

}