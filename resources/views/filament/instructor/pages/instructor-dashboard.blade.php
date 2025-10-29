<x-filament-panels::page>
    <div class="mx-auto max-w-6xl space-y-6">

        {{-- Layout: announcements centered column, stats sidebar on the right for md+ --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            {{-- Announcements in a centered column (md) --}}
            <div class="md:col-span-2 md:col-start-2">
                <div class="space-y-6">
                    {{-- Keep a small header for the feed --}}
                    <div class="py-2 text-center md:text-left">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Department News</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Latest announcements from the department.</p>
                    </div>

                    {{-- Announcements feed will render below (kept where it was) --}}
                    {{-- Announcements Feed (reuse style from news page) --}}
                    <div class="space-y-4">
                        @forelse($this->getAnnouncements() as $announcement)
                            <div
                                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-shadow duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                                @if ($announcement->is_pinned)
                                    <div
                                        class="flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-orange-500 px-4 py-2 text-sm font-medium text-white">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 2L13 7h5l-4 4 1.5 5L10 13l-5.5 3L6 11 2 7h5l3-5z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Pinned Announcement
                                    </div>
                                @endif

                                <div class="p-6">
                                    <div class="mb-4 flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-sm font-semibold text-white">
                                            {{ substr($announcement->author->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $announcement->author->name }}</div>
                                            <div
                                                class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $announcement->published_at_for_humans }}
                                            </div>
                                        </div>
                                    </div>

                                    <h2 class="mb-3 text-xl font-bold leading-tight text-gray-900 dark:text-white">
                                        {{ $announcement->title }}</h2>

                                    <div class="prose prose-gray dark:prose-invert max-w-none">
                                        {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($announcement->content)->toHtml() !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
                                <div
                                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">No announcements
                                    yet</h3>
                                <p class="text-gray-600 dark:text-gray-400">Check back later for the latest news and
                                    updates from
                                    the department.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar: stat cards --}}
            <aside class="md:col-span-1 md:col-start-4">
                <div class="space-y-6">
                    {{-- Current Academic Year card --}}
                    <div
                        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Current Academic Year
                                </div>
                                <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ optional($this->getCurrentAcademicYear())->year ?? 'Not set' }}</div>
                            </div>
                            <div
                                class="ml-4 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Handled Sections summary card --}}
                    <div
                        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Handled Sections</div>
                                <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $this->getHandledSections()->count() }}</div>
                                <div class="text-xs text-gray-500">sections in the active year</div>
                            </div>
                            <div
                                class="ml-4 flex h-10 w-10 items-center justify-center rounded-full bg-green-500 text-white">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-6a3 3 0 013-3h0a3 3 0 013 3v6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 21h14" />
                                </svg>
                            </div>
                        </div>

                        {{-- Optionally list small preview of sections --}}
                        @if ($this->getHandledSections()->isNotEmpty())
                            <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                @foreach ($this->getHandledSections()->take(5) as $section)
                                    <li class="flex items-center justify-between">
                                        <div class="truncate">{{ $section->name }} @if ($section->course)
                                                &middot; <span
                                                    class="text-xs text-gray-500">{{ $section->course->code ?? ($section->course->name ?? '') }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $section->students()->count() }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </aside>
        </div>


    </div>
</x-filament-panels::page>
