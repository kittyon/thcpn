
@component('mail::panel')
# 用户注册

只差一步，即可完成注册！
请将下方展示的4位验证数字输入进注册验证框，并完成注册。

{{$code}}
@endcomponent

@component('mail::footer')
{{ config('app.name') }}
@endcomponent
