<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\DataFixtures;

use App\Entity\AuthToken;
use App\Entity\User;
use App\Security\AuthTokenAuthenticator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtures
 */
class AuthTokenFixtures extends Fixture implements DependentFixtureInterface
{
    const ADMIN_AUTH_TOKEN = 'VX82JM1r8rg+6nkNlK5uGNrVgBMoNtl3icAVQ0hwwt3yJwPN1hVo13NDb7K3Y9bbYjM=';
    const ADMIN_AUTH_TOKEN_INVALID = 'IX88JM1r8rg+6nkNlK5uGNrVgBMoNtl3icAVQ0hwwt3yJRfN1hVo13NDb7K3Y9bbYjM=';
    const USER_AUTH_TOKEN =  'VX82JM1r8rg+6nkNlK5uGNrVgBMoNtl3icAVQ0hADt4yJwPN1hVo13NDb7K3Y9beTjC=';
    const USER_AUTH_TOKEN_INVALID =  'IX82JM1r8rg+6nkNlK5uGNrVgBMvctl3icAVQ0hwwt4yJwPN1hVo13NDb7K3Y9beTjC=';
    const USER_AUTH_TOKEN_DISABLE =  'IX82JM1r8rg+6nkNlK5uGNrVgBMoNtl3icAQd0hwwt4yJwPN1hVo13NDb7K3Y9PsDjC=';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->createAdminAuthTokenValid($manager);
        $this->createAdminAuthTokenInvalid($manager);
        $this->createUserAuthTokenValid($manager);
        $this->createUserAuthTokenInvalid($manager);
        $this->createUserAuthTokenDisable($manager);
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function createAdminAuthTokenValid(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);
        $authToken = new AuthToken();

        $authToken->setValue(self::ADMIN_AUTH_TOKEN)
            ->setCreatedAt(new \DateTime())
            ->setUser($user)
        ;

        $user->addAuthToken($authToken);

        $manager->persist($authToken);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function createAdminAuthTokenInvalid(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);
        $authToken = new AuthToken();

        $authToken->setValue(self::ADMIN_AUTH_TOKEN_INVALID)
            ->setCreatedAt(new \DateTime('-'.(AuthTokenAuthenticator::TOKEN_VALIDITY_DURATION*2).' minutes'))
            ->setUser($user)
        ;

        $user->addAuthToken($authToken);

        $manager->persist($authToken);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function createUserAuthTokenValid(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_USER_REFERENCE);
        $authToken = new AuthToken();

        $authToken->setValue(self::USER_AUTH_TOKEN)
            ->setCreatedAt(new \DateTime())
            ->setUser($user)
        ;

        $user->addAuthToken($authToken);

        $manager->persist($authToken);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function createUserAuthTokenInvalid(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_USER_REFERENCE);
        $authToken = new AuthToken();

        $authToken->setValue(self::USER_AUTH_TOKEN_INVALID)
            ->setCreatedAt(new \DateTime('-'.(AuthTokenAuthenticator::TOKEN_VALIDITY_DURATION*2).' minutes'))
            ->setUser($user)
        ;

        $user->addAuthToken($authToken);

        $manager->persist($authToken);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function createUserAuthTokenDisable(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_DISABLE_REFERENCE);
        $authToken = new AuthToken();

        $authToken->setValue(self::USER_AUTH_TOKEN_DISABLE)
            ->setCreatedAt(new \DateTime())
            ->setUser($user)
        ;

        $user->addAuthToken($authToken);

        $manager->persist($authToken);
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}