<?php

namespace Tests\Feature;

use Tests\TestCase;

class HowToOrderPageTest extends TestCase
{
    public function test_how_to_order_page_shows_the_three_order_steps(): void
    {
        $this->get('/guide/order')
            ->assertOk()
            ->assertSee('ご注文の流れ')
            ->assertSee('商品を選ぶ')
            ->assertSee('仕様・数量を選ぶ')
            ->assertSee('注文内容を確認する');
    }
}
