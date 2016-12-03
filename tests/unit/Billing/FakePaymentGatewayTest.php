<?php
use App\Billing\FakePaymentGateway;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FakePaymentGatewayTest extends TestCase {

    function test_charges_with_a_valid_payment_token() {
       $paymentGateway = new FakePaymentGateway;
       $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
       $this->assertEquals(2500, $paymentGateway->totalCharges());
    }

}
?>




?>