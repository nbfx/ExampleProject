<?php

/* All inner names and paths have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Tests\Functional\Admin;

use ChangedDirName\Base\Tests\Controller\BaseController;
use ChangedDirName\Base\Tests\Fixtures\User\ORM\UserData;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminDefaultControllerTest
 *
 * @package ChangedDirName\Bundle\MainBundle\Tests\Functional\Api
 */
class AdminDefaultControllerTest extends BaseController
{
    /**
     * @var string
     */
    protected $setSettingsUrl = '/control-panel/user-profile/cp-settings';

    /**
     * @var string
     */
    protected $getSettingsUrl = '/control-panel/user-profile/get-authorized-admin-data';

    /**
     * Check structure of Settings JSON
     *
     * @runInSeparateProcess
     */
    public function testSetSettingsAction()
    {
        $dataToWrite = (object) [
            'isFixed' => true,
            'isCollapsed' => true,
            'isBoxed' => true,
            'isRTL' => true,
            'horizontal' => true,
            'isFloat' => true,
            'asideHover' => true,
            'theme' => "/control-panel/build/css/theme-d.css",
        ];
        $client = $this->createAdminAuthorizedClient(UserData::TEST_USER_SETTINGS_USERNAME, 'administrator');
        $client->request(
            'POST',
            $this->setSettingsUrl,
            [$dataToWrite],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );
        $response = $client->getResponse();

        $this->assertEquals(
            Response::HTTP_ACCEPTED,
            $response->getStatusCode()
        );
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $user = $client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('ChangedDirNameUserBundle:User')
            ->findOneBy(['username' => UserData::TEST_USER_SETTINGS_USERNAME]);
        $userSettings = $user->getSettings();

        $this->assertEquals($dataToWrite, (object) $userSettings[0]);
    }

    /**
     * Check the getAuthorizedAdminData method
     *
     * @runInSeparateProcess
     */
    public function testGetAuthorizedAdminData()
    {
        $client = $this->createAdminAuthorizedClient(UserData::TEST_USER_SETTINGS_USERNAME, 'administrator');
        $client->request(
            'GET',
            $this->getSettingsUrl,
            [],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );
        $response = $client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $expectedContent = (object) [
            'username' => UserData::TEST_USER_SETTINGS_USERNAME,
            'firstName' => 'someTestFirstName',
            'lastName' => 'someTestLastName',
            'email' => 'settings@user-email.com',
            'settings' => [],
        ];

        $this->assertEquals(Response::HTTP_OK, $statusCode);
        $this->assertObjectsStructuresEquals($expectedContent, json_decode($content));
    }
}
