<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    private  UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
       
        // ingredients
        $ingredient = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));

            $ingredient[] = $ingredient;
            $manager->persist($ingredient);
        }

        //recettes
    for ($j=0; $j < 25; $j++) { 
        $recette = new Recette();
        $recette->setNom($this->faker->word())
            ->setTemps(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
            ->setNbPersonne(mt_rand(0, 1) == 1 ? mt_rand(1, 49) : null)
            ->setDifficulté(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
            ->setDescription($this->faker->text(300))
            ->setPrix(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
            ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);

        for ($k=0; $k < mt_rand(5, 15); $k++) { 
            $recette->addlisteIngredient($ingredient[mt_rand(0, count($ingredient) - 1)]);
        }
        $manager->persist($recette);

    }


        //Users
        for ($l=0; $l < 10 ; $l++) { 
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER']);
                
            $hashPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );




            $manager->persist($user);  
        }

        $manager->flush();
    }

}
