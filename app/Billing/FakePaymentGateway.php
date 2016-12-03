<?php
namespace App\Billing;

use App\Billing\PaymentGateway;

class FakePaymentGateway implements PaymentGateway {
	private $charges;
	
	function __construct() {
		$this->charges = collect();
	}
	
	public function getValidTestToken() {
		return '123';
	}

	public function totalCharges() {
		return $this->charges->sum();
	}

	public function charge($amount, $token) {
		$this->charges[] = $amount;
	}

}

?>