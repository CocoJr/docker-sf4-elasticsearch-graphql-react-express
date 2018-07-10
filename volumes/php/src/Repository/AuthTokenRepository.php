<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Repository;

use App\Entity\AuthToken;
use Doctrine\ORM\EntityRepository;

/**
 * @method AuthToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthToken[]    findAll()
 * @method AuthToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthTokenRepository extends EntityRepository
{
    /**
     * @param $value
     *
     * @return AuthToken|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    function findOneByValue($value)
    {
        return $this->createQueryBuilder('auth_token')
            ->select(['auth_token', 'user'])
            ->innerJoin('auth_token.user', 'user')
            ->where('auth_token.value = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
