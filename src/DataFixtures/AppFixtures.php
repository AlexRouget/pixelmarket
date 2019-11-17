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
        $usernames = ['Alex', 'Clem', 'Doc', 'Didier', 'Michel', 'Henri', 'Thomas', 'O\'maley'];
        $avatars = [
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg',
            'https://www.listchallenges.com/f/items-dl/30b9cc37-1a6a-456a-97bf-f0121f5a0c26.jpg',
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg',
            'https://www.listchallenges.com/f/items-dl/e0fff511-3b03-4bd6-b5af-8c1d9d3671bd.jpg',
            'https://www.listchallenges.com/f/items-dl/e0fff511-3b03-4bd6-b5af-8c1d9d3671bd.jpg',
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg',
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg',
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg'
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
        for ($i = 0; $i < 100; $i++) {

            $title = $faker->realText(20);
            $description = $faker->realText(180);
            $attachment = $faker->imageUrl(640, 480);
            $price = $faker->numberBetween(10, 500);
            $location = $faker->randomElement(['Bordeaux', 'Paris', 'Franconville']);
            $categories = $faker->randomElement(['jeux-video','goodies','dvd', 'jeux-de-societe', 'retrogaming']);
            $state = $faker->randomElement(['new','very-good','good', 'bad', 'very-bad']);
            $isPublic = $faker->boolean(70);
            $dateBetween = $faker->dateTimeBetween('-30 days', 'now', null);

            $date = new \DateTime();

            $post = new Post();
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setAttachment($attachment);
            $post->setPrice($price);
            $post->setLocation($location);
            $post->setCategories($categories);
            $post->setPublic($isPublic);
            $post->setCreatedAt($date);
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
