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
    public function findHomepage($categories, int $start = 0, $take = 12)
    {
       return $this->findPostList($categories, $start, $take, true, false);
    }

    /**
     * Get all the posts and the related amount of comments
     *
     * @return array The posts for the homepage
     */
    public function findPostList($categories, $start, $take, $onlyPublic = false, $onlyUnchecked = false)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p as post')
            ->groupBy('p.id')
            ->orderBy('p.published_at', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($take);

            
            if ($onlyPublic === true) {
                $queryBuilder = $queryBuilder->where('p.public = true');
            }
            if ($onlyUnchecked === true) {
                $queryBuilder = $queryBuilder->where('p.checked = false');
            }
            if ($categories) {
                $queryBuilder = $queryBuilder->where('p.categories = :val')->setParameter('val', $categories);
            }

        $query = $queryBuilder->getQuery();
        // dump($query);die;
        $results = $query->getResult();

        $posts = [];
        foreach ($results as $result) {
            $post = $result['post'];
            array_push($posts, $post);
        }

        return $posts;
    }

    /**
     * Get all the posts researched
     *
     * @return array The posts for the searchBar
     */
    public function findPost($search)
    {
        
        $queryBuilder = $this->createQueryBuilder('p')
        ->select('p as post')
        ->groupBy('p.id')
        ->orderBy('p.published_at', 'DESC')
        ->where('p.title = :val')->setParameter('val', $search)
        ;

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


    public function countByCat($cat)
    {
        return $this->count(['categories' => $cat]);
    }

    public function countByChecked()
    {
        return $this->count(['checked' => false]);
    }
}
