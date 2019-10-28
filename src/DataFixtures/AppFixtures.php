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
        $usernames = ['Alex', 'Clem', 'Doc'];
        $avatars = [
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg',
            'https://www.listchallenges.com/f/items-dl/30b9cc37-1a6a-456a-97bf-f0121f5a0c26.jpg',
            'https://www.listchallenges.com/f/items-dl/e0fff511-3b03-4bd6-b5af-8c1d9d3671bd.jpg'
        ];


        foreach ($usernames as $k => $name) {

            $user = new User();
            $user->setUsername($name);
            $user->setEmail(strtolower($name) . '@mail.org');
            $user->setAvatar($avatars[$k]);

            $password = $this->encoder->encodePassword($user,'azeaze');
            $user->setPassword($password);

            $manager->persist($user);
            array_push($users, $user);

        }

        // Création des Posts
        for ($i = 0; $i < 30; $i++) {

            $title = $faker->realText(20);
            $description = $faker->realText(180);
            $price = $faker->numberBetween(10, 100);
            $location = $faker->randomElement(['Bordeaux', 'Paris', 'Franconville']);
            $categories = $faker->randomElements(['jeux vidéo','goodies','dvd'], 2);
            $isPublic = $faker->boolean(70);

            $date = new \DateTime();

            $post = new Post();
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setPrice($price);
            $post->setLocation($location);
            $post->setCategories($categories);
            $post->setPublic($isPublic);
            $post->setCreatedAt($date);
            $post->setPublishedAt($date->add(new \DateInterval('P1D')));

            // Auteur du post
            $k = array_rand($users);
            $author = $users[$k];
            $post->setAuthor($author);

            $manager->persist($post);
        }



        $manager->flush();
    }
}
