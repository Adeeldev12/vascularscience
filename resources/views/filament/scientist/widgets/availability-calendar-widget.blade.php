<div>
    {{-- Debug --}}
    <pre class="text-white bg-gray-800 p-2 rounded mb-2">
        Available: {{ json_encode($availableDates) }}
        Unavailable: {{ json_encode($unavailableDates) }}
        Pending: {{ json_encode($pendingDates) }}
    </pre>

    <div
        x-data="{
            availableDates: @js($availableDates),
            unavailableDates: @js($unavailableDates),
            pendingDates: @js($pendingDates),
            statusSummary: {},

            init() {
                const allDates = [
                    ...this.availableDates.map(date => ({ date, status: 'available' })),
                    ...this.unavailableDates.map(date => ({ date, status: 'unavailable' })),
                    ...this.pendingDates.map(date => ({ date, status: 'pending' })),
                ];

                // Build summary dynamically for below calendar
                this.statusSummary = {
                    ...(this.availableDates.length ? { 'Available': 'bg-green-600' } : {}),
                    ...(this.unavailableDates.length ? { 'Unavailable': 'bg-red-600' } : {}),
                    ...(this.pendingDates.length ? { 'Pending': 'bg-yellow-500' } : {}),
                };

                this.$nextTick(() => {
                    const fp = flatpickr(this.$refs.calendarInput, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        disableMobile: true,
                        monthSelectorType: 'dropdown',

                        onDayCreate: function (selectedDates, dateStr, instance, dayElem) {
                            const date = dayElem.dateObj.toISOString().split('T')[0];
                            const match = allDates.find(x => x.date === date);

                            // Instead of adding dots, color the background directly
                            if (match) {
                                if (match.status === 'available') {
                                    dayElem.classList.add('available-day');
                                } else if (match.status === 'unavailable') {
                                    dayElem.classList.add('unavailable-day');
                                } else if (match.status === 'pending') {
                                    dayElem.classList.add('pending-day');
                                }
                            }
                        },

                        onMonthChange: function () {
                            instance.redraw();
                        },
                    });

                    Livewire.on('availabilityUpdated', () => {
                        fp.destroy();
                        this.init();
                    });
                });
            }
        }"
        class="flex justify-center items-center min-h-[20vh]"
    >
        <div class="bg-gray-900 text-white p-4 rounded-xl shadow-lg inline-block">
            <input type="text" x-ref="calendarInput" class="hidden" />

            {{-- Dynamic legend based on data --}}
            <template x-if="Object.keys(statusSummary).length > 0">
                <div class="mt-4 text-sm space-y-1">
                    <template x-for="(color, label) in statusSummary" :key="label">
                        <div>
                            <span :class="color + ' inline-block w-3 h-3 rounded-full mr-2'"></span>
                            <span x-text="label"></span>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Static legend if no data --}}
            <template x-if="Object.keys(statusSummary).length === 0">
                <div class="mt-4 text-sm space-y-1 opacity-60 italic">
                    <div><span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span> Available</div>
                    <div><span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span> Unavailable</div>
                    <div><span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2"></span> Pending</div>
                </div>
            </template>
        </div>
    </div>

    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        @endpush

        @push('styles')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
            <style>
                /* Dark calendar theme */
                .flatpickr-calendar {
                    background: #111827 !important;
                    border: 1px solid #1f2937 !important;
                    color: #f3f4f6 !important;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                    border-radius: 0.75rem;
                }

                .flatpickr-day {
                    color: #e5e7eb !important;
                    border-radius: 6px;
                    position: relative;
                    transition: all 0.2s ease-in-out;
                }

                .flatpickr-day:hover {
                    background: #2563eb !important;
                    color: #fff !important;
                }

                .flatpickr-current-month,
                .flatpickr-weekday {
                    color: #f9fafb !important;
                }

                /* Remove side arrows */
                .flatpickr-prev-month,
                .flatpickr-next-month {
                    display: none !important;
                }

                /* White year arrows */
                .flatpickr-current-month .numInputWrapper span.arrowUp:after,
                .flatpickr-current-month .numInputWrapper span.arrowDown:after {
                    border-bottom-color: #fff !important;
                    border-top-color: #fff !important;
                }

                /* Highlight current day */
                .flatpickr-day.today {
                    border: 1px solid #2563eb !important;
                    color: #fff !important;
                }

                /* Background colors for availability */
                .flatpickr-day.available-day {
                    background-color: #0ed658 !important; /* green */
                    color: #fff !important;
                }
                .flatpickr-day.unavailable-day {
                    background-color: #cb0a0a !important; /* red */
                    color: #fff !important;
                }
                .flatpickr-day.pending-day {
                    background-color: #eab308 !important; /* yellow */
                    color: #111827 !important;
                }
            </style>
        @endpush
    @endonce
</div>
