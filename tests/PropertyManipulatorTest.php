<?php
namespace Transform;

use ReflectionProperty;

require_once 'lib/PropertyManipulator.php';
require_once 'tests/Fixtures/Product.php';
require_once 'tests/Fixtures/LineItem.php';

class PropertyManipulatorTest extends \PHPUnit_Framework_TestCase {
	
	public function testDefaultFilter() {
		$manipulator = new PropertyManipulator();
		$this->assertEquals(ReflectionProperty::IS_PUBLIC
							| ReflectionProperty::IS_PROTECTED
							| ReflectionProperty::IS_PRIVATE,
			$manipulator->getPropertyFilter());
	}
	
	public function testConstructorSetsFilter() {
		$filter =	ReflectionProperty::IS_STATIC;
		$manipulator = new PropertyManipulator($filter);
		$this->assertEquals($filter, $manipulator->getPropertyFilter());
	}
	
	public function testExtract() {
		$product = new Fixtures\Product();
		$product->setId('simple-product');
		$product->setName('T-Shirt');
		$product->setPrice(49.99);
		$manipulator = new PropertyManipulator();
		$this->assertEquals(array(
			'id' => 'simple-product',
			'name' => 'T-Shirt',
			'price' => 49.99,
		), $manipulator->extract($product));
	}
	
	public function testInject() {
		$product = new Fixtures\Product();
		$manipulator = new PropertyManipulator();
		$manipulator->inject($product, array(
			'id' => 'simple-product',
			'name' => 'T-Shirt',
			'price' => 49.99,
		));
		$this->assertEquals('simple-product', $product->getId());
		$this->assertEquals('T-Shirt', $product->getName());
		$this->assertEquals(49.99, $product->getPrice());
	}
	
}