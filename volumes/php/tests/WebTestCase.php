<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests;

use App\DataFixtures\AuthTokenFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\AuthToken;
use App\Entity\File;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCase extends BaseWebTestCase
{
    /** @var EntityManager */
    protected $em;

    /** @var bool */
    protected static $fixturesIsLoaded = false;

    /**
     * Boots the kernel
     * Sets up the entity manager
     */
    protected function setUp()
    {
        $container = $this->getContainer();

        if (!$this->em) {
            $this->em = $container->get('doctrine.orm.entity_manager');
        }

        $this->loadFixtures([UserFixtures::class, AuthTokenFixtures::class]);
        $this->runCommand('fos:elastica:populate');

        parent::setUp();
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Translation\Translator|\Symfony\Component\Translation\IdentityTranslator
     */
    protected function getTranslator()
    {
        return $this->getContainer()->get('translator');
    }

    /**
     * @param array $payload ['user', 'errors']
     * @param bool $isValid
     */
    protected function assertUserPayload($payload, $isValid)
    {
        $this->assertTrue(is_array($payload));
        $this->assertArrayHasKey('user', $payload);
        $this->assertArrayHasKey('errors', $payload);
        $this->assertTrue(is_array($payload['errors']));
        $this->assertTrue($isValid ? is_object($payload['user']) : is_null($payload['user']));
        $this->assertTrue($isValid ? empty($payload['errors']) : !empty($payload['errors']));
    }

    /**
     * @param array $payload ['token', 'errors']
     * @param bool $isValid
     */
    protected function assertTokenPayload($payload, $isValid)
    {
        $this->assertTrue(is_array($payload));
        $this->assertArrayHasKey('token', $payload);
        $this->assertArrayHasKey('errors', $payload);
        $this->assertTrue(is_array($payload['errors']));
        $this->assertTrue($isValid ? is_object($payload['token']) : is_null($payload['token']));
        $this->assertTrue($isValid ? empty($payload['errors']) : !empty($payload['errors']));
    }

    /**
     * @param User $user
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param string $plainPasswordConfirm
     */
    protected function assertUser(User $user, $username = null, $email = null)
    {
        $this->assertNotNull($user->getId());
        $this->assertNotNull($user->getTermsAcceptedAt());
        $this->assertNotEmpty($user->getRoles());
        $this->assertTrue($user->hasRole('ROLE_USER'));
        $this->assertNotNull($user->getRegistratedAt());
        $this->assertEquals(true, $user->isEnabled());
        if ($username) {
            $this->assertEquals($username, $user->getUsername());
        }
        if ($email) {
            $this->assertEquals($email, $user->getEmail());
        }
    }

    /**
     * @param AuthToken $authToken
     * @param null $username
     */
    protected function assertToken(AuthToken $authToken, $username = null)
    {
        $this->assertNotNull($authToken->getId());
        $this->assertNotNull($authToken->getUser());
        $this->assertNotNull($authToken->getValue());
        $this->assertNotNull($authToken->getCreatedAt());
        $this->assertNotNull($user = $authToken->getUser());
        if ($username) {
            $this->assertEquals($username, $user->getUsername());
        }
    }

    /**
     * @param File $file
     */
    protected function assertFile(File $file)
    {
        $this->assertNotNull($file->getId());
        $this->assertNotNull($file->getMimeType ());
        $this->assertNotNull($file->getName());
        $this->assertNotNull($file->getPath());
        $this->assertNotNull($file->getPublicPath());
        $this->assertNotNull($file->getSize());
        $this->assertNotNull($file->getCreatedAt());
    }

    protected function assertPagination(array $payload)
    {
        $this->assertArrayHasKey('page', $payload);
        $this->assertArrayHasKey('limit', $payload);
        $this->assertArrayHasKey('total', $payload);
        $this->assertArrayHasKey('items', $payload);
    }

    /**
     * @param null $username
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    protected function getToken($username = null)
    {
        $tokenStorage = $this->getContainer()->get('security.token_storage');
        if ($username) {
            if ($user = $this->em->getRepository('App:User')->findOneBy(array('username' => $username))) {
                $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
                $tokenStorage->setToken($token);
            }
        }

        return $tokenStorage;
    }
}