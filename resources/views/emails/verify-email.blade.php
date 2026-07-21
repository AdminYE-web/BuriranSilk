<x-mail::message>
# ThaiSilkへようこそ

{{ $user->name ?: $user->email }} 様

会員登録を完了するため、下のボタンからメールアドレスを確認してください。

<x-mail::button :url="$verificationUrl">
メールアドレスを確認する
</x-mail::button>

この確認リンクの有効期限は5分です。

このメールに心当たりがない場合は、何も操作する必要はありません。

ThaiSilk
</x-mail::message>