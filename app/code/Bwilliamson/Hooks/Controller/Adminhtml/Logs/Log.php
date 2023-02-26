<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\LayoutFactory;

class Log extends AbstractManageHooks
{
    protected LayoutFactory $resultLayoutFactory;

    public function __construct(
        HookFactory   $hookFactory,
        Registry      $coreRegistry,
        Context       $context,
        LayoutFactory $resultLayoutFactory
    )
    {
        $this->resultLayoutFactory = $resultLayoutFactory;

        parent::__construct($hookFactory, $coreRegistry, $context);
    }

    public function execute()
    {
        $this->initHook(true);

        return $this->resultLayoutFactory->create();
    }
}
