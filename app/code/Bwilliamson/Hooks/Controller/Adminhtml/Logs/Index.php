<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::logs';

    private PageFactory $pageFactory;

    public function __construct(
        Context     $context,
        PageFactory $pageFactory
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu('Bwilliamson_Hooks::logs');
        $page->getConfig()->getTitle()->prepend(__('Hook Logs'));

        return $page;
    }
}
