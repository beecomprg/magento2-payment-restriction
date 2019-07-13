<?php

namespace Beecom\PaymentRestriction\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\DataObject;
use Beecom\Core\Block\Adminhtml\Form\Field\PaymentMethods;

class Methods extends AbstractFieldArray
{
    /**
     * Rows cache
     *
     * @var array|null
     */
    private $_arrayRowsCache;

    protected $_shippingGroupRenderer;
    protected $_paymentGroupRenderer;

    protected function _getPaymentGroupRenderer()
    {
        if (!$this->_paymentGroupRenderer) {
            $this->_paymentGroupRenderer = $this->getLayout()->createBlock(
                PaymentMethods::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_paymentGroupRenderer->setClass('pohoda_payment_method_select');
        }
        return $this->_paymentGroupRenderer;
    }

    protected function _getShippingGroupRenderer()
    {
        if (!$this->_shippingGroupRenderer) {
            $this->_shippingGroupRenderer = $this->getLayout()->createBlock(
                ShippingMethods::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_shippingGroupRenderer->setClass('pohoda_shipping_method_select');
        }
        return $this->_shippingGroupRenderer;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('method',
            ['label' => __('Payment'), 'renderer' => $this->_getPaymentGroupRenderer()]
        );
        $this->addColumn('code', ['label' => __('Shipping'), 'renderer' => $this->_getShippingGroupRenderer()]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Obtain existing data from form element
     *
     * Each row will be instance of \Magento\Framework\DataObject
     *
     * @return array
     */
    public function getArrayRows()
    {
        if (null !== $this->_arrayRowsCache) {
            return $this->_arrayRowsCache;
        }
        $result = [];
        /** @var AbstractElement */
        $element = $this->getElement();
        if ($element->getValue() && is_array($element->getValue())) {
            foreach ($element->getValue() as $rowId => $row) {
                $rowColumnValues = [];
                foreach ($row as $key => $value) {
                    $row[$key] = $value;
                    $rowColumnValues[$this->_getCellInputElementId($rowId, $key)] = $row[$key];
                }
                $row['_id'] = $rowId;
                $row['column_values'] = $rowColumnValues;
                $result[$rowId] = new DataObject($row);
                $this->_prepareArrayRow($result[$rowId]);
            }
        }
        $this->_arrayRowsCache = $result;
        return $this->_arrayRowsCache;
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getPaymentGroupRenderer()->calcOptionHash($row->getData('method'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->_getShippingGroupRenderer()->calcOptionHash($row->getData('code'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
