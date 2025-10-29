<x-filament-panels::page>
    <div>
        <section class="space-y-6">
            <header class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
                <div class="mb-2 flex w-full items-center justify-start sm:mb-0 sm:w-auto">
                    {{-- {{ $this->createAction }} --}}
                </div>
                <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                    <div class="w-full sm:w-72">
                        <select wire:model.live="selectedSection"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
                            <option value="">All sections</option>
                            @foreach ($this->availableSections as $sectionId => $sectionName)
                                <option value="{{ $sectionId }}">{{ $sectionName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-72">
                        <select wire:model.live="selectedRole"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
                            <option value="">All roles</option>
                            @foreach ($this->availableRoles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}">{{ $roleLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </header>

            <!-- grid of group cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($this->groups ?? [] as $group)
                    {{-- Render the Livewire card component for each Group model --}}
                    <a wire:key="group-{{ $group->id }}" wire:navigate
                        href="{{ route('filament.instructor.pages.assigned-groups.{group}.proposals', $group) }}">
                        <livewire:group-cards-assigned :group="$group" :key="'group-card-' . $group->id" />
                    </a>
                @empty
                    <div class="col-span-full rounded-lg bg-white p-12 shadow-sm dark:bg-gray-800">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-3-3h-4a3 3 0 00-3 3v2m5-12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No groups found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if ($this->selectedSection || $this->selectedRole)
                                    No groups found for the selected filters.
                                @else
                                    You don't have any assigned groups yet.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($this->groups->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    {{ $this->groups->links() }}
                </div>
            @endif
        </section>
        <x-filament-actions::modals />
    </div>

</x-filament-panels::page>
