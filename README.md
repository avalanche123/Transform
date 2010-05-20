# Object Transformation Library

In a need to map object data from one system to another,
I was always using temporary, throw-away solutions, which
turned out to be pretty ugly.

PHP 5.3 Reflection API changes the game...

# Usage

Assume you have a product and a line item. Line item gets
added to the order and essentially represents the product,
with some data frozen (price) for historical reasons - you
don't want product price updates to change customer order
history and financial information.

You could write a manual getData() setData($data) type of
transform, or you could use this Transform library.

Assume you have two classes.
Product class:

    <?php
    class Product {
        private $id;
        private $name;
        private $price;
        public function setId($id) {
            $this->id = $id;
        }
        public function setName($name) {
            $this->name = $name;
        }
        public function setPrice($price) {
            $this->price = $price;
        }
    }

LineItem class:

    <?php
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

You need to define the properties map, that would guide the transformation, in a
array('source property name' => 'target property', ...) name manner:

    <?php
    $map = array(
        'id'    => 'productId',
        'name'    => 'name',
        'price'    => 'unitPrice',
    );

Instantiate your source class:

    $product = new Product();
    $product->setId('unique-id');
    $product->setName('T-Shirt');
    $product->setPrice(49.99);

Or just use array of data (useful when need to convert Web Services result):

    $product = array(
        'id' => 'unique-id',
        'name' => 'T-Shirt',
        'price' => 49.99,
    );

And tranform:

    $transformer = new Transformer();
    $transformer->setTransformationMap($map);
    
    $lineItem = $transformer->transform($product, 'LineItem');
    // or
    //...
    $lineItem = new LineItem();
    $transformer->transform($product, $lineItem);
    
    $lineItem->getProductId();
    $lineItem->getName();
    $lineItem->getUnitPrice();

Another useful example is when you have some input data from
one of your data sources, which already has the correct mappings,
then you could use the PropertyManipulator class:

    <?php
    $data = array(
        'productId' => 'unique-id',
        'name' => 'T-Shirt',
        'unitPrice' => 49.99,
    );
    
    $manipulator = new PropertyManipulator();
    $lineItem = $manipulator->inject('LineItem', $data);
    // or
    //...
    $lineItem = new LineItem();
    $manipulator->inject($lineItem, $data);

The other manipulator method, that can come in handy is extract():

    <?php
    //...
    echo json_encode($manipulator->extract($lineItem));
    // outputs: { productId: "unique-id", name: "T-Shirt", unitPrice: 49.99 }

Happy coding!