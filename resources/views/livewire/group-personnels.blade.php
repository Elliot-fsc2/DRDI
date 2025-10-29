<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-0">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-white">
                Personnel Assignment for {{ $group->name }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage instructors and their roles for this group
            </p>
        </div>

        <div class="flex-shrink-0">
            {{ $this->assignPersonnelAction }}
        </div>
    </div>

    {{-- Personnel List --}}
    @if ($personnels->count() > 0)
        <div class="grid gap-4">
            @foreach ($personnels as $personnel)
                <div
                    class="group rounded-lg border border-gray-200 bg-white p-4 transition-all duration-200 hover:border-gray-300 hover:shadow-md sm:p-6 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-0">
                        <div class="flex-1">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                                {{-- Instructor Info --}}
                                <div class="flex items-center gap-3">
                                    <div
                                        class="bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 flex h-10 w-10 items-center justify-center rounded-full">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900 sm:text-lg dark:text-white">
                                            {{ $personnel->instructor->user->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                            {{ $personnel->instructor->user->email }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Role Badge --}}
                                @php
                                    $roleColors = [
                                        'technical_adviser' =>
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'language_critic' =>
                                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'statistician' =>
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                        'grammarian' =>
                                            'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                    ];
                                    $roleColor =
                                        $roleColors[$personnel->role] ??
                                        'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';

                                    $roleLabels = [
                                        'technical_adviser' => 'Technical Adviser',
                                        'language_critic' => 'Language Critic',
                                        'statistician' => 'Statistician',
                                        'grammarian' => 'Grammarian',
                                    ];
                                    $roleLabel =
                                        $roleLabels[$personnel->role] ??
                                        ucfirst(str_replace('_', ' ', $personnel->role));
                                @endphp

                                <span
                                    class="{{ $roleColor }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                    {{ $roleLabel }}
                                </span>
                            </div>

                            {{-- Assignment Date --}}
                            <div class="mt-2 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Assigned on {{ $personnel->created_at->format('M d, Y') }}
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-2 pt-2 sm:pt-0">
                            <button wire:click="mountAction('editPersonnel', { personnel: {{ $personnel->id }} })"
                                class="inline-flex items-center rounded-md bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50">
                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                            <button wire:click="mountAction('removePersonnel', { personnel: {{ $personnel->id }} })"
                                class="inline-flex items-center rounded-md bg-red-50 px-3 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="py-12 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No personnel assigned yet</h3>
            <p class="mb-4 text-gray-600 dark:text-gray-400">Get started by assigning instructors to this group.</p>
            <div class="flex justify-center">
                {{ $this->assignPersonnelAction }}
            </div>
        </div>
    @endif

    {{-- Action Modals --}}
    <x-filament-actions::modals />
</div>
