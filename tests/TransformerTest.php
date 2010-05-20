<?php
namespace Transform;

require_once 'lib/Transformer.php';
require_once 'lib/PropertyManipulator.php';
require_once 'tests/Fixtures/Product.php';
require_once 'tests/Fixtures/LineItem.php';

class TransformerTest extends \PHPUnit_Framework_TestCase {
    public function testLazyLoadsManipulator() {
        $transformer = new Transformer();
        $this->assertNotNull($transformer->getManipulator());
    }
    
    public function testDefaultManipulatorIsInstanceOfPropertyManupulator() {
        $transformer = new Transformer();
        $this->assertTrue($transformer->getManipulator() instanceof PropertyManipulator);
    }
    
    public function assertHasEmptyTransformationMap() {
        $transformer = new Transformer();
        $this->assertEquals(array(), $transformer->getTransformationMap());
    }
    
    public function testSetManupulator() {
        $newManipulator = new PropertyManipulator();
        $transformer = new Transformer();
        $transformer->setManipulator($newManipulator);
        $this->assertSame($newManipulator, $transformer->getManipulator());
    }
    
    public function testConstructorSetsManipulator() {
        $newManipulator = new PropertyManipulator();
        $transformer = new Transformer($newManipulator);
        $this->assertSame($newManipulator, $transformer->getManipulator());
    }
    
    public function testSetMap() {
        $map = array('property' => 'attribute');
        $transformer = new Transformer();
        $transformer->setTransformationMap($map);
        $this->assertEquals($map, $transformer->getTransformationMap());
    }
    
    public function testTransform() {
        $map = array(
            'id'    => 'productId',
            'name'    => 'name',
            'price'    => 'unitPrice',
        );
        $product = new Fixtures\Product();
        $product->setId('product-1');
        $product->setName('T-Shirt');
        $product->setPrice(44.99);
        
        $transformer = new Transformer();
        $transformer->setTransformationMap($map);
        
        $lineItem = $transformer->transform($product, 'Transform\Fixtures\LineItem');
        
        $this->assertEquals($product->getId(), $lineItem->getProductId());
        $this->assertEquals($product->getName(), $lineItem->getName());
        $this->assertEquals($product->getPrice(), $lineItem->getUnitPrice());
    }
}