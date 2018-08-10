# Credit Key PHP SDK

## Table of Contents

- [Support](#support)
- [Requirements](#requirements)
- [Overview](#overview)
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

## Overview
-----------

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

This object represents an product in the user's shopping cart. ```$sku```, ```$size```, and ```$color``` are all optional and can be ```null```.  The ```$merchantProductId``` is the key referring to the product on the merchant system.

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

This object represents total order charges, discounts applied, tax and shipping amounts. ```$total``` refers to the subtotal (without shipping and taxes), and ```$grandTotal``` refers to the grand total after shipping, taxes, and discounts applied.  Each field should be a floating point value.

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

This object is used to return information about an order that has been placed, after checkout was successfully completed.  It should be unnecessary for consuming applications to instantiate this object; it is returned by various methods but never used as a parameter.

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

### InvalidRequestException

```\CreditKey\Exceptions\InvalidRequestException``` is thrown when invalid parameters were passed by the consuming application to the SDK.

### NotFoundException

```\CreditKey\Exceptions\NotFoundException``` is thrown by methods in the ```Orders``` class when the given order ID is not found.

### OperationErrorException

```\CreditKey\Exceptions\OperationErrorException``` is thrown when the Credit Key API  encounters an error during a request.

## Authentication
-----------------

### authenticate

## Checkout Methods
-------------------

### isDisplayedInCheckout

This method should be called as the Checkout page is rendered, to determine whether or not to offer Credit Key as a payment option to the user. ```$cartContents``` should be an array of [\CreditKey\Models\CartItem](#cartitem), and ```$customerId``` should be the unique key on the merchant site to refer to this user if they are logged in.  For guest checkout, ```$customerId``` should be ```null```.

```
$isDisplayed = Checkout::isDisplayedInCheckout($cartContents, $customerId);
```

### beginCheckout

This method should be called when the user selects Credit Key as a payment option to complete checkout.  This method should be called with all available customer information from the checkout page, and will return a unique [creditkey.com](https://www.creditkey.com) URL that the merchant site should redirect the user to, in order to complete checkout.

#### Parameters

* **$cartContents** - Required - an array of [\CreditKey\Models\CartItem](#cartitem) objects describing all items in the user's shopping cart.
* **$billingAddress** - Required - a [\CreditKey\Models\Address](#address) object describing the customer name, email, phone number, and billing address provided on the checkout page.  If your checkout page does not collect a billing address, you must pass this object with at least the ```$firstName```, ```$lastName```, and ```$email``` fields and preferably the ```$phoneNumber```.  Other fields can be ```null```.
* **$shippingAddress** - Required - The shipping address that the customer entered on the checkout page.
* **$charges** - Required - a [\CreditKey\Models\Charges](#charges) object describing the order amount, shipping and tax amouts, and any discounts applied.
* **$remoteId** - Required - a unique ID in the merchant application to refer to this user's checkout session.  When Credit Key redirects back to the merchant site after a successful checkout, this ID will be referred to.
* **$customerId** - Optional - a unique ID in the merchant application to refer to the user, if the user is logged in.  Can be ```null```.
* **$returnUrl** - Required - a unique URL on the merchant site that Credit Key will redirect the user's browser to upon successful checkout. TODO- Link to section describing return URL process
* **$cancelUrl** - Required - a URL on the merchant site that Credit Key will redirect the user's browser to if the Credit Key checkout failed, was declined, or canceled by the user.

#### Example

```
$redirectUrl = \CreditKey\Checkout::beginCheckout($cartContents, $billingAddress, $shippingAddress, $charges, $remoteId, $customerId, $returnUrl, $cancelUrl);
```

### completeCheckout

After a successful checkout, Credit Key will redirect back to the merchant website where the payment will be validated, and the order will be placed.  This method completes this checkout process when the order is placed.  If this method is not called by the merchant for an order, even if the customer successfully completed Credit Key's checkout, then the payment will not be made.  ```$ckOrderId``` refers to the unique Credit Key order ID that was returned on redirect back to the merchant site.  A boolean is returned describing whether the payment was successfully authorized.  TODO: link to section describing checkout process.

```
$isAuthorized = \CreditKey\Checkout::completeCheckout($ckOrderId);
```

## Order Management Methods
---------------------------

### confirm

### update

### find

### findByMerchantId

### cancel

### refund

