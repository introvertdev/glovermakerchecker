@component('mail::message')
# You have a pending request that needs your attention.

Kindly login to verify and approve the requests.

@component('mail::button', ['url' => ''])
View Requests
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
