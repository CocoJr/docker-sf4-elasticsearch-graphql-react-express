<?php

namespace App\Tests\GraphQL\Mutation;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\GraphQL\Mutation\AdminUserMutation;
use App\Manager\UserManager;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminUserMutationTest extends WebTestCase
{
    /**
     * @dataProvider providerEdit
     *
     * @param $user
     * @param $id
     * @param $username
     * @param $email
     * @param $plainPassword
     * @param $plainPasswordConfirm
     * @param $registratedAt
     * @param $enable
     * @param $payloadTestCallback
     */
    public function testEdit($user, $id, $username, $email, $plainPassword, $plainPasswordConfirm, $registratedAt, $enable, $payloadTestCallback, $isValid)
    {
        $this->getToken($user);

        $userManager = $this->getContainer()->get(UserManager::class);
        $adminUserMutation = new AdminUserMutation();

        try {
            $payload = $adminUserMutation->edit($userManager, $id, $username, $email, $plainPassword, $plainPasswordConfirm, $registratedAt, $enable);
        } catch (\Exception $e) {
            if (!isset($payload)) {
                $payload = [];
            }

            $this->$payloadTestCallback($payload, $e);

            return;
        }

        $this->assertUserPayload($payload, $isValid);
        if ($isValid) {
            $this->assertUserValidPayload($payload, $id, $username, $email, $registratedAt, $enable);
        } else {
            $this->$payloadTestCallback($payload, null);
        }
    }

    /**
     * @return array
     */
    public function providerEdit()
    {
        $registratedAt = new \DateTime('+1 hours');
        $registratedAt->setTime($registratedAt->format('H'), $registratedAt->format('i'), $registratedAt->format('s'), 0);
        
        return [
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                null,
                null,
                null,
                null,
                null,
                false,
                null,
                true,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                null,
                null,
                null,
                null,
                null,
                true,
                null,
                true,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                'newUsername',
                'newEmail@test.fr',
                'newPassword',
                'newPassword',
                $registratedAt,
                true,
                null,
                true,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                UserFixtures::NB_USERS+1,
                null,
                null,
                null,
                null,
                null,
                true,
                'assertUserNotFound',
                false,
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                1,
                null,
                null,
                null,
                null,
                null,
                true,
                'assertAccessDenied',
                false,
            ],
        ];
    }

    /**
     * @dataProvider providerUploadImgProfil
     *
     * @param $username
     * @param $files
     * @param $isValid
     * @param $errorsCallback
     *
     * @throws \Exception
     */
    public function testUploadImgProfil($username, $id, $files, $isValid, $errorsCallback)
    {
        $container = $this->getContainer();
        $tokenStorage = $this->getToken($username);

        $userManager = $container->get(UserManager::class);
        $requestStack = $container->get('request_stack');
        $request = new Request(array(), array(), array(), array(), $files);
        $requestStack->push($request);
        $adminUserMutation = new AdminUserMutation();

        try {
            $payload = $adminUserMutation->uploadImgProfil($userManager, $requestStack, $id);
            if (!$isValid) {
                $this->assertNull($payload['file']);
                foreach ($errorsCallback as $errorCallback) {
                    $this->$errorCallback($payload['errors'], null);
                }
            } else {
                $this->assertFile($payload['file']);
                $this->assertEmpty($payload['errors']);

                $user = $tokenStorage->getToken()->getUser();
                $imgProfil = $user->getImgProfil();
                $this->assertNotNull($tokenStorage->getToken()->getUser()->getImgProfil());

                $payload = $adminUserMutation->uploadImgProfil($userManager, $requestStack, $id);
                $this->assertFile($payload['file']);
                $this->assertEmpty($payload['errors']);
                $this->assertNotEquals($imgProfil, $user->getImgProfil());
            }
        } catch (\Exception $e) {
            $this->assertFalse($isValid);

            if (!isset($payload)) {
                $payload = [];
            }

            foreach ($errorsCallback as $errorCallback) {
                $this->$errorCallback($payload, $e);
            }
        }
    }

    /**
     * @return array
     */
    public function providerUploadImgProfil()
    {
        $img = tempnam(sys_get_temp_dir(), 'testImg');
        imagepng(imagecreatetruecolor(10, 10), $img);
        $imgFile = new UploadedFile($img, 'test_img.png', 'image/png', null, true);
        $php = tempnam(sys_get_temp_dir(), 'testPhp');
        file_put_contents($php, '<?php echo "ok";');
        $phpFile = new UploadedFile($php, 'test_php.php', 'application/x-php', null, true);

        return [
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                [$imgFile],
                true,
                null,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                [$phpFile],
                false,
                ['assertInvalidFile'],
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                [$imgFile],
                true,
                null,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                [$phpFile],
                false,
                ['assertInvalidFile'],
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                1,
                [$phpFile, $imgFile],
                false,
                ['assertInvalidFile'],
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                -1,
                [$phpFile, $imgFile],
                false,
                ['assertUserNotFound'],
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                1,
                [$imgFile],
                false,
                ['assertAccessDenied'],
            ],
        ];
    }

    /**
     * @param $payload
     * @param $id
     * @param $enable
     */
    private function assertUserValidPayload($payload, $id, $username, $email, $registratedAt, $enable)
    {
        /** @var User $user */
        $user = $payload['user'];
        $this->assertEquals($id, $user->getId());
        if (!is_null($username)) {
            $this->assertEquals($username, $user->getUsername());
        }
        if (!is_null($email)) {
            $this->assertEquals($email, $user->getEmail());
        }
        if (!is_null($registratedAt)) {
            $this->assertEquals($registratedAt, $user->getRegistratedAt());
        }
        if (!is_null($enable)) {
            $this->assertEquals($enable, $user->isEnabled());
        }
    }

    /**
     * @param $payload
     * @param $id
     * @param $enable
     */
    private function assertUserNotFound($payload, $erreur)
    {
        /** @var User $user */
        $errors = $payload['errors'];
        $this->assertTrue(is_array($errors));
        $this->assertNotEmpty($errors);
        $this->assertEquals($errors[0]['message'], $this->getTranslator()->trans('user.admin.not_found', [], 'validators'));
    }

    /**
     * @param $erreur
     * @param $id
     * @param $enable
     */
    private function assertAccessDenied($payload, $erreur)
    {
        $this->assertTrue(is_object($erreur));
        $this->assertEquals(AccessDeniedException::class, get_class($erreur));
    }

    /**
     * @param $errors
     */
    private function assertInvalidFile($errors)
    {
        $this->assertArrayHasKey(0, $errors);
        $expected = $this->getTranslator()->trans('This file is not a valid image.', [], 'validators');
        $this->assertEquals($expected, $errors[0]['message']);
        $this->assertEquals('imgProfil.file', $errors[0]['key']);
    }
}