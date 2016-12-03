<?php

use App\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConcertTest extends TestCase {
	use DatabaseMigrations;

    function test_can_get_formatted_date() {
    	$concert = factory(Concert::class)->make([
    		'date' => Carbon::parse('2016-12-01 08:00pm')
    	]);

    	$date = $concert->formatted_date;
    	$this->assertEquals('December 1, 2016', $date);
    }


    /** @test */
    function test_can_get_formatted_start_time (){
    	$concert = factory(Concert::class)->make([
    		'date' => Carbon::parse('2016-12-01 17:00:00')
    	]);

    	$this->assertEquals('5:00pm', $concert->formatted_start_time);
    }

    /** @test */
    function test_can_get_ticket_price_in_dollars (){
    	$concert = factory(Concert::class)->make([
    		'ticket_price' => 6750
    	]);

    	$this->assertEquals('67.50', $concert->ticket_price_in_dollars);
    }


    /** @test */
    function test_can_order_concert_tickets (){
        $concert = factory(Concert::class)->create();
        $order = $concert->orderTickets('john@doe.com', 3);
        $this->assertEquals('john@doe.com', $order->email);
        $this->assertEquals(3, $order->tickets()->count());
    }
    
    
}
?>

