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
                            placeholder="search groups by name" aria-label="Search groups by name"
                            class="block w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-12 pr-5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 rtl:pl-5 rtl:pr-11 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
                    </div>
                </div>
            </div>
        </header>

        <!-- grid of group cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($groups ?? [] as $group)
                {{-- Render the Livewire card component for each Group model --}}
                <a wire:navigate wire:key="group-{{ $group->id }}"
                    href="{{ route('filament.instructor.pages.my-sections.{section}.{group}', ['section' => $section, 'group' => $group]) }}">
                    <livewire:group-cards :group="$group" :key="'group-card-' . $group->id" />
                </a>
            @empty
                <div class="col-span-full rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400">No groups found.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6 flex items-center justify-center">
            {{ $groups->links() }}
        </div>
    </section>
    <x-filament-actions::modals />
</div>
