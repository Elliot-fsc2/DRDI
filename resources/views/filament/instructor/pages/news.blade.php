<x-filament-panels::page>
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="py-6 text-center">
            <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                📰 Department News
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Stay updated with the latest announcements and news
            </p>

            {{-- Create Announcement Button --}}
            @if (auth()->user()->instructor?->roles()->where('name', 'DRDI Head')->exists())
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('filament.instructor.pages.news.create') }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-600 dark:hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Create Announcement
                    </a>
                </div>
            @endif
        </div>

        {{-- Announcements Feed --}}
        <div class="space-y-4">
            @forelse($this->getAnnouncements() as $announcement)
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-shadow duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    {{-- Pinned Badge --}}
                    @if ($announcement->is_pinned)
                        <div
                            class="flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-orange-500 px-4 py-2 text-sm font-medium text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L13 7h5l-4 4 1.5 5L10 13l-5.5 3L6 11 2 7h5l3-5z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Pinned Announcement
                        </div>
                    @endif

                    {{-- Post Content --}}
                    <div class="p-6">
                        {{-- Author Info --}}
                        <div class="mb-4 flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-sm font-semibold text-white">
                                {{ substr($announcement->author->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $announcement->author->name }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $announcement->published_at_for_humans }}
                                </div>
                            </div>
                        </div>

                        {{-- Announcement Title --}}
                        <h2 class="mb-3 text-xl font-bold leading-tight text-gray-900 dark:text-white">
                            {{ $announcement->title }}
                        </h2>

                        {{-- Announcement Content --}}
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($announcement->content)->toHtml() !!}
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                        No announcements yet
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Check back later for the latest news and updates from the department.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
