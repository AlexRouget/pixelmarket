<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    /**
     * Get the posts and the related amount of comments
     * Results are paginated.
     *
     * @param int $start The initial offset for the result
     * @param int $take The number of posts to select
     *
     * @return array The posts for the homepage
     */
    public function findHomepage(int $start = 0, $take = 5)
    {
       return $this->findPostList($start, $take, true);
    }

    /**
     * Get all the posts and the related amount of comments
     *
     * @return array The posts for the homepage
     */
    public function findPostList($start, $take, $onlyPublic = false)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p as post')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($take);

        if ($onlyPublic === true) {
            $queryBuilder = $queryBuilder->where('p.public = true');
        }

        $results = $queryBuilder->getQuery()->getResult();

        $posts = [];
        foreach ($results as $result) {
            $post = $result['post'];
            array_push($posts, $post);
        }

        return $posts;
    }

    public function countForHomepage()
    {
        return $this->count(['public' => true]);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
