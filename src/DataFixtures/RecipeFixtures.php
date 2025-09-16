<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['Plat Principal', 'Entrée', 'Dessert', 'Apéritif', 'Boisson', 'Sauce'];
        foreach( $categories as $categoryName ) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference($categoryName, $category);
        }
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));
        $i = 0;
        while ($i < 15) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->foodName())
                ->setContent($faker->paragraphs(3, true))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setDuration($faker->numberBetween(10, 180))
                ->setCategory($this->getReference($faker->randomElement($categories), Category::class))
                ->setUser($this->getReference('USER'.$faker->numberBetween(1,10), User::class));
            $manager->persist($recipe);
            $i++;
        }

        $manager->flush();
    }
}
