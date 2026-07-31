@extends('frontend.layouts.app')

@section('title', 'プライバシーポリシー | ThaiSilk')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/cancel-order.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/privacy-policy.css') }}">
@endsection

@section('content')
    <section class="cancel-order-page privacy-policy-page">
        <div class="cancel-order-container">
            <header class="cancel-order-header">
                <h1>プライバシーポリシー</h1>
                <p>お客様に安心してご利用いただくための個人情報保護方針</p>
               
            </header>

            <article class="cancel-order-card privacy-policy-card">
                <h2>プライバシーポリシー (HotstrapJP)</h2>

                <div class="cancel-order-content">
                    <div class="privacy-policy-preface">
                        <p>近年、高度情報化に伴う個人情報保護の重要性への社会の関心は高まっており、関連企業をはじめすべてのお客様からお預かりした個人情報を保護することは、ソフトウェア開発・人材派遣業務を営む当社の社会的責務であると考えます。</p>
                        <p>当社は以下のとおり個人情報保護方針を定め、個人情報保護の仕組みを構築し、全従業員に個人情報保護の重要性の認識と取組みを徹底させることにより、個人情報の保護を推進することを宣言致します。</p>
                    </div>

                    <section>
                        <h3>第1条 個人情報の取得と利用</h3>
                        <p>当社は利用目的を明確にした上で、目的の範囲内に限り、個人情報を利用します。利用目的は個人情報管理台帳上に明示し、個人情報を取扱う各部門の部門個人情報管理責任者の責任において、利用目的を逸脱した利用が行われないための確認手順を設け、実施します。</p>
                        <p>また、利用目的の範囲を超えて個人情報の利用を行わないよう、社内の管理体制の整備及び安全管理措置を講じます。</p>
                    </section>

                    <section>
                        <h3>第2条 個人情報の管理と保護</h3>
                        <p>個人情報の管理は、厳重に行うこととし、お客さまにご承諾いただいた場合を除き、第三者に対しデータを開示・提供することはいたしません。また個人情報の漏えい、滅失又はき損を防ぐため、以下のような安全管理措置を行います。また問題発生の予防のための手順を設け実施するとともに、万が一の問題発生に対しては速やかに再発防止のための是正を行います。</p>
                        <p>当社で行う安全措置（一部）：</p>
                        <ul class="privacy-policy-measures">
                            <li>個人情報へのアクセス可能者の制限</li>
                            <li>個人情報へのアクセス権限の設定</li>
                            <li>ネットワークおよび端末等に対する不正アクセス対策</li>
                            <li>個人データへのパスワードの設定または暗号化</li>
                            <li>個人データの外部記憶媒体への書き出し制限</li>
                        </ul>
                    </section>

                    <section>
                        <h3>第3条 準拠法等</h3>
                        <p>当社は、当社が保有する個人情報の取扱いに関して適用される法令、国が定める指針及びその他の規範を遵守いたします。</p>
                    </section>

                    <section>
                        <h3>第4条 問合せ・苦情への対応</h3>
                        <p>当社は、保有する個人情報に対するお問合せや苦情に対して、受付、対応の体制と手順を整備し、迅速に対応いたします。</p>
                    </section>

                    <section>
                        <h3>第5条 個人情報保護管理体制および仕組みの継続的改善</h3>
                        <p>当社は、個人情報保護に関する管理の体制と仕組みについて継続的改善を実施いたします。</p>
                        <div class="privacy-policy-company">
                            <p>制定日　2004年10月01日</p>
                            <p>ユー・アンド・アース株式会社</p>
                            <p>代表取締役　門田　正徳</p>
                            <p>（個人情報に関する苦情・問合せ窓口　<a href="mailto:info@youandearth.com">info@youandearth.com</a>）</p>
                        </div>
                    </section>

                    <section class="privacy-policy-major-section">
                        <h3>個人情報の利用目的</h3>
                        <p>ユー・アンド・アース株式会社（以下、当社）が、取得又は保有する個人情報の利用目的は以下の通りです。</p>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;1&gt; お客様からのお問い合わせへの対応</h4>
                            <p>・お客様から当社になされる、電子メール、郵送、電話などによるお問い合わせに対する対応</p>
                        </div>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;2&gt; その他</h4>
                            <p>・当社のサービスにおいて、上記に規定のない目的で個人情報を利用する場合があります。その場合には、個別サービスのウェブサイトにその旨を掲載します。</p>
                        </div>
                    </section>

                    <section class="privacy-policy-major-section">
                        <h3>開示等の請求手続き</h3>
                        <p>当社は、保有個人データに関する個人の権利を尊重し、自己の個人情報に対し、開示、訂正、削除を求められた時は、本人であることを確認の上、合理的な時間内でこれに応じます。</p>
                        <p>尚、第三者から提供された個人情報については、当社に開示等の権限はありません。</p>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;1&gt; 開示等の請求の申出先</h4>
                            <p>開示等のご請求については、所定の請求書に必要書類を添付の上、郵送によりお願いいたします。請求書を当社へ郵送する際には、配達記録郵便や簡易書留郵便など、配達の記録が確認できる方法にお願いいたします。なお、封筒に朱書きで「個人情報開示請求書在中」とお書き添えいただければ幸いです。</p>
                            <address class="privacy-policy-address">
                                ユー・アンド・アース株式会社<br>
                                〒135-0064 東京都江東区青海2-4-32 TIME24ビル 5階 中央<br>
                                個人情報問い合わせ窓口：個人情報担当者
                            </address>
                        </div>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;2&gt; 開示等の請求における提出書面</h4>
                            <p>開示等のご請求を行う場合は、当社の「個人情報に関する開示等申請書」に所定の事項を全てご記入の上、ご本人様の確認のための下記&lt;3&gt;の書類を同封し上記「個人情報問い合わせ窓口」宛にご郵送ください。</p>
                        </div>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;3&gt; ご本人様の確認のための書類</h4>
                            <div class="privacy-policy-document-group">
                                <h5>・ご本人</h5>
                                <p>運転免許証、各種健康保険証、年金手帳等、旅券（パスポート）のいずれかのコピー または住民票の写し、以上のうち1通</p>
                            </div>
                            <div class="privacy-policy-document-group">
                                <h5>・代理人</h5>
                                <p>代理人であることの証明として、当該本人からの委任状と当該本人の住民票の写し。さらに代理人自身の本人確認として運転免許証、各種健康保険証、年金手帳等、旅券（パスポート）のいずれかのコピーまたは住民票の写し、以上のうち1通</p>
                            </div>
                            <p class="privacy-policy-note">※ 当社にて所定の書面の提出及び手数料の受領を確認できた場合、こちらからご本人確認の電話をさせていただきます。</p>
                        </div>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;4&gt; 開示、利用目的の通知のご請求に関する手数料</h4>
                            <p>個人情報の開示及び利用目的の通知をご請求する場合、手数料をいただきます。</p>
                            <div class="privacy-policy-fee">
                                <p>1回の請求ごとに、500円（税込）</p>
                                <p>500円分の郵便定額小為替を提出書類にご同封ください。</p>
                                <p>郵便定額小為替のご購入のための料金及び当社への郵送料はお客様にてご負担ください。</p>
                            </div>
                            <ul class="privacy-policy-notes">
                                <li>個人情報の開示及び利用目的の通知のときのみ手数料をいただきます。個人情報の訂正、追加、消去、利用停止又は第三者提供の停止のときは手数料は不要です。</li>
                                <li>手数料が不足していた場合、及び手数料が同封されていなかった場合は、その旨ご連絡いたしますが、所定の期間内にお支払いがない場合は、開示、利用目的の通知のご請求がなかったものとして対応させていただきます。</li>
                                <li>その他実費を要した場合は、別途、請求させていただきます。</li>
                            </ul>
                        </div>

                        <div class="privacy-policy-subsection">
                            <h4>&lt;5&gt; 開示等のご請求に対する回答方法</h4>
                            <p>請求者の請求書記載住所宛に書面によってご回答いたします。</p>
                            <p class="privacy-policy-end">以上</p>
                        </div>
                    </section>
                </div>
            </article>
        </div>
    </section>
@endsection
