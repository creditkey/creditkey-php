# Credit Key PHP SDK

## Table of Contents

- [Support](#support)
- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Models](#models)
    - [Address](#address)
    - [CartItem](#cartitem)
    - [Charges](#charges)
    - [Order](#order)
- [Exceptions](#exceptions)
    - [NotFoundException](#notfoundexception)
    - [OperationErrorException](#operationerrorexception)
- [Authentication](#authentication)
    - [authenticate](#authenticate)
- [Checkout Methods](#checkout-methods)
    - [isDisplayedInCheckout](#isdisplayedincheckout)
    - [beginCheckout](#begincheckout)
    - [completeCheckout](#completecheckout)
- [Order Management Methods](#order-management-methods)
    - [confirm](#confirm)
    - [update](#update)
    - [find](#find)
    - [findByMerchantId](#findbymerchantid)
    - [cancel](#cancel)
    - [refund](#refund)

## Support
----------

You should have been put in contact with an Implementation Support Engineer at Credit Key.  You can directly contact your Support Engineer with any questions or to receive implementation assistance.

## Requirements
---------------

The Credit Key PHP SDK requires PHP 5.6 or higher, with the php_curl extension loaded. Use of [Composer](https://getcomposer.org) is optional.

## Getting Started
------------------

## Models
---------

Most SDK methods either accept one or more of these models as an argument, or return one as a result.  All models work the same in that field values can only be set by the constructor, and can be accessed by corresponding ```get``` methods.  All models documented here are under the ```\CreditKey\Models``` namespace.

### Address

This object is used to represent either a billing or shipping address.

```
$billingAddress = new \CreditKey\Models\Address($firstName, $lastName, $email, $address1, $address2, $city, $state, $zip, $phoneNumber);
```

```
$firstName = $billingAddress->getFirstName();
$lastName = $billingAddress->getLastName();
$email = $billingAddress->getEmail();
$address1 = $billingAddress->getAddress1();
$address2 = $billingAddress->getAddress2();
$city = $billingAddress->getCity();
$state = $billingAddress->getState();
$zip = $billingAddress->getZip();
$phoneNumber = $billingAddress->getPhoneNumber();
```

### CartItem

This object represents an product in the user's shopping cart. ```sku```, ```size```, and ```color``` are all optional and can be ```null```.  The ```merchantProductId``` is the key referring to the product on the merchant system.

```
$cartItem = new \CreditKey\Models\CartItem($merchantProductId, $name, $price, $sku, $quantity, $size, $color);
```

```
$merchantProductId = $cartItem->getMerchantProductId();
$name = $cartItem->getName();
$price = $cartItem->getPrice();
$sku = $cartItem->getSku();
$quantity = $cartItem->getQuantity();
$size = $cartItem->getSize();
$color = $cartItem->getColor();
```

### Charges

This object represents total order charges, discounts applied, tax and shipping amounts. ```total``` refers to the subtotal (without shipping and taxes), and ```grandTotal``` refers to the grand total after shipping, taxes, and discounts applied.  Each field should be a floating point value.

```
$charges = new \CreditKey\Models\Charges($total, $shipping, $tax, $discountAmount, $grandTotal);
```

```
$total = $charges->getTotal();
$shipping = $charges->getShipping();
$tax = $charges->getTax();
$discountAmount = $charges->getDiscountAmount();
$grandTotal = $charges->getGrandTotal();
```

### Order

This object is used to return information about an order.  It should be unnecessary for consuming applications to instantiate this object; it is returned by various methods but never used as a parameter.

```
$orderId = $order->getOrderId();
$status = $order->getStatus();
$captureStatus = $order->getCaptureStatus();
$amount = $order->getAmount();
$refundedAmount = $order->getRefundedAmount();
$merchantOrderId = $order->getMerchantOrderId();
$status = $order->getMerchantStatus();

// Returns an array of CartItem objects
$items = $order->getItems();

// Returns an Address object
$shippingAddress = $order->getShippingAddress();
```

## Exceptions
-------------

### NotFoundException

### OperationErrorException

## Authentication
-----------------

### authenticate

## Checkout Methods
-------------------

### isDisplayedInCheckout

### beginCheckout

### completeCheckout

## Order Management Methods
---------------------------

### confirm

### update

### find

### findByMerchantId

### cancel

### refund

