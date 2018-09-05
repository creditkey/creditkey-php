# Credit Key PHP SDK

## Table of Contents

- [Support](#support)
- [Requirements](#requirements)
- [Overview](#overview)
- [Return to Merchant after Credit Key Checkout](#return-to-merchant-after-credit-key-checkout)
    - [Return URL](#return-url)
    - [Cancel URL](#cancel-url)
    - [Actions Upon Return](#actions-upon-return)
- [Getting Started](#getting-started)
    - [With Composer](#with-composer)
    - [Without Composer](#without-composer)
- [Models](#models)
    - [Address](#address)
    - [CartItem](#cartitem)
    - [Charges](#charges)
    - [Order](#order)
- [Exceptions](#exceptions)
    - [ApiNotConfiguredException](#apinotconfiguredexception)
    - [ApiUnauthorizedException](#apiunauthorizedexception)
    - [InvalidRequestException](#invalidrequestexception)
    - [NotFoundException](#notfoundexception)
    - [OperationErrorException](#operationerrorexception)
- [Authentication](#authentication)
    - [configure](#configure)
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

[Credit Key](https://www.creditkey.com) checkout works similarly as services like [PayPal](https://www.paypal.com) in the sense that the user will be redirected to special checkout pages hosted on [creditkey.com](https://www.creditkey.com) to complete the checkout process.

When rendering your checkout page, you should always call [\CreditKey\Checkout::isDisplayedInCheckout](#isdisplayedincheckout) to determine whether or not to display Credit Key as a payment option.

When the user selects Credit Key as a payment option on your checkout page, you will need to call [\CreditKey\Checkout::beginCheckout](#begincheckout).  Using this method you will send information about the order to Credit Key, such as the items in the user's shopping cart, the total amount to be billed, the billing and shipping addresses specified by the user in checkout, and so forth.  This method will return a unique [creditkey.com](https://www.creditkey.com) URL which you should redirect the user's browser to, in order for them to complete the checkout process.

After successful checkout on Credit Key's site, Credit Key will redirect the user's browser back to a unique URL provided by you to [beginCheckout](#begincheckout).  At this point you should call [\CreditKey\Checkout::completeCheckout](#completecheckout) to validate that the payment was successful and complete the order.  Upon successful return from [completeCheckout](#completecheckout), you should place the order in your system - then display your own order confirmation place to the user.

When the order ships, you should call [\CreditKey\Orders::confirm](#confirm) to notify Credit Key that the order has shipped.  If [confirm](#confirm) isn't called for several days after completing checkout, Credit Key will automatically cancel the order in it's system and the payment will not be issued.

If the order is canceled before shipment, you can call [\CreditKey\Orders::cancel](#cancel) to cancel the payment.  To issue a full or partial refund, use [\CreditKey\Orders::refund](#refund).

## Return to Merchant after Credit Key Checkout
-----------------------------------------------

You will need to implement at least one, possibly two, endpoints or controller actions on your system to receive users returning from Credit Key checkout.  These URL's are provided to Credit Key each time a user selects the option to check out with Credit Key, when calling [\CreditKey\Checkout::beginCheckout](#begincheckout).  They can be unique user-specific URL's.

If the Cancel URL or Return URL you provide to Credit Key include the string ```%CKKEY%```, then upon redirect this string will be replaced with the Credit Key Order ID.

### Return URL

The Return URL will be a URL on your system that Credit Key redirects the user's browser to after successful checkout.  When the user returns to this URL, you should validate the successful payment with Credit Key, complete the order in your system, and then display your order confirmation page.  Credit Key will not redirect a user to this URL if they have not successfully completed Credit Key checkout.

We recommend creating a session-specific URL for each request that contains identifying information about the session, such as the primary key in your system used to refer to the user's checkout session.  This way you will easily be able to line up the Credit Key order with the user's shopping cart session.  However, if you track checkout sessions with cookies, a general URL might work in your scenario.

### Cancel URL

Credit Key will redirect users to the Cancel URL when checkout was not completed successfully - such as when the user canceled the Credit Key checkout session, or if the user was not able to be approved for a loan.  In many cases, you can simply provide the URL to your checkout page for the Cancel URL.  But if you want to take another action besides going back to the checkout page, or perform tracking, you can redirect elsewhere.

### Actions Upon Return

In the endpoint you setup to handle the [Return URL](#return-url), you should take the following actions:

1. Call [\CreditKey\Checkout::completeCheckout](#completecheckout), passing the Credit Key Order ID provided in the URL by Credit Key.  This method should return ```true``` to indicate the payment is authorized and you can continue placing the order.  If ```false``` is returned, or an [exception is thrown](#exceptions), you should return an error and you should not continue placing the order.
2. Place the order as a new order in your system as an order with an authorized payment.
3. Call [\CreditKey\Orders::update](#update) to provide Credit Key with your local merchant Order ID and Order Status.

## Getting Started
------------------

### With Composer

If your project uses the [Composer](https://getcomposer.org) dependency manager, you can include the Credit Key PHP SDK by executing the following from the command-line:

```
% composer require creditkey/b2bgateway
```

Composer's autoload should then automatically load the bindings.

### Without Composer

If you do not want to use Composer, you can load the bindings by including the ```init.php``` file:

```
require_once('/path/to/creditkey-php/init.php');
```

## Models
---------

Most SDK methods either accept one or more of these models as an argument, or return one as a result.  All models are similar in that field values can only be set by the constructor, and can be accessed by corresponding ```get``` methods.  All models documented here are under the ```\CreditKey\Models``` namespace.

### Address

This object is used to represent either a billing or shipping address.

```
$billingAddress = new \CreditKey\Models\Address($firstName, $lastName, $companyName, $email, $address1, $address2, $city, $state, $zip, $phoneNumber);
```

```
$firstName = $billingAddress->getFirstName();
$lastName = $billingAddress->getLastName();
$companyName = $billingAddress->getCompanyName();
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

```$shipping```, ```$tax```, and ```$discountAmount``` can be ```null``` or ```0``` if the value is not applicable to this purchase.

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

The following common exceptions are thrown by Credit Key SDK methods when various errors are encountered.

### ApiNotConfiguredException

```\CreditKey\Exceptions\ApiNotConfiguredException``` is thrown if you attempt to call any SDK method before configuring the API endpoint and credentials using [\CreditKey\Api::configure](#configure).

### ApiUnauthorizedException

```\CreditKey\Exceptions\ApiUnauthorizedException``` is thrown when the API has been configured with an invalid Public Key/Shared Secret combination.

### InvalidRequestException

```\CreditKey\Exceptions\InvalidRequestException``` is thrown when invalid parameters were passed by the consuming application to the SDK.

### NotFoundException

```\CreditKey\Exceptions\NotFoundException``` is thrown by methods in the ```Orders``` class when the given order ID is not found.

### OperationErrorException

```\CreditKey\Exceptions\OperationErrorException``` is thrown when the Credit Key API  encounters an error during a request.

## Authentication
-----------------

### configure

This method is used to provide the Credit Key PHP SDK with the API environment to connect to, and your given public key and shared secret.  The public key and shared secret values which should be provided to you by Credit Key support staff.  It is necessary to configure the API before calling any other SDK method.

The first parameter specifies which API environment should be connected to.  Valid values are ```\CreditKey\Api::PRODUCTION```, ```\CreditKey\Api::STAGING```, and ```\CreditKey\Api::LOCAL_DEVELOPMENT```.

```
\CreditKey\Api::configure(\CreditKey\Api::PRODUCTION, $publicKey, $sharedSecret);
```

### authenticate

This method can be used to determine whether valid public key and shared secret values have been provided - and the Credit Key API is up and reachable.  A boolean is returned.

```
$isSuccessful = \CreditKey\Authentication::authenticate();
```

## Checkout Methods
-------------------

### isDisplayedInCheckout

This method should be called as the Checkout page is rendered, to determine whether or not to offer Credit Key as a payment option to the user. ```$cartContents``` should be an array of [\CreditKey\Models\CartItem](#cartitem), and ```$customerId``` should be the unique key on the merchant site to refer to this user if they are logged in.  For guest checkout, ```$customerId``` should be ```null```.

```
$isDisplayed = \CreditKey\Checkout::isDisplayedInCheckout($cartContents, $customerId);
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
* **$returnUrl** - Required - a unique URL on the merchant site that Credit Key will redirect the user's browser to upon successful checkout. See the section on the [Return Url](#return-url) for additional information.
* **$cancelUrl** - Required - a URL on the merchant site that Credit Key will redirect the user's browser to if the Credit Key checkout failed, was declined, or canceled by the user.  See the s3ection on the [Cancel URL](#cancel-url) for additional information.

#### Example

```
$redirectUrl = \CreditKey\Checkout::beginCheckout($cartContents, $billingAddress, $shippingAddress, $charges, $remoteId, $customerId, $returnUrl, $cancelUrl);
```

### completeCheckout

After a successful checkout, Credit Key will redirect back to the merchant website where the payment will be validated, and the order will be placed.  This method completes this checkout process when the order is placed.  If this method is not called by the merchant for an order, even if the customer successfully completed Credit Key's checkout, then the payment will not be made.  ```$ckOrderId``` refers to the unique Credit Key order ID that was returned on redirect back to the merchant site.  A boolean is returned describing whether the payment was successfully authorized.  See [Actions Upon Return](#actions-upon-return) for additional information.

```
$isAuthorized = \CreditKey\Checkout::completeCheckout($ckOrderId);
```

If ```false``` is returned here or an [exception is thrown](#exceptions), you should not treat the order as a valid order.

## Order Management Methods
---------------------------

### confirm

This method should be called when the order is shipped.  Send the updated ```$cartContents``` and ```$charges``` (in case they've changed since purchase), as well as the Order ID used by the merchant application (```$merchantOrderId```) and the order status in the merchant system (```$merchantOrderStatus```).  A [\CreditKey\Models\Order](#order) object is returned.

```
$order = \CreditKey\Orders::confirm($ckOrderId, $merchantOrderId, $merchantOrderStatus, $cartContents, $charges);
```

### update

This method can be used to update the ```$charges```, ```$cartContents```, ```$shippingAddress```, ```$merchantOrderId``` or ```$merchantOrderStatus``` at any time in Credit Key's system.  ```null``` can be sent for any parameter besides ```$ckOrderId``` if you do not with to update the values associated with that parameter.  A [\CreditKey\Models\Order](#order) object is returned.

```
$order = \CreditKey\Orders::update($ckOrderId, $merchantOrderStatus, $merchantOrderId, $cartContents, $charges, $shippingAddress);
```

We recommend calling this method immediately after checkout, as soon as a corresponding order is created in the merchant application, to provide Credit Key with the ```$merchantOrderId```.

### find

Retreive order data from Credit Key using ```$ckOrderId```.  A [\CreditKey\Models\Order](#order) object is returned.  [\CreditKey\Exceptions\NotFoundException](#notfoundexception) is thrown if the order cannot be found.

```
$order = \CreditKey\Orders::find($ckOrderId);
```

### findByMerchantId

Retreive order data from Credit Key using the merchant application order ID.  If you have not provided the ```$merchantOrderId``` to Credit Key via a previous API call, this method will fail.  A [\CreditKey\Models\Order](#order) object is returned.  [\CreditKey\Exceptions\NotFoundException](#notfoundexception) is thrown if the order cannot be found.

```
$order = \CreditKey\Orders::findByMerchantOrderId($merchantOrderId);
```

### cancel

Cancel an order.  This method can only be called before [\CreditKey\Orders::confirm](#confirm) is called.  It will cancel the order.  This method is intended to be used when the order is canceled before shipment.  A [\CreditKey\Models\Order](#order) object is returned.

```
$order = \CreditKey\Orders::cancel($ckOrderId);
```

### refund

Issue either a partial or full refund to the customer.  ```$refundAmount``` should be a positive floating point value indicating the amount to refund.  A [\CreditKey\Models\Order](#order) object is returned.

```
$refundAmount = 29.99;
$order = \CreditKey\Orders::refund($ckOrderId, $refundAmount);
```
