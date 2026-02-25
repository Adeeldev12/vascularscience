{{-- @component('mail::message')
# Scientist Availability {{ ucfirst($action) }}

**Scientist:** {{ $availability->scientist->name }}

📅 **Date:** {{ $availability->date }}
🕒 **Time:** {{ $availability->start_time }} - {{ $availability->end_time }}

**Status:** {{ $availability->status }}

**Note:** {{ $availability->note }}

This availability was {{ $action }} by {{ $availability->scientist->name }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent --}}
<!DOCTYPE html>
<html>
<body>
    <p><strong>Scientist Availability {{ ucfirst($action) }}</strong></p>

    <p><strong>Scientist:</strong> {{ $availability->scientist->name }}</p>

    <p>
        📅 <strong>Date:</strong> {{ $availability->date }}<br>
        🕒 <strong>Time:</strong> {{ $availability->start_time }} - {{ $availability->end_time }}
    </p>

    <p><strong>Status:</strong> {{ ucfirst($availability->status) }}</p>

    @if($availability->note)
        <p><strong>Note:</strong> {{ $availability->note }}</p>
    @endif

    <p>This availability was {{ $action }} by {{ $availability->scientist->name }}.</p>

    <br>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
