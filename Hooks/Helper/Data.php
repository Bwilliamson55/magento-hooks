<?php

namespace Bwilliamson\Hooks\Helper;

use Bwilliamson\Hooks\Block\Adminhtml\LiquidFilters;
use Bwilliamson\Hooks\Model\Config\Source\Authentication;
use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Bwilliamson\Hooks\Model\Config\Source\Status;
use Bwilliamson\Hooks\Model\HistoryFactory;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Liquid\Template;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Zend_Http_Response;

class Data extends AbstractHelper
{
    protected LiquidFilters $liquidFilters;
    private LoggerInterface $logger;
    private Json $json;

    public function __construct(
        Context                               $context,
        private StoreManagerInterface         $storeManager,
        protected UrlInterface                $backendUrl,
        protected TransportBuilder            $transportBuilder,
        protected CurlFactory                 $curlFactory,
        LiquidFilters                         $liquidFilters,
        protected HookFactory                 $hookFactory,
        protected HistoryFactory              $historyFactory,
        protected CustomerRepositoryInterface $customer,
        LoggerInterface                       $logger,
        Json                                  $json
    ) {
        $this->liquidFilters = $liquidFilters;
        $this->logger = $logger;
        $this->json = $json;
        parent::__construct($context);
    }

    public function isEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT): bool
    {
        return $this->scopeConfig->isSetFlag('bwilliamson_hooks/general/enabled', $scope);
    }

    public function getItemStore($item): int
    {
        if (method_exists($item, 'getData')) {
            return $item->getData('store_id') ?: $this->storeManager->getStore()->getId();
        }

        return $this->storeManager->getStore()->getId();
    }

    public function send($item, $hookType): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        $hookCollection = $this->getFilteredHookCollection($item, $hookType);

        foreach ($hookCollection as $hook) {
            if ($hook->getHookType() === HookType::ORDER) {
                $statusItem = $item->getStatus();
                $orderStatus = explode(',', $hook->getOrderStatus());
                if (!in_array($statusItem, $orderStatus, true)) {
                    continue;
                }
            }
            $this->saveHistory($hook, $item);
        }
    }

    public function sendHttpRequestFromHook($hook, $item = false, $log = false): array
    {
        $url = $log ? $log->getPayloadUrl() : $this->generateLiquidTemplate($item, $hook->getPayloadUrl());
        $authentication = $hook->getAuthentication();
        $method = $hook->getMethod();
        $username = $hook->getUsername();
        $password = $hook->getPassword();
        if ($authentication === Authentication::BASIC) {
            $authentication = $this->getBasicAuthHeader($username, $password);
        }

        $body = $log ? $log->getBody() : $this->generateLiquidTemplate($item, $hook->getBody());
        $headers = $hook->getHeaders();
        $contentType = $hook->getContentType();

        return $this->sendHttpRequest($headers, $authentication, $contentType, $url, $body, $method);
    }

    public function generateLiquidTemplate($item, $templateHtml): string
    {
        try {
            $template = new Template();
            $filtersMethods = $this->liquidFilters->getFiltersMethods();

            $template->registerFilter($this->liquidFilters);
            $template->parse($templateHtml, $filtersMethods);

            if ($item instanceof Product) {
                $item->setStockItem(null);
            }

            return $template->render(['item' => $item]);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return '';
    }

    public function sendHttpRequest($headers, $authentication, $contentType, $url, $body, $method): array
    {
        if (!$method) {
            $method = 'GET';
        }
        if ($headers && !is_array($headers)) {
            $headers = $this->json->unserialize($headers);
        }
        $headersConfig = [];

        foreach ($headers as $header) {
            $key = $header['name'];
            $value = $header['value'];
            $headersConfig[] = trim($key) . ': ' . trim($value);
        }

        if ($authentication) {
            $headersConfig[] = 'Authorization: ' . $authentication;
        }

        if ($contentType) {
            $headersConfig[] = 'Content-Type: ' . $contentType;
        }

        $curl = $this->curlFactory->create();
        $curl->write($method, $url, '1.1', $headersConfig, $body);
        $this->logger->critical(implode(',', $headersConfig));
        $this->logger->critical($this->json->serialize($body));
        $result = ['success' => false];
        try {
            $resultCurl = $curl->read();

            $result['response'] = $resultCurl;
            if (!empty($resultCurl)) {
                $result['status'] = Zend_Http_Response::extractCode($resultCurl);
                if (isset($result['status']) && in_array($result['status'], [200, 201])) {
                    $result['success'] = true;
                } else {
                    $result['message'] = __('Cannot connect to server. Please try again later.');
                }
            } else {
                $result['message'] = __('Cannot connect to server. Please try again later.');
            }
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        }
        $curl->close();

        return $result;
    }

    public function getBasicAuthHeader($username, $password): string
    {
        return 'Basic ' . base64_encode("$username:$password");
    }

    /**
     * @param mixed $hook
     * @param $item
     * @return void
     */
    public function saveHistory(mixed $hook, $item): void
    {
        $history = $this->historyFactory->create();
        $data = [
            'hook_id' => $hook->getId(),
            'hook_name' => $hook->getName(),
            'store_ids' => $hook->getStoreIds(),
            'hook_type' => $hook->getHookType(),
            'priority' => $hook->getPriority(),
            'payload_url' => $this->generateLiquidTemplate($item, $hook->getPayloadUrl()),
            'body' => $this->generateLiquidTemplate($item, $hook->getBody())
        ];
        $history->addData($data);
        try {
            $result = $this->sendHttpRequestFromHook($hook, $item);
            $history->setResponse($result['response'] ?? '');
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        if ($result['success'] === true) {
            $history->setStatus(Status::SUCCESS);
        } else {
            $history->setStatus(Status::ERROR)
                ->setMessage($result['message']);
        }
    }

    private function getFilteredHookCollection($item, $hookType): AbstractDb|AbstractCollection
    {
        //TODO: refactor for deprecation of getCollection
        return $this->hookFactory->create()->getCollection()
            ->addFieldToFilter('hook_type', $hookType)
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('store_ids', [
                ['finset' => Store::DEFAULT_STORE_ID],
                ['finset' => $this->getItemStore($item)]
            ])
            ->setOrder('priority', 'ASC');
    }
}
