<div>
    <section class="space-y-6">
        <header class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
            <div class="mb-2 flex w-full items-center justify-start sm:mb-0 sm:w-auto">
                {{ $this->createAction }}
            </div>
            <div class="w-full sm:w-72">
                <div>
                    <div class="relative flex items-center">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                            </svg>
                        </span>

                        <input type="search" wire:model.live.debounce.300ms="search"
                            placeholder="search students by name, ID, email or course" aria-label="Search students"
                            class="block w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-12 pr-5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 rtl:pl-5 rtl:pr-11 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
                    </div>
                </div>
            </div>
        </header>

        <!-- grid of student cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($students ?? [] as $student)
                {{-- Render the Livewire card component for each Student model --}}
                <a wire:navigate wire:key="student-{{ $student->id }}"
                    href="{{ \App\Filament\Instructor\Pages\ManageStudentsDetails::getUrl(['student' => $student]) }}"
                    class="block h-full transition-transform duration-200 hover:scale-105">
                    <livewire:student-card :student="$student" :key="'student-card-' . $student->id" />
                </a>
            @empty
                <div class="col-span-full rounded-lg bg-white p-12 shadow-sm dark:bg-gray-800">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No students found
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if ($search)
                                No students match your search criteria.
                            @else
                                No students are available yet.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-6 flex items-center justify-center">
            {{ $students->links() }}
        </div>
    </section>
    <x-filament-actions::modals />
</div>
