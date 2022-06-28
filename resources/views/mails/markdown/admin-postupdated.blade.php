@component('mail::message')
# Introduction

Post {{$postSlug}} has been updated!

@component('mail::button', ['url' => $postUrl])
View Post
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
