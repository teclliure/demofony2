<?php

/**
 * Demofony2 app.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 14/11/14
 * Time: 12:39
 */
namespace Demofony2\AppBundle\DataFixtures\ORM;

use Nelmio\Alice\Fixtures;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class FixturesLoader implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $files = $finder->files()->name('*.'.$this->getEnvironment().'.yml')->in(__DIR__.'/Alice/');

        foreach ($files as $file) {
            Fixtures::load($file->getRealPath(), $manager, array('providers' => array($this)));
        }

        $manager->flush();
    }
    /**
     * @param $file
     *
     * @return UploadedFile
     */
    public function uploadedFile($file)
    {
        // copy file to temp, so original won't be deleted by uploadedfile->move
        $file = __DIR__.$file;
        $temp = __DIR__.'/uploaded/'.uniqid();
        copy($file, $temp);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        return new UploadedFile($temp, basename($temp), finfo_file($finfo, $temp), filesize($temp), null, true);
    }

    private function getEnvironment()
    {
        return  $this->container->get('kernel')->getEnvironment();
    }
}
