<?php
namespace Transform;

/**
 * // define transformation map:
 * $map = array(
 *     'id' => 'productId',
 *     'price' => 'total',
 *     'name'  => 'productName',
 * );
 * // create new source object:
 * $product = new Product();
 * $product->id = 1;
 * $product->name = 'T-Shirt';
 * $product->price = 49.99;
 * // create transformer with the transformation map:
 * $transformer = new Transformer();
 * $transformer->setMap($map);
 * // mutate product into LineItem instance:
 * $lineItem = $transformer->transform($product, 'LineItem');
 * $lineItem->productId;
 * $lineItem->productName;
 * $lineItem->total;
 */
class Transformer {
    /**
     * @var array $transformationMap
     */
    private $transformationMap = array();
    /**
     * @var PropertyManipulator $manipulator
     */
    private $manipulator;
    /**
     * @param PropertyManipulator $manipulator
     */
    public function __construct(PropertyManipulator $manipulator = null) {
        if (isset($manipulator)) {
            $this->setManipulator($manipulator);
        }
    }
    /**
     * @param array $transformationMap
     */
    public function setTransformationMap(array $transformationMap) {
        $this->transformationMap = $transformationMap;
    }
    /**
     * @return array $transformationMap
     */
    public function getTransformationMap() {
        return $this->transformationMap;
    }
    /**
     * @param PropertyManipulator $manipulator
     */
    public function setManipulator(PropertyManipulator $manipulator) {
        $this->manipulator = $manipulator;
    }
    
    /**
     * @return PropertyManipulator $manipulator
     */
    public function getManipulator() {
        if ( ! isset ($this->manipulator)) {
            $this->manipulator = new PropertyManipulator();
        }
        return $this->manipulator;
    }
    
    /**
     * @param mixed $in
     * @param mixed $out
     * @return mixed $in
     */
    public function transform($in, $out) {
        $data = $this->getManipulator()->extract($in);
        $result = array();
        foreach ($data as $key => $value) {
            if (isset ($this->transformationMap[$key])) {
                $result[$this->transformationMap[$key]] = $value;
            }
        }
        return $this->getManipulator()->inject($out, $result);
    }
    
}