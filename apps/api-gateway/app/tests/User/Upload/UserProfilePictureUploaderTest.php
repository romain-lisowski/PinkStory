<?php

declare(strict_types=1);

namespace App\Test\User\Upload;

use App\User\Entity\User;
use App\User\Upload\UserProfilePictureUploader;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @internal
 * @coversNothing
 */
final class UserProfilePictureUploaderTest extends KernelTestCase
{
    private Prophet $prophet;
    private UserProfilePictureUploader $uploader;
    private $params;
    private User $user;

    public function setUp(): void
    {
        self::bootKernel();

        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
            ->setProfilePictureDefined(true)
        ;

        $this->params = $this->prophet->prophesize(ParameterBagInterface::class);

        $this->uploader = new UserProfilePictureUploader($this->params->reveal());
        $this->uploader->setUser($this->user);

        $filesystem = new Filesystem();
        $filesystem->touch(sys_get_temp_dir().'/test.jpg');
    }

    public function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove([
            sys_get_temp_dir().'/test.jpg',
            self::$container->getParameter('project_file_manager_dir').self::$container->getParameter('project_file_manager_image_dir').self::$container->getParameter('project_file_manager_image_user_dir').'/'.$this->user->getProfilePicturePath(),
        ]);

        $this->prophet->checkPredictions();
    }

    public function testUploadSuccess(): void
    {
        $this->params->get('project_file_manager_dir')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('project_file_manager_dir'));
        $this->params->get('project_file_manager_image_dir')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('project_file_manager_image_dir'));
        $this->params->get('project_file_manager_image_user_dir')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('project_file_manager_image_user_dir'));

        $fileUploaded = $this->uploader->upload(new UploadedFile(sys_get_temp_dir().'/test.jpg', 'test.jpg', 'image/jpeg', null, true));

        $filesystem = new Filesystem();
        $fileExists = $filesystem->exists(self::$container->getParameter('project_file_manager_dir').self::$container->getParameter('project_file_manager_image_dir').self::$container->getParameter('project_file_manager_image_user_dir').'/'.$this->user->getProfilePicturePath());

        $this->assertTrue($fileUploaded);
        $this->assertTrue($fileExists);
    }

    public function testRemoveSuccess(): void
    {
        $this->params->get('project_file_manager_dir')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('project_file_manager_dir'));
        $this->params->get('project_file_manager_image_dir')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('project_file_manager_image_dir'));
        $this->params->get('project_file_manager_image_user_dir')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('project_file_manager_image_user_dir'));

        $this->uploader->upload(new UploadedFile(sys_get_temp_dir().'/test.jpg', 'test.jpg', 'image/jpeg', null, true));

        $filesystem = new Filesystem();
        $fileExistsBefore = $filesystem->exists(self::$container->getParameter('project_file_manager_dir').self::$container->getParameter('project_file_manager_image_dir').self::$container->getParameter('project_file_manager_image_user_dir').'/'.$this->user->getProfilePicturePath());

        $this->uploader->remove();

        $fileExistsAfter = $filesystem->exists(self::$container->getParameter('project_file_manager_dir').self::$container->getParameter('project_file_manager_image_dir').self::$container->getParameter('project_file_manager_image_user_dir').'/'.$this->user->getProfilePicturePath());

        $this->assertTrue($fileExistsBefore);
        $this->assertFalse($fileExistsAfter);
    }

    public function testRemoveFailFileNotFound(): void
    {
        $this->params->get('project_file_manager_dir')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('project_file_manager_dir'));
        $this->params->get('project_file_manager_image_dir')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('project_file_manager_image_dir'));
        $this->params->get('project_file_manager_image_user_dir')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('project_file_manager_image_user_dir'));

        $this->expectException(FileNotFoundException::class);

        $this->uploader->remove();
    }
}
