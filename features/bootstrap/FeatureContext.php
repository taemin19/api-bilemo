<?php

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behatch\Context\RestContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var JWTManager
     */
    private $jwtManager;

    /**
     * @var RestContext
     */
    private $restContext;

    private $currentClient;

    public function __construct(KernelInterface $kernel, JWTManager $jwtManager)
    {
        $this->kernel = $kernel;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->kernel->getContainer()->get('doctrine')->getManager(), ['client']);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $purger = new ORMPurger($this->kernel->getContainer()->get('doctrine')->getManager(), ['users']);
        $purger->purge();
    }

    /**
     * @param BeforeScenarioScope $scope
     * @BeforeScenario @loginAsClient1
     */
    public function login(BeforeScenarioScope $scope)
    {
        $client = new Client();
        $client->setName('client1');
        $client->setUsername('client1');
        $client->setPassword('client1');

        $this->currentClient = $client;

        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($client);
        $em->flush();

        $token = $this->jwtManager->create($client);
        $this->restContext = $scope->getEnvironment()->getContext(RestContext::class);
        $this->restContext->iAddHeaderEqualTo('Authorization', "Bearer $token");
    }

    /**
     * @param TableNode $table
     * @Given the following products exist:
     */
    public function theFollowingProductsExist(TableNode $table)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();

        foreach ($table->getHash() as $productHash) {
            $product = new Product();
            $product->setModel($productHash['model']);
            $product->setBrand($productHash['brand']);
            $product->setDescription($productHash['description']);
            $product->setStorage($productHash['storage']);
            $product->setColor($productHash['color']);
            $product->setPrice($productHash['price']);

            $em->persist($product);
        }

        $em->flush();
    }

    /**
     * @param TableNode $table
     * @Given the following users exist for client1:
     */
    public function theFollowingUsersExistForClient1(TableNode $table)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();

        foreach ($table->getHash() as $userHash) {
            $user = new User();
            $user->setFirstname($userHash['firstname']);
            $user->setLastname($userHash['lastname']);
            $user->setEmail($userHash['email']);
            $user->setClient($this->currentClient);

            $em->persist($user);
        }

        $em->flush();
    }

    /**
     * @param TableNode $table
     * @Given the following users exist for client2:
     */
    public function theFollowingUsersExistForClient2(TableNode $table)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();

        $client = new Client();
        $client->setName('client2');
        $client->setUsername('client2');
        $client->setPassword('client2');

        $em->persist($client);

        foreach ($table->getHash() as $userHash) {
            $user = new User();
            $user->setFirstname($userHash['firstname']);
            $user->setLastname($userHash['lastname']);
            $user->setEmail($userHash['email']);
            $user->setClient($client);

            $em->persist($user);
        }

        $em->flush();
    }

    /**
     * @param TableNode $table
     * @Given the following clients exist:
     */
    public function theFollowingClientsExist(TableNode $table)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();

        foreach ($table->getHash() as $clientHash) {
            $client = new Client();
            $client->setName($clientHash['name']);
            $client->setUsername($clientHash['username']);
            $client->setPassword($clientHash['password']);

            $em->persist($client);
        }

        $em->flush();
    }
}
