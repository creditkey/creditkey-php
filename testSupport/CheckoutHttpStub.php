<?php
namespace CreditKey\TestSupport {
    /** Captures the serialized request without contacting the Credit Key API. */
    final class CheckoutHttpStub
    {
        public static $requests = array();
    }
}

namespace CreditKey {
    // Loaded only in an isolated PHPUnit process to avoid affecting API tests.
    function curl_init()
    {
        return (object) array('options' => array());
    }

    function curl_setopt($handle, $option, $value)
    {
        $handle->options[$option] = $value;
        return true;
    }

    function curl_exec($handle)
    {
        \CreditKey\TestSupport\CheckoutHttpStub::$requests[] = $handle->options;
        return '{"checkout_url":"https://checkout.example.test/session"}';
    }

    function curl_getinfo($handle, $option)
    {
        return 200;
    }
}
