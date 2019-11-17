<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        // Création des Users
        $users = [];
        $usernames = ['alex33_game', 'bleucerise', 'j3r3mgame', 'lise_unicorn', 'molly_hyde', 'ok_clem'];
        $dateBetweenUser = $faker->dateTimeBetween('-30 days', '-20 days', null);

        foreach ($usernames as $k => $name) {

            $user = new User();
            $user->setUsername($name);
            $user->setEmail(strtolower($name) . '@mail.org');

            $password = $this->encoder->encodePassword($user,'azeaze');
            $user->setPassword($password);
            $user->setCreatedAt($dateBetweenUser);

            $manager->persist($user);
            array_push($users, $user);

        }

        // Création des Posts
        for ($i = 0; $i < 50; $i++) {

            $title = 'à modifier';
            $description = 'à modifier';
            $location = $faker->randomElement(['Bordeaux', 'Paris', 'Franconville', 'Toulouse', 'Nantes', 'Merignac', 'Nancy', 'Grenoble']);
            $categories = $faker->randomElement(['jeux-video','goodies','dvd', 'jeux-de-societe', 'retrogaming', 'book']);
            $state = $faker->randomElement(['new','very-good','good', 'bad', 'very-bad']);
            $isPublic = $faker->boolean(70);
            $dateBetween = $faker->dateTimeBetween('-20 days', 'now', null);

            $date = new \DateTime();

            $post = new Post();
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setLocation($location);
            $post->setCategories($categories);
            $post->setState($state);
            $post->setPublic($isPublic);
            $post->setCreatedAt($dateBetween);
            $post->setPublishedAt($dateBetween);

            // Auteur du post
            $k = array_rand($users);
            $author = $users[$k];
            $post->setAuthor($author);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
