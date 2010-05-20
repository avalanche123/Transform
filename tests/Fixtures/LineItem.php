<?php

namespace Transform\Fixtures;

class LineItem {
    private $productId;
    private $name;
    private $unitPrice;
    
    public function getProductId() {
        return $this->productId;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getUnitPrice() {
        return $this->unitPrice;
    }
}