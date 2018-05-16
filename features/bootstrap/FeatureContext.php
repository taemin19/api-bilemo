<?php

use App\Entity\Product;
use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->kernel->getContainer()->get('doctrine')->getManager());
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
    }

    /**
     * @Given the following products exist:
     */
    public function  theFollowingProductsExist(TableNode $table)
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
     * @Given the following users exist:
     */
    public function  theFollowingUsersExist(TableNode $table)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();

        foreach ($table->getHash() as $productHash) {
            $product = new User();
            $product->setFirstname($productHash['firstname']);
            $product->setLastname($productHash['lastname']);
            $product->setEmail($productHash['email']);

            $em->persist($product);
        }

        $em->flush();
    }
}
