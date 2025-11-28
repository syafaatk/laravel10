<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Leave') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- LEFT: Leave Application Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Apply for New Leave</h3>
                            
                            <form action="{{ route('cuti.store') }}" method="POST" id="cutiForm">
                                @csrf

                                {{-- User Selection (Admin only) --}}
                                @if(Auth::user()->hasRole('admin'))
                                    <div class="mb-4">
                                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                                        <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('user_id') border-red-500 @enderror">
                                            <option value="">-- Select User --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <p class="text-sm text-blue-900"><strong>Applying as:</strong> {{ Auth::user()->name }}</p>
                                    </div>
                                @endif

                                {{-- Leave Type Selection --}}
                                <div class="mb-4">
                                    <label for="master_cuti_id" class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                                    <select name="master_cuti_id" id="master_cuti_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('master_cuti_id') border-red-500 @enderror" required onchange="updateAvailableDays()">
                                        <option value="">-- Select Leave Type --</option>
                                        @foreach ($masterCutis as $cutiType)
                                            <option value="{{ $cutiType->id }}" data-days="{{ $cutiType->available_days ?? $cutiType->days }}" {{ old('master_cuti_id') == $cutiType->id ? 'selected' : '' }}>
                                                {{ $cutiType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('master_cuti_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div id="availableDaysInfo" class="mt-2 text-sm text-gray-600"></div>
                                </div>

                                {{-- Start Date --}}
                                <div class="mb-4">
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('start_date') border-red-500 @enderror" value="{{ old('start_date') }}" required onchange="calculateDays()">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- End Date --}}
                                <div class="mb-4">
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('end_date') border-red-500 @enderror" value="{{ old('end_date') }}" required onchange="calculateDays()">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Days Requested (Auto-calculated & Read-only) --}}
                                <div class="mb-4">
                                    <label for="days_requested" class="block text-sm font-medium text-gray-700 mb-2">Business Days (Auto-calculated)</label>
                                    <div class="relative">
                                        <input type="number" name="days_requested" id="days_requested" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" value="{{ old('days_requested', 0) }}" readonly>
                                        <small class="text-gray-500 mt-1 block">Excludes weekends, public holidays & cuti bersama</small>
                                    </div>
                                    @error('days_requested')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Reason --}}
                                <div class="mb-6">
                                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                                    <textarea name="reason" id="reason" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('reason') border-red-500 @enderror" rows="4" required placeholder="Enter your reason for leave...">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex gap-3">
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                                        {{ __('Submit Leave Request') }}
                                    </button>
                                    <a href="{{ route('cuti.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Holiday Calendar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Calendar & Holidays</h3>
                            
                            {{-- Calendar Navigation --}}
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" id="prevMonth" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">← Prev</button>
                                <span id="monthYear" class="font-semibold text-gray-800"></span>
                                <button type="button" id="nextMonth" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Next →</button>
                            </div>

                            {{-- Calendar Grid --}}
                            <div id="calendarContainer" class="mb-4">
                                <!-- Generated by JavaScript -->
                            </div>

                            {{-- Holiday Legend --}}
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Holiday Legend</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-red-200 border border-red-400 rounded"></div>
                                        <span class="text-gray-700">Public Holiday / Cuti Bersama</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-gray-300 border border-gray-400 rounded"></div>
                                        <span class="text-gray-700">Weekend (Sat-Sun)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-indigo-200 border border-indigo-400 rounded"></div>
                                        <span class="text-gray-700">Selected Range</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Holidays List --}}
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2">Upcoming Holidays</h4>
                                <div id="holidaysList" class="text-xs space-y-1">
                                    <!-- Populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/isSameOrBefore.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/isSameOrAfter.js"></script>
    <script>
        // Extend dayjs dengan plugins
        dayjs.extend(window.dayjs_plugin_isSameOrBefore);
        dayjs.extend(window.dayjs_plugin_isSameOrAfter);

        // Holiday data from config
        const holidays = {!! json_encode(config('holidays.holidays', [])) !!};
        const cutiBersama = {!! json_encode(config('holidays.cuti_bersama', [])) !!};
        const allHolidays = [...new Set([...holidays, ...cutiBersama])].sort();

        let currentDate = dayjs();
        const masterCutis = {!! json_encode($masterCutis->pluck('available_days', 'id')->merge($masterCutis->pluck('days', 'id'))) !!};

        function renderCalendar() {
            const year = currentDate.year();
            const month = currentDate.month();
            const firstDay = dayjs(new Date(year, month, 1)).day();
            const daysInMonth = dayjs(new Date(year, month + 1, 0)).date();

            document.getElementById('monthYear').textContent = currentDate.format('MMMM YYYY');

            let html = '<div class="grid grid-cols-7 gap-1 text-xs">';
            html += '<div class="font-semibold text-center text-gray-600">Sun</div>';
            html += '<div class="font-semibold text-center text-gray-600">Mon</div>';
            html += '<div class="font-semibold text-center text-gray-600">Tue</div>';
            html += '<div class="font-semibold text-center text-gray-600">Wed</div>';
            html += '<div class="font-semibold text-center text-gray-600">Thu</div>';
            html += '<div class="font-semibold text-center text-gray-600">Fri</div>';
            html += '<div class="font-semibold text-center text-gray-600">Sat</div>';

            for (let i = 0; i < firstDay; i++) {
                html += '<div></div>';
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(year, month, day);
                const dateStr = dayjs(dateObj).format('YYYY-MM-DD');
                const isHoliday = allHolidays.includes(dateStr);
                const isWeekend = dayjs(dateObj).day() === 0 || dayjs(dateObj).day() === 6;
                const isInRange = isDateInRange(dateStr);

                // Only highlight business days in the range with indigo
                const isBusinessDayInRange = isInRange && !isWeekend && !isHoliday;

                let bgClass = 'bg-white text-gray-900';
                if (isBusinessDayInRange) {
                    bgClass = 'bg-indigo-500 text-white border border-indigo-600 font-semibold';
                } else if (isInRange && isWeekend) {
                    bgClass = 'bg-gray-300 text-gray-900 border border-gray-400'; // Keep weekend color
                } else if (isInRange && isHoliday) {
                    bgClass = 'bg-red-200 text-gray-900 border border-red-400'; // Keep holiday color
                } else if (isHoliday) {
                    bgClass = 'bg-red-200 text-gray-900 border border-red-400';
                } else if (isWeekend) {
                    bgClass = 'bg-gray-300 text-gray-900 border border-gray-400';
                }

                html += `<button type="button" class="p-2 text-center rounded ${bgClass} cursor-pointer hover:opacity-75 transition" onclick="selectDate('${dateStr}')" title="${isHoliday ? 'Holiday' : isWeekend ? 'Weekend' : ''}">${day}</button>`;
            }
            html += '</div>';

            document.getElementById('calendarContainer').innerHTML = html;
            updateHolidaysList();
        }

        function isDateInRange(dateStr) {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            
            if (!start || !end) return false;
            
            const current = dayjs(dateStr);
            const startDate = dayjs(start);
            const endDate = dayjs(end);
            
            return current.isSameOrAfter(startDate) && current.isSameOrBefore(endDate);
        }

        function selectDate(dateStr) {
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');

            if (!startInput.value) {
                startInput.value = dateStr;
            } else if (!endInput.value) {
                if (dateStr >= startInput.value) {
                    endInput.value = dateStr;
                } else {
                    endInput.value = startInput.value;
                    startInput.value = dateStr;
                }
            } else {
                startInput.value = dateStr;
                endInput.value = '';
            }

            // Trigger calculation & re-render after date change
            calculateDays();
            renderCalendar();
        }

        function calculateDays() {
            const startStr = document.getElementById('start_date').value;
            const endStr = document.getElementById('end_date').value;

            if (!startStr || !endStr) {
                document.getElementById('days_requested').value = 0;
                return;
            }

            const start = dayjs(startStr);
            const end = dayjs(endStr);
            let count = 0;

            for (let date = start; date.isSameOrBefore(end); date = date.add(1, 'day')) {
                const dateStr = date.format('YYYY-MM-DD');
                const isWeekend = date.day() === 0 || date.day() === 6;
                const isHoliday = allHolidays.includes(dateStr);

                if (!isWeekend && !isHoliday) {
                    count++;
                }
            }

            document.getElementById('days_requested').value = count;
            renderCalendar();
        }

        function updateAvailableDays() {
            const select = document.getElementById('master_cuti_id');
            const selectedOption = select.options[select.selectedIndex];
            const days = selectedOption.getAttribute('data-days') || 0;
            
            if (days > 0) {
                document.getElementById('availableDaysInfo').innerHTML = `<span class="text-indigo-600 font-semibold">✓ Available: ${days} days</span>`;
            } else {
                document.getElementById('availableDaysInfo').innerHTML = `<span class="text-gray-500">Select a leave type to see available days</span>`;
            }
        }

        function updateHolidaysList() {
            const year = currentDate.year();
            const month = currentDate.month();
            const monthHolidays = allHolidays.filter(h => {
                const hDate = dayjs(h);
                return hDate.year() === year && hDate.month() === month;
            }).slice(0, 5);

            let html = '';
            if (monthHolidays.length > 0) {
                html = monthHolidays.map(h => `<div class="text-gray-600 text-xs">📌 ${dayjs(h).format('DD MMM (ddd)')}</div>`).join('');
            } else {
                html = '<div class="text-gray-500 text-xs">No holidays this month</div>';
            }
            document.getElementById('holidaysList').innerHTML = html;
        }

        // Event listeners for calendar navigation
        document.getElementById('prevMonth').addEventListener('click', function(e) {
            e.preventDefault();
            currentDate = currentDate.subtract(1, 'month');
            renderCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', function(e) {
            e.preventDefault();
            currentDate = currentDate.add(1, 'month');
            renderCalendar();
        });

        // Event listeners untuk input date fields
        document.getElementById('start_date').addEventListener('change', function() {
            calculateDays();
        });

        document.getElementById('end_date').addEventListener('change', function() {
            calculateDays();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
            updateAvailableDays();
            
            // Jika ada old values, hitung days_requested
            const startVal = document.getElementById('start_date').value;
            const endVal = document.getElementById('end_date').value;
            if (startVal && endVal) {
                calculateDays();
            }
        });
    </script>
    @endpush
</x-app-layout>