<?php

namespace Tests\Feature;

use Tests\TestCase;

class HowToPayPageTest extends TestCase
{
    public function test_how_to_pay_page_shows_available_and_upcoming_payment_methods(): void
    {
        $this->get('/guide/payment')
            ->assertOk()
            ->assertSee('お支払い方法')
            ->assertSee('クレジットカード決済')
            ->assertSee('現在メンテナンス中')
            ->assertSee('銀行振込')
            ->assertSee('その他の決済');
    }
}
