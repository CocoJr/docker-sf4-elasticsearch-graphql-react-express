<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 */
class UserFixtures extends Fixture
{
    const NB_ADMIN_USERS = 8;
    const NB_USER_USERS = 80;
    const NB_USERS = self::NB_ADMIN_USERS + self::NB_USER_USERS;

    const ADMIN_USER_REFERENCE = 'ADMIN_USER_REFERENCE';
    const ADMIN_BASE_USERNAME = 'admin';
    const ADMIN_BASE_PASSWORD = 'admin';

    const USER_USER_REFERENCE = 'USER_USER_REFERENCE';
    const USER_BASE_USERNAME = 'user';
    const USER_BASE_PASSWORD = 'password';

    const USER_DISABLE_REFERENCE = 'USER_DISABLE_REFERENCE';
    const USER_DISABLE_ID = self::NB_USERS;
    const USER_DISABLE_USERNAME = 'userDisable';
    const USER_DISABLE_PASSWORD = 'userDisable';

    private $encoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->createAdminUsers($manager);
        $this->createUserUsers($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function createAdminUsers(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::NB_ADMIN_USERS; $i++) {
            $combine = $i > 1 ? '_'.$i : null;
            $user = new User();

            $user->setUsername(self::ADMIN_BASE_USERNAME.$combine)
                ->setEmail(self::ADMIN_BASE_USERNAME.$combine.'@example.fr')
                ->setIsActive(true)
                ->setTermsAcceptedAt(new \DateTime())
                ->setPassword($this->encoder->encodePassword($user, self::ADMIN_BASE_PASSWORD.$combine))
                ->setRoles(array('ROLE_USER', 'ROLE_ADMIN'))
            ;

            if ($i === 1) {
                $this->addReference(self::ADMIN_USER_REFERENCE, $user);
            }

            $manager->persist($user);
            $manager->flush();
        }
    }

    /**
     * @param ObjectManager $manager
     */
    private function createUserUsers(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::NB_USER_USERS; $i++) {
            $combine = $i > 1 ? '_'.$i : null;
            $user = new User();

            $user->setUsername(self::USER_BASE_USERNAME.$combine)
                ->setEmail(self::USER_BASE_USERNAME.$combine.'@example.fr')
                ->setIsActive(true)
                ->setTermsAcceptedAt(new \DateTime())
                ->setPassword($this->encoder->encodePassword($user, self::USER_BASE_PASSWORD.$combine))
                ->setRoles(array('ROLE_USER'))
            ;

            if ($i === 1) {
                $this->addReference(self::USER_USER_REFERENCE, $user);
            }
            if ($i + self::NB_ADMIN_USERS === self::USER_DISABLE_ID) {
                $user->setIsActive(false)
                    ->setUsername(self::USER_DISABLE_USERNAME)
                    ->setEmail(self::USER_DISABLE_USERNAME.'@example.fr')
                    ->setPassword($this->encoder->encodePassword($user, self::USER_DISABLE_PASSWORD))
                ;

                $this->addReference(self::USER_DISABLE_REFERENCE, $user);
            }

            $manager->persist($user);
            $manager->flush();
        }
    }
}