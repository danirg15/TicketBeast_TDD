<?php
use App\Concert;
use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseTicketsTest extends TestCase {
	use DatabaseMigrations;

    protected function setUp() {
        parent::setUp();
        $this->paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);
    }

    private function orderTickets($concert, $params) {
        $this->json('POST', "/concerts/{$concert->id}/orders", $params);
    }

    private function assertValidationError($field) {
        $this->assertResponseStatus(422);
        $this->assertArrayHasKey($field, $this->decodeResponseJson());
    }

    function test_customer_can_purchase_concerts_tickets() {
       	$concert = factory(Concert::class)->create([
       		'ticket_price' => 3250
       	]);

       	$this->orderTickets($concert, [
       		'email' => 'john@doe.com',
       		'ticket_quantity' => 3,
       		'payment_token' => $this->paymentGateway->getValidTestToken()
       	]);

        $this->assertResponseStatus(201);

       	$this->assertEquals(9750, $this->paymentGateway->totalCharges());
       	$this->assertTrue($concert->orders->contains(function ($order) {
       	    return $order->email = 'john@doe.com';
       	}));

       	$order = $concert->orders()->where('email', 'john@doe.com')->first();
       	$this->assertEquals(3, $order->tickets()->count());
    }


    /** @test */
    function test_email_is_required_field_to_purschase_tickets (){
        //$this->disableExceptionHandling();
        $concert = factory(Concert::class)->create();

        $this->orderTickets($concert, [
          'ticket_quantity' => 3,
          'payment_token' => $this->paymentGateway->getValidTestToken()
        ]);

        $this->assertValidationError('email');
    }

    /** @test */
    function test_email_must_be_valid_to_purschase_tickets (){
        //$this->disableExceptionHandling();
        $concert = factory(Concert::class)->create();

        $this->orderTickets($concert, [
          'ticket_quantity' => 3,
          'email' => 'not-a-valid-email',
          'payment_token' => $this->paymentGateway->getValidTestToken()
        ]);

        $this->assertValidationError('email');
    }


    /** @test */
    function test_ticket_quantity_is_required_to_purschase_tickets (){
        //$this->disableExceptionHandling();
        $concert = factory(Concert::class)->create();

        $this->orderTickets($concert, [
          'email' => 'dani@gmail.com',
          'payment_token' => $this->paymentGateway->getValidTestToken()
        ]);

        $this->assertValidationError('ticket_quantity');
    }


     /** @test */
    function test_ticket_quantity_must_be_at_least_1_to_purschase_tickets (){
        $concert = factory(Concert::class)->create();

        $this->orderTickets($concert, [
          'ticket_quantity' => 0,
          'email' => 'not-a-valid-email',
          'payment_token' => $this->paymentGateway->getValidTestToken()
        ]);

        $this->assertValidationError('ticket_quantity');
    }

    /** @test */
    function test_payment_token_is_required (){
        $concert = factory(Concert::class)->create();

        $this->orderTickets($concert, [
          'ticket_quantity' => 1,
          'email' => 'john@doe.com',
        ]);

        $this->assertValidationError('payment_token');
    }
    

}
?>

