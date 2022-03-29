<?php

namespace Checkout\Marketplace;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Files\FilesClient;
use Checkout\Marketplace\Balances\BalancesQuery;
use Checkout\Marketplace\Transfer\CreateTransferRequest;

class MarketplaceClient extends FilesClient
{
    const MARKETPLACE_PATH = "marketplace";
    const INSTRUMENT_PATH = "instruments";
    const FILES_PATH = "files";
    const ENTITIES_PATH = "entities";
    const TRANSFERS_PATH = "transfers";
    const BALANCES_PATH = "balances";

    private $filesApiClient;
    private $transfersApiClient;
    private $balancesApiClient;

    public function __construct(
        ApiClient             $apiClient,
        ApiClient             $filesApiClient,
        ApiClient             $transfersApiClient,
        ApiClient             $balancesApiClient,
        CheckoutConfiguration $configuration
    ) {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
        $this->filesApiClient = $filesApiClient;
        $this->transfersApiClient = $transfersApiClient;
        $this->balancesApiClient = $balancesApiClient;
    }

    /**
     * @param OnboardEntityRequest $entityRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createEntity(OnboardEntityRequest $entityRequest)
    {
        return $this->apiClient->post($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH), $entityRequest, $this->sdkAuthorization());
    }

    /**
     * @param $entityId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getEntity($entityId)
    {
        return $this->apiClient->get($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH, $entityId), $this->sdkAuthorization());
    }

    /**
     * @param $entityId
     * @param OnboardEntityRequest $entityRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function updateEntity($entityId, OnboardEntityRequest $entityRequest)
    {
        return $this->apiClient->put($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH, $entityId), $entityRequest, $this->sdkAuthorization());
    }

    /**
     * @param $entityId
     * @param MarketplacePaymentInstrument $marketplacePaymentInstrument
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createPaymentInstrument($entityId, MarketplacePaymentInstrument $marketplacePaymentInstrument)
    {
        return $this->apiClient->post($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH, $entityId, self::INSTRUMENT_PATH), $marketplacePaymentInstrument, $this->sdkAuthorization());
    }

    /**
     * @param MarketplaceFileRequest $marketplaceFileRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function submitFile(MarketplaceFileRequest $marketplaceFileRequest)
    {
        return $this->filesApiClient->submitFileFilesApi(self::FILES_PATH, $marketplaceFileRequest, $this->sdkAuthorization());
    }

    /**
     * @param CreateTransferRequest $transferRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function initiateTransferOfFunds(CreateTransferRequest $transferRequest)
    {
        return $this->transfersApiClient->post(self::TRANSFERS_PATH, $transferRequest, $this->sdkAuthorization());
    }

    /**
     * @param $entity_id
     * @param BalancesQuery $balancesQuery
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveEntityBalances($entity_id, BalancesQuery $balancesQuery)
    {
        return $this->balancesApiClient->query($this->buildPath(self::BALANCES_PATH, $entity_id), $balancesQuery, $this->sdkAuthorization());
    }
}
