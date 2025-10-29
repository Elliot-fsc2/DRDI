<x-filament-panels::page>
    <div class="max-w-4xl space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                📝 Create New Announcement
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Create an announcement that will be visible to all instructors
            </p>
        </div>

        <form wire:submit="create" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end gap-4 border-t border-gray-200 pt-6 dark:border-gray-700">
                <x-filament::button tag="a" href="{{ route('filament.instructor.pages.news') }}" color="gray"
                    outlined>
                    <x-slot name="icon">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                    </x-slot>
                    Cancel
                </x-filament::button>

                <x-filament::button type="submit" color="primary">
                    <x-slot name="icon">
                        <x-heroicon-o-plus class="h-4 w-4" />
                    </x-slot>
                    Create Announcement
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
