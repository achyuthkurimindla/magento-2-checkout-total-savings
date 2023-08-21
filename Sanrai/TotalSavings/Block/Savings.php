<?php
namespace Sanrai\TotalSavings\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Checkout\Model\Session;

class Savings extends \Magento\Framework\View\Element\Template
{
    protected $_priceHelper;
    protected $_checkoutSession;

    public function __construct(
        Context $context,
        PriceHelper $priceHelper,
        Session $checkoutSession,
        array $data = []
    ) {
        $this->_priceHelper = $priceHelper;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    public function getTotalSavings()
    {
        $totalSavings = 0;
        // $specialSavings=0;
        
        $quote = $this->_checkoutSession->getQuote();
        foreach ($quote->getAllVisibleItems() as $item) {
            
            $itemPrice = $item->getPrice() * $item->getQty();

            $product = $item->getProduct();
            $specialDiscount = $product->getPrice() - $product->getFinalPrice() ;
            $totalSavings += $specialDiscount * $item->getQty();
            // $couponDiscount = $item->getDiscountAmount();
            // $totalSavings = $specialSavings+ $couponDiscount;
        }
        if ($quote->getCouponCode()) {
            $totalSavings += $quote->getBaseSubtotal() - $quote->getBaseSubtotalWithDiscount();
        }
            return $this->_priceHelper->currency($totalSavings, true, false);
    }
    
}
  
?>
