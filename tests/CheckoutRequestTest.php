<?php
use CreditKey\Api;
use CreditKey\Checkout;
use CreditKey\Exceptions\InvalidRequestException;
use CreditKey\TestSupport\CheckoutHttpStub;
use CreditKey\TestSupport\CreditKeyTestData;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class CheckoutRequestTest extends TestCase
{
    protected function setUp(): void
    {
        require __DIR__ . '/../testSupport/CheckoutHttpStub.php';
        Api::configure('https://api.example.test', 'test-public-key', 'test-shared-secret');
    }

    private function checkoutArguments(): array
    {
        return array(
            CreditKeyTestData::cartContents(),
            CreditKeyTestData::billingAddress(),
            CreditKeyTestData::shippingAddress(),
            CreditKeyTestData::charges(),
            'test-quote',
            null,
            'https://merchant.example.test/return',
            'https://merchant.example.test/cancel',
            '',
            'modal'
        );
    }

    private function lastRequest(): array
    {
        $options = end(CheckoutHttpStub::$requests);
        $this->assertSame('https://api.example.test/ecomm/begin_checkout', $options[CURLOPT_URL]);
        $this->assertSame(1, $options[CURLOPT_POST]);
        return json_decode($options[CURLOPT_POSTFIELDS], true, 512, JSON_THROW_ON_ERROR);
    }

    public function testExistingCheckoutOmitsMerchantData(): void
    {
        $url = call_user_func_array(array(Checkout::class, 'beginCheckout'), $this->checkoutArguments());

        $this->assertSame('https://checkout.example.test/session', $url);
        $request = $this->lastRequest();
        $this->assertArrayNotHasKey('merchant_data', $request);
        $this->assertSame('test-quote', $request['remote_id']);
        $this->assertSame('modal', $request['mode']);
        $this->assertSame('test-public-key', $request['public_key']);
    }

    public function testEightArgumentCheckoutPreservesLegacyPayload(): void
    {
        $args = $this->checkoutArguments();
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $expected = $this->lastRequest();
        unset($expected['order_complete_url'], $expected['mode']);

        $url = call_user_func_array(array(Checkout::class, 'beginCheckout'), array_slice($args, 0, 8));

        $this->assertSame('https://checkout.example.test/session', $url);
        $this->assertSame($expected, $this->lastRequest());
    }

    public static function legacyModes(): array
    {
        return array('modal' => array('modal'), 'redirect' => array('redirect'));
    }

    #[DataProvider('legacyModes')]
    public function testNineArgumentCheckoutTreatsNinthArgumentAsMode(string $mode): void
    {
        $args = $this->checkoutArguments();
        $args[9] = $mode;
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $expected = $this->lastRequest();
        unset($expected['order_complete_url']);

        $legacyArgs = array_slice($args, 0, 8);
        $legacyArgs[] = $mode;
        $url = call_user_func_array(array(Checkout::class, 'beginCheckout'), $legacyArgs);

        $this->assertSame('https://checkout.example.test/session', $url);
        $this->assertSame($expected, $this->lastRequest());
    }

    public static function currentCheckoutOptions(): array
    {
        $cases = array();
        foreach (array('modal', 'redirect') as $mode) {
            foreach (array('url' => 'https://merchant.example.test/complete', 'empty' => '', 'null' => null) as $label => $url) {
                $cases[$mode . '-' . $label] = array($url, $mode);
            }
        }
        return $cases;
    }

    #[DataProvider('currentCheckoutOptions')]
    public function testCurrentSignaturesPreserveCallbackAndMode(?string $callback, string $mode): void
    {
        $args = $this->checkoutArguments();
        $args[8] = $callback;
        $args[9] = $mode;
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $standard = $this->lastRequest();
        $this->assertArrayHasKey('order_complete_url', $standard);
        $this->assertSame($callback, $standard['order_complete_url']);
        $this->assertSame($mode, $standard['mode']);
        $this->assertArrayNotHasKey('merchant_data', $standard);

        $args[] = array('pay_now' => true);
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $payByBank = $this->lastRequest();
        $this->assertSame(array('pay_now' => true), $payByBank['merchant_data']);
        unset($payByBank['merchant_data']);
        $this->assertSame($standard, $payByBank);
    }

    public static function legacyArgumentCounts(): array
    {
        return array('eight' => array(8), 'nine' => array(9));
    }

    #[DataProvider('legacyArgumentCounts')]
    public function testLegacyCheckoutStillRejectsMissingRequiredData(int $argumentCount): void
    {
        $args = array_slice($this->checkoutArguments(), 0, 8);
        $args[0] = null;
        if ($argumentCount === 9) {
            $args[] = 'modal';
        }

        $this->expectException(InvalidRequestException::class);
        try {
            call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        } finally {
            $this->assertSame(array(), CheckoutHttpStub::$requests);
        }
    }

    public function testExplicitNullPreservesExistingPayload(): void
    {
        $args = $this->checkoutArguments();
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $original = $this->lastRequest();

        $args[] = null;
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);

        $this->assertSame($original, $this->lastRequest());
    }

    public function testPayByBankAddsOnlyMerchantDataWithBooleanTrue(): void
    {
        $args = $this->checkoutArguments();
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $original = $this->lastRequest();

        $args[] = array('pay_now' => true);
        $url = call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        $request = $this->lastRequest();

        $this->assertSame('https://checkout.example.test/session', $url);
        $this->assertSame(array('pay_now' => true), $request['merchant_data']);
        unset($request['merchant_data']);
        $this->assertSame($original, $request);
    }

    public function testMerchantDataPreservesFalseAndOtherFields(): void
    {
        $args = $this->checkoutArguments();
        $merchantData = array('pay_now' => false, 'reference' => 'bank-checkout');
        $args[] = $merchantData;

        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);

        $this->assertSame($merchantData, $this->lastRequest()['merchant_data']);
    }

    public function testPayByBankDoesNotAffectSubsequentStandardCheckout(): void
    {
        $args = $this->checkoutArguments();
        $args[] = array('pay_now' => true);
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $args);
        call_user_func_array(array(Checkout::class, 'beginCheckout'), $this->checkoutArguments());

        $this->assertCount(2, CheckoutHttpStub::$requests);
        $this->assertArrayNotHasKey('merchant_data', $this->lastRequest());
    }
}
