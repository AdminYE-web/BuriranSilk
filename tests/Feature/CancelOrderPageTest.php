<?php

namespace Tests\Feature;

use Tests\TestCase;

class CancelOrderPageTest extends TestCase
{
    public function test_cancel_order_page_explains_the_policy_and_links_to_contact(): void
    {
        $this->get('/guide/cancel-order')
            ->assertOk()
            ->assertSee('ご注文のキャンセル')
            ->assertSee('キャンセル可能な期間')
            ->assertSee('キャンセルをお受けできない場合')
            ->assertSee(route('contact.index', ['inquiry_type' => 'order']), false);
    }
}
