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
                            placeholder="search sections by name or course" aria-label="Search sections"
                            class="block w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-12 pr-5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 rtl:pl-5 rtl:pr-11 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
                    </div>
                </div>
            </div>
        </header>

        <!-- grid of section cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($sections ?? [] as $section)
                {{-- Render the Livewire card component for each Section model --}}
                <a wire:navigate wire:key="section-{{ $section->id }}"
                    href="{{ route('filament.instructor.pages.my-sections.{section}', ['section' => $section]) }}">
                    <livewire:section-cards :section="$section" :key="'section-card-' . $section->id" />
                </a>
            @empty
                <div class="col-span-full rounded-lg bg-white p-12 shadow-sm dark:bg-gray-800">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No sections found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if ($search)
                                No sections match your search criteria.
                            @else
                                You don't have any sections yet.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($sections->hasPages())
            <div class="mt-6 flex items-center justify-center">
                {{ $sections->links() }}
            </div>
        @endif
    </section>
    <x-filament-actions::modals />
</div>
