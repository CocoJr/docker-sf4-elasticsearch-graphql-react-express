<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\GraphQL\Mutation;

use App\DataFixtures\UserFixtures;
use App\GraphQL\Mutation\UserMutation;
use App\Manager\UserManager;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UserMutationTest extends WebTestCase
{
    /**
     * @dataProvider providerCreate
     *
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param string $plainPasswordConfirm
     * @param bool $termsAccepted
     * @param bool $isValid
     * @param string[] $errorsCallback
     */
    public function testCreate($username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted, $isValid, $errorsCallback)
    {
        $container = $this->getContainer();
        $userManager = $container->get(UserManager::class);
        $userMutation = new UserMutation();
        $payload = $userMutation->create($userManager, $username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted);

        $this->assertUserPayload($payload, $isValid);
        if (!$isValid) {
            foreach ($errorsCallback as $errorCallback) {
                $this->$errorCallback($payload['errors'], $username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted);
            }
        } else {
            $this->assertEmpty($payload['errors']);
            $this->assertUser($payload['user'], $username, $email);
        }
    }

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
    public function testEdit($user, $username, $email, $plainPassword, $plainPasswordConfirm, $payloadTestCallback, $isValid)
    {
        $this->getToken($user);

        $userManager = $this->getContainer()->get(UserManager::class);
        $userMutation = new UserMutation();

        $payload = $userMutation->edit($userManager, $username, $email, $plainPassword, $plainPasswordConfirm);

        $this->assertUserPayload($payload, $isValid);
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
                'newUser',
                'newUser@test.fr',
                'newPassword',
                'newPassword',
                null,
                true,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                'newAdminUser',
                'newAdminUser@test.fr',
                'newPassword',
                'newPassword',
                null,
                true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function providerCreate()
    {
        return [
            [
                'providerCreate0',
                'providerCreate0@exemple.fr',
                'password',
                'password',
                true,
                true,
                null,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                UserFixtures::ADMIN_BASE_USERNAME.'@example.fr',
                'password',
                'password',
                true,
                false,
                ['assertUsernameExist', 'assertEmailExist'],
            ],
            [
                'providerCreate1',
                'providerCreate1@exemple.fr',
                'password',
                'password2',
                true,
                false,
                ['assertInvalidPasswordConfirm'],
            ],
        ];
    }

    /**
     * @dataProvider providerLogin
     *
     * @param string $username
     * @param string $password
     * @param bool $isValid
     * @param array $errorsCallback
     *
     * @throws \Exception
     */
    public function testLogin($username, $password, $isValid, $errorsCallback)
    {
        $container = $this->getContainer();
        $userManager = $container->get(UserManager::class);
        $userMutation = new UserMutation();
        $payload = $userMutation->login($userManager, $username, $password);

        $this->assertTokenPayload($payload, $isValid);
        if (!$isValid) {
            foreach ($errorsCallback as $errorCallback) {
                $this->$errorCallback($payload['errors'], $username, $password);
            }
        } else {
            $this->assertEmpty($payload['errors']);
            $this->assertToken($payload['token'], $username);
        }
    }

    /**
     * @return array
     */
    public function providerLogin()
    {
        return [
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                UserFixtures::ADMIN_BASE_PASSWORD,
                true,
                null,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                UserFixtures::ADMIN_BASE_PASSWORD.'false',
                false,
                ['assertInvalidLogin'],
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                UserFixtures::USER_BASE_PASSWORD,
                true,
                null,
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                UserFixtures::USER_BASE_PASSWORD.'false',
                false,
                ['assertInvalidLogin'],
            ],
            [
                'unknow',
                'unknow',
                false,
                ['assertInvalidLogin'],
            ],
            [
                UserFixtures::USER_DISABLE_USERNAME,
                UserFixtures::USER_DISABLE_PASSWORD,
                false,
                ['assertDisableLogin'],
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
    public function testUploadImgProfil($username, $files, $isValid, $errorsCallback)
    {
        $container = $this->getContainer();
        $tokenStorage = $this->getToken($username);

        $userManager = $container->get(UserManager::class);
        $requestStack = $container->get('request_stack');
        $request = new Request(array(), array(), array(), array(), $files);
        $requestStack->push($request);
        $userMutation = new UserMutation();

        $payload = $userMutation->uploadImgProfil($userManager, $requestStack);
        if (!$isValid) {
            $this->assertNull($payload['file']);
            foreach ($errorsCallback as $errorCallback) {
                $this->$errorCallback($payload['errors']);
            }
        } else {
            $this->assertFile($payload['file']);
            $this->assertEmpty($payload['errors']);

            $user = $tokenStorage->getToken()->getUser();
            $imgProfil = $user->getImgProfil();
            $this->assertNotNull($tokenStorage->getToken()->getUser()->getImgProfil());

            $payload = $userMutation->uploadImgProfil($userManager, $requestStack);
            $this->assertFile($payload['file']);
            $this->assertEmpty($payload['errors']);
            $this->assertNotEquals($imgProfil, $user->getImgProfil());
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
                [$imgFile],
                true,
                null,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                [$phpFile],
                false,
                ['assertInvalidFile'],
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                [$imgFile],
                true,
                null,
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                [$phpFile],
                false,
                ['assertInvalidFile'],
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                [$phpFile, $imgFile],
                false,
                ['assertInvalidFile'],
            ],
        ];
    }

    private function assertUsernameExist($errors, $username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted)
    {
        $isAsserted = false;
        foreach ($errors as $error) {
            if ($error['key'] == 'username') {
                $expected = $this->getTranslator()->trans('username.exist', [], 'validators');
                $this->assertEquals($expected, $error['message']);
                $isAsserted = true;
            }
        }

        $this->assertTrue($isAsserted);
    }

    private function assertEmailExist($errors, $username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted)
    {
        $isAsserted = false;
        foreach ($errors as $error) {
            if ($error['key'] == 'email') {
                $expected = $this->getTranslator()->trans('email.exist', [], 'validators');
                $this->assertEquals($expected, $error['message']);
                $isAsserted = true;
            }
        }

        $this->assertTrue($isAsserted);
    }

    private function assertInvalidPasswordConfirm($errors, $username, $email, $plainPassword, $plainPasswordConfirm, $termsAccepted)
    {
        $isAsserted = false;
        foreach ($errors as $error)
        {
            if ($error['key'] == 'passwordConfirm') {
                $expected = $this->getTranslator()->trans('This value should be equal to {{ compared_value }}.', ['{{ compared_value }}' => '"'.$plainPassword.'"'], 'validators');
                $this->assertEquals($expected, $error['message']);
                $isAsserted = true;
            }
        }

        $this->assertTrue($isAsserted);
    }

    /**
     * @param $errors
     */
    private function assertInvalidLogin($errors)
    {
        $this->assertArrayHasKey(0, $errors);
        $expected = $this->getTranslator()->trans('error.username_or_password', [], 'validators');
        $this->assertEquals($expected, $errors[0]['message']);
    }

    /**
     * @param $errors
     */
    private function assertDisableLogin($errors)
    {
        $this->assertArrayHasKey(0, $errors);
        $expected = $this->getTranslator()->trans('error.user_is_disable', [], 'validators');
        $this->assertEquals($expected, $errors[0]['message']);
    }

    /**
     * @param $errors
     */
    private function assertInvalidFile($errors)
    {
        $this->assertArrayHasKey(0, $errors);
        $expected = $this->getTranslator()->trans('Ce fichier n\'est pas une image valide.', [], 'validators');
        $this->assertEquals($expected, $errors[0]['message']);
        $this->assertEquals('imgProfil.file', $errors[0]['key']);
    }
}