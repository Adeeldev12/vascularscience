<div>
    <h3>Calendar</h3>
    <p><strong>Available:</strong> {{ implode(', ', $availableDates) }}</p>
    <p><strong>Unavailable:</strong> {{ implode(', ', $unavailableDates) }}</p>
    <p><strong>Pending:</strong> {{ implode(', ', $pendingDates) }}</p>
</div>
