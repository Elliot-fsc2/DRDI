<div>
    <div class="space-y-6">
        <!-- Header with Create Button -->
        <div class="flex items-center justify-between">
            {{ $this->createConsultationAction }}
        </div>

        <!-- Consultations List -->
        @if ($this->consultations->count() > 0)
            <div class="grid gap-4">
                @foreach ($this->consultations as $consultation)
                    <div
                        class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex-1">
                                <!-- Date, Status and Instructor -->
                                <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($consultation->consultation_date)->format('M d, Y') }}
                                        </span>
                                    </div>

                                    @php
                                        $statusColors = [
                                            'scheduled' =>
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'completed' =>
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'cancelled' =>
                                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            'rescheduled' =>
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        ];
                                        $statusColor =
                                            $statusColors[$consultation->status] ??
                                            'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
                                    @endphp

                                    <span
                                        class="{{ $statusColor }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                        {{ ucfirst($consultation->status) }}
                                    </span>
                                </div>

                                <!-- Instructor Name -->
                                <div class="mb-3 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Scheduled by: {{ $consultation->instructor->user->name }}</span>
                                </div>

                                <!-- Details Grid -->
                                <div class="grid gap-3 sm:grid-cols-2">
                                    @if ($consultation->location)
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ $consultation->location }}</span>
                                        </div>
                                    @endif

                                    @if ($consultation->consultation_method)
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ ucwords(str_replace('_', ' ', $consultation->consultation_method)) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Student Concerns -->
                                @if ($consultation->student_concerns)
                                    <div class="mt-3">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Student Concerns:
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $consultation->student_concerns }}</p>
                                    </div>
                                @endif

                                <!-- Instructor Feedback -->
                                @if ($consultation->instructor_feedback)
                                    <div class="mt-3">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Instructor
                                            Feedback:</h4>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $consultation->instructor_feedback }}</p>
                                    </div>
                                @endif

                                <!-- Next Consultation -->
                                @if ($consultation->next_consultation_date)
                                    <div class="mt-3 flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Next:
                                            {{ \Carbon\Carbon::parse($consultation->next_consultation_date)->format('M d, Y \a\t g:i A') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            @if ($consultation->instructor_id === auth()->user()->instructor->id)
                                <div class="flex items-center gap-2 sm:flex-col">
                                    {{ ($this->editConsultationAction)(['consultation' => $consultation->id]) }}
                                    {{ ($this->deleteConsultationAction)(['consultation' => $consultation->id]) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div
                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No consultations scheduled</h3>
                <p class="mb-4 text-gray-600 dark:text-gray-400">Schedule your first consultation with this group.</p>
                {{ $this->createConsultationAction }}
            </div>
        @endif
    </div>

    <x-filament-actions::modals />
</div>
