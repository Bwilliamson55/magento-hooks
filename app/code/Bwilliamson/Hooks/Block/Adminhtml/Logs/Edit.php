<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Logs;

use Bwilliamson\Hooks\Model\History;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

class Edit extends Container
{

    protected Registry $coreRegistry;

    public function __construct(
        Registry $coreRegistry,
        Context  $context,
        array    $data = []
    )
    {
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_blockGroup = 'Bwilliamson_Hooks';
        $this->_controller = 'adminhtml_logs';

        parent::_construct();

        /** @var History $log */
        $log = $this->coreRegistry->registry('bwilliamson_hooks_log');

        if ($log->getId()) {
            $this->buttonList->add('replay', [
                'label' => __('Replay'),
                'onclick' => sprintf("location.href = '%s';", $this->getReplayUrl($log)),
            ], -90);
        }
    }

    protected function getReplayUrl($log): string
    {
        return $this->getUrl('*/*/replay', ['id' => $log->getId()]);
    }

    public function getFormActionUrl(): string
    {
        /** @var History $feed */
        $log = $this->coreRegistry->registry('bwilliamson_hooks_log');
        if ($id = $log->getId()) {
            return $this->getUrl('*/*/save', ['id' => $id]);
        }

        return parent::getFormActionUrl();
    }
}
