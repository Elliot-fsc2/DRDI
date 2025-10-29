<div class="h-full">
    {{-- Section card displaying section details --}}
    <div class="flex h-full w-full flex-col overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <!-- photo -->
        <img class="h-48 w-full object-cover sm:h-56 md:h-64"
            src="https://www.shutterstock.com/image-photo/book-open-pages-close-up-600nw-2562942291.jpg"
            alt="Section photo">

        <div class="flex flex-1 flex-col p-6">
            <header class="flex flex-col justify-between sm:flex-row sm:items-start">
                <!-- left: section name and course info -->
                <div class="min-w-0 flex-1">
                    <h3 class="break-words text-xl font-semibold text-gray-800 dark:text-white">{{ $section->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                        {{ $section->course->code }}
                    </p>
                </div>

                <!-- right: student count and academic year -->
                <div class="mt-3 flex flex-col items-end gap-2 whitespace-nowrap sm:mt-0">
                    <span
                        class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-200"
                        aria-hidden="true">
                        <svg class="mr-1 h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        {{ $section->students_count ?? $section->students()->count() }}
                        <span class="sr-only">students</span>
                    </span>

                    <span
                        class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200"
                        role="status">
                        {{ $section->academicYear->year }}
                    </span>
                </div>
            </header>

            <!-- Academic year info -->
            <div class="mt-4">

            </div>

            <!-- Footer with instructor info -->
            <div class="mt-auto flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full object-cover"
                        src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png"
                        alt="Instructor avatar">
                    <div class="ml-3 text-sm">
                        <p class="font-medium text-gray-700 dark:text-gray-200">
                            {{ $section->instructor?->full_name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Instructor</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
