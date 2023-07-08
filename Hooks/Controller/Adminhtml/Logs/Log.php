<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Bwilliamson\Hooks\Api\HooksServiceInterface;
use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\LayoutFactory;

class Log extends AbstractManageHooks
{
    protected LayoutFactory $resultLayoutFactory;

    public function __construct(
        Context       $context,
        HooksServiceInterface              $hooksService,
        protected HooksRepositoryInterface $hooksRepository,
        HookFactory                            $hookFactory,
        LayoutFactory $resultLayoutFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;

        parent::__construct($context, $hooksService, $hooksRepository, $hookFactory);
    }

    public function execute()
    {
        $this->initHook(true);

        return $this->resultLayoutFactory->create();
    }
}
