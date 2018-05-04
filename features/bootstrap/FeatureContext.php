<?php

use Behat\Behat\Context\Context;
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
}
