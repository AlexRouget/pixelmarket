<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[] Returns an array of User objects
     */

    public function findById($id)
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.id = :val')
            ->setParameter('val', $id)
            ->orderBy('user.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

      /**
     * Get all the users and the related amount of comments
     *
     * @return array The users for the adminpage
     */
    public function findUserList($start, $take, $onlyUnchecked = false)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->select('u as user')
            ->groupBy('u.id')
            ->orderBy('u.createdAt', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($take);

            if ($onlyUnchecked === true) {
                $queryBuilder = $queryBuilder->where('u.checked = false');
            }

        $results = $queryBuilder->getQuery()->getResult();

        $users = [];
        foreach ($results as $result) {
            $user = $result['user'];
            array_push($users, $user);
        }

        return $users;
    }

    public function countByChecked()
    {
        return $this->count(['checked' => false]);
    }
}
