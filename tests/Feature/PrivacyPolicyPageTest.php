<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPolicyPageTest extends TestCase
{
    public function test_privacy_policy_page_shows_the_policy_sections(): void
    {
        $this->get('/privacy-policy')
            ->assertOk()
            ->assertSee('プライバシーポリシー')
            ->assertSee('第1条 個人情報の取得と利用')
            ->assertSee('第2条 個人情報の管理と保護')
            ->assertSee('第3条 準拠法等')
            ->assertSee('第4条 問合せ・苦情への対応')
            ->assertSee('第5条 個人情報保護管理体制および仕組みの継続的改善')
            ->assertSee('個人情報の利用目的')
            ->assertSee('開示等の請求手続き')
            ->assertSee('ご本人様の確認のための書類')
            ->assertSee('500円（税込）')
            ->assertSee('開示等のご請求に対する回答方法')
            ->assertSee('info@youandearth.com');
    }
}
