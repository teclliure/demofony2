<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class AbstractDemofony2ControllerTest extends WebTestCase
{
    const API_VERSION = '/api/v1';

    /**
     *
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected static $kernel;

    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected $application;

    /**
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Client $client
     */
    protected $client;

    /**
     *
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     *
     * @var Symfony\Component\HttpFoundation\Response;
     */
    protected $response;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    abstract public function getDemofony2Url();

    abstract public function getMethod();

    abstract public function getValidParameters();

    abstract public function getRequiredParameters();

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        //        $this->initialize(); // To use Test classes inside another test
    }

    public function setUp($user = null, $password = null)
    {
        parent::setUp();
        $this->initialize($user, $password);
        $this->logout();

        $this->regenerateDatabase();
    }

    protected function initialize($user, $password)
    {
        $this->client = $this->createClient(array(), array(
                'PHP_AUTH_USER' => $user,
                'PHP_AUTH_PW'   => $password,
            ));
        $this->crawler = null;
        $this->response = null;
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
        $this->application = new Application(static::$kernel);
        $this->container = $this->application->getKernel()->getContainer();
    }

    protected function logout()
    {
        $this->request(array(), '/ca/logout', 'GET');
    }

    public function request(array $parameters = array(), $url = false, $method = false, $files = array())
    {
        if (!$url) {
            $url = $this->getDemofony2Url();
        }

        if (!$method) {
            $method = $this->getMethod();
        }
        if (!$files) {
            $files = $this->getFiles();
        }
//        echo "$url ($method) params: " . implode(',', $parameters) . "\n";
        $this->crawler = $this->client->request($method, $url, $parameters, $files);

        return $this->getResponse();
    }

    public function getResponse()
    {
        try {
            $this->response = $this->client->getResponse();
            $responseJson = json_decode($this->response->getContent(), true);
//            echo json_encode($responseJson);
            return $responseJson;
        } catch (Exception $ex) {
            echo "Error decoding: ".$this->response;

            return array();
        }
    }

    public function getHtmlResponse()
    {
        return $this->client->getResponse()->getContent();
    }

    public function getResponseTag($tagName)
    {
        $htmlResponse = $this->getHtmlResponse();
        preg_match("#<$tagName>(.*)</$tagName>#", $htmlResponse, $matches);

        return $matches ? $matches[1] : null;
    }

    public function getResponseMeta($name, $attributeName = 'name')
    {
        $htmlResponse = $this->getHtmlResponse();
        $regEx = "#<meta.*$attributeName=\"$name\" content=\"(.*)\"[^>]*>#";
        preg_match($regEx, $htmlResponse, $matches);

        return $matches ? $matches[1] : null;
    }

    public function assertStatusResponse($expectedStatusCode, $message = '')
    {
        if (!$message) {
            $message = json_encode($this->getResponse());
        }
        $this->assertEquals($expectedStatusCode, $this->response->getStatusCode(), $message);
    }

    public function assertArrayHasKeyAndEquals($key, $array, $expectedValue, $message = '')
    {
        if (!$message) {
            $message = $key."\n".json_encode($array);
        }
        $this->assertArrayHasKey($key, $array, $message);
        $this->assertEquals($expectedValue, $array[$key], $message);
    }

    public function assertArrayHasKeyAndContainsString($key, $array, $expectedValue, $message = '')
    {
        $this->assertArrayHasKey($key, $array, $message);
        if (!$message) {
            $message = "Does not contains string $expectedValue ".$array[$key];
        }
        $this->assertTrue(false !== strstr($array[$key], $expectedValue), $message);
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getDatabaseManager()
    {
        return $this->em;
    }

    protected function doTestMissingParameter($parameterName)
    {
        $params = $this->getValidParameters();
        unset($params[$parameterName]);

        $this->doTheWrongTest(
                $params, 400, ErrorCodes::GLOBAL_MISSING_PARAMETER, "parameter \"$parameterName\" is empty", "Testing required parameter $parameterName"
        );
    }

    private function doTheWrongTest($params, $expectedStatusCode, $expectedErrorCode, $expectedErrorMessage, $message = '')
    {
        $response = $this->request($params);
        $this->assertStatusResponse($expectedStatusCode, $message);
        $this->assertArrayHasKeyAndEquals('success', $response, false, $message);
        $this->assertArrayHasKey('error', $response, $message);
        $this->assertArrayHasKeyAndEquals('code', $response['error'], $expectedErrorCode, $message);
        $this->assertArrayHasKeyAndContainsString('message', $response['error'], $expectedErrorMessage, $message);
    }

    private function doTheRigthTest($params)
    {
        $response = $this->request($params);
        $this->assertTrue($this->response->isSuccessful(), json_encode($params));
    }

    public function xtestRequiredParameters()
    {
        foreach ($this->getRequiredParameters() as $parameter) {
            $this->doTestMissingParameter($parameter);
        }
    }

    public function xtestOptionalParameters()
    {
        $optionalParameters = array_diff_key($this->getValidParameters(), array_flip($this->getRequiredParameters()));

        $mandatoryParameters = array_intersect_key($this->getValidParameters(), array_flip($this->getRequiredParameters()));

        $subsets = $this->generateSubsets($optionalParameters);

        foreach ($subsets as $paramsWithoutRequiredParams) {
            $p = array_merge($paramsWithoutRequiredParams, $mandatoryParameters);
            $this->doTheRigthTest($p);
        }
    }

    private function generateSubsets($array, $minLength = 1)
    {
        $keys = array_keys($array);
        $count = count($array);
        $members = pow(2, $count);
        $return = array();
        for ($i = 0; $i < $members; $i++) {
            $b = sprintf("%0".$count."b", $i);
            $out = array();
            for ($j = 0; $j < $count; $j++) {
                if ($b{$j} == '1') {
                    $k = $keys[$j];
                    $out[$k] = $array[$k];
                }
            }
            if (count($out) >= $minLength) {
                $return[] = $out;
            }
        }

        return $return;
    }

    private function dropDatabase()
    {
        $command = new DropDatabaseDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ));
        $command->run($input, new NullOutput());

        // we have to close the connection after dropping the database so we don't get "No database selected" error
        $connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();
        if ($connection->isConnected()) {
            $connection->close();
        }
    }

    private function createDatabase()
    {
        // create the database
        $command = new CreateDatabaseDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:create',
        ));
        $command->run($input, new NullOutput());

        // create schema
        $command = new CreateSchemaDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:create',
        ));
        $command->run($input, new NullOutput());
    }

    private function myLoadFixtures()
    {
        // get the Entity Manager
        $em = $this->getDatabaseManager();

        // load fixtures
        $client = static::createClient();
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'Demofony2\AppBundle\DataFixtures\ORM\FixturesLoader',
        );

        $this->loadFixtures($classes);
    }

    protected function regenerateDatabase()
    {
        $this->dropDatabase();
        $this->createDatabase();
        $this->myLoadFixtures();
    }

    protected function getFixture($fixtureName)
    {
        return $this->fixtures->getReference($fixtureName);
    }

    public function getFiles()
    {
        return array();
    }

    /**
     *
     * @return \Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector
     */
    public function getMailCollector()
    {
        $profile = $this->client->getProfile();
        if (!$profile) {
            throw new \Exception('Enable the profiler with $this->enableProfiler()');
        }

        return $profile->getCollector('swiftmailer');
    }

    public function enableProfiler()
    {
        $this->client->enableProfiler();
    }

    public function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);
    }
}
