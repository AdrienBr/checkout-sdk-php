<?php

namespace Checkout;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class AbstractCheckoutSdkBuilder
{

    protected $environment;
    protected $httpClientBuilder;
    protected $logger;

    public function __construct()
    {
        $this->environment = Environment::sandbox();
        $this->httpClientBuilder = new DefaultHttpClientBuilder();
        $this->setDefaultLogger();
    }

    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function setHttpClientBuilder(HttpClientBuilderInterface $httpClientBuilder)
    {
        $this->httpClientBuilder = $httpClientBuilder;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function getCheckoutConfiguration()
    {
        return new CheckoutConfiguration(
            $this->getSdkCredentials(),
            $this->environment,
            $this->httpClientBuilder,
            $this->logger
        );
    }

    private function setDefaultLogger()
    {
        $this->logger = new Logger(CheckoutUtils::PROJECT_NAME);
        $this->logger->pushHandler(new StreamHandler("php://stderr"));
    }

    abstract protected function getSdkCredentials();

    /**
     * @return mixed
     */
    abstract protected function build();
}
