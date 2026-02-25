{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}


@component('mail::message')
# Scientist Availability {{ ucfirst($action) }}

Hello Admin,

**{{ $scientist->name }}** has just **{{ $action }}** their availability.

{{-- 📅 **Date:** {{ $availability->date }}
🕒 **Time:** {{ $availability->start_time }} - {{ $availability->end_time }} --}}
📅 **Date:** {{ \Carbon\Carbon::parse($availability->date)->format('Y-m-d') }}
🕒 **Time:**
{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
 -
{{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}


@component('mail::button', ['url' => config('app.url')])
View in Admin Panel
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

