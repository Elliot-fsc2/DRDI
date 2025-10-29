<div>
    <section class="space-y-6">
        <!-- Page Header -->
        <div class="rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 p-6 shadow-sm">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    {{ $instructor->full_name }}'s Sections
                </h2>
                <p class="mt-1 text-sm text-blue-100">
                    {{ $instructor->department->name ?? 'No Department' }} •
                    {{ $sections->count() }} {{ Str::plural('section', $sections->count()) }}
                </p>
            </div>
        </div>

        <!-- Sections Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($sections ?? [] as $section)
                <div
                    class="h-full rounded-lg border border-gray-100 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex h-full flex-col">
                        <!-- Section Header -->
                        <div class="flex-1">
                            <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">
                                {{ $section->name }}
                            </h3>
                            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $section->course->code }} - {{ $section->course->name }}
                            </p>
                        </div>

                        <!-- Section Stats -->
                        <div
                            class="flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-700">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mr-1 h-4 w-4 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <span class="font-medium">{{ $section->students_count ?? 0 }}</span>
                                <span class="ml-1">students</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mr-1 h-4 w-4 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 110-8 4 4 0 010 8z" />
                                </svg>
                                <span class="font-medium">{{ $section->groups_count ?? 0 }}</span>
                                <span class="ml-1">groups</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-lg bg-white p-12 text-center shadow-sm dark:bg-gray-800">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">No sections assigned</h3>
                    <p class="text-gray-500 dark:text-gray-400">This instructor hasn't been assigned to any sections
                        yet.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
