<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex h-full w-full flex-col overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <!-- photo (kept as requested) -->
        <img class="h-48 w-full object-cover sm:h-56 md:h-64"
            src="https://static.vecteezy.com/system/resources/thumbnails/028/133/374/small/school-classroom-in-blur-background-without-young-student-blurry-view-of-elementary-class-room-no-kid-or-teacher-with-chairs-and-tables-in-campus-back-to-school-concept-generative-ai-photo.jpg"
            alt="Group photo">

        <div class="flex flex-1 flex-col p-6">
            <header class="flex flex-col justify-between sm:flex-row sm:items-start">
                <!-- left: allow truncation when space is limited -->
                <div class="min-w-0 flex-1">
                    <h3 class="truncate text-xl font-semibold text-gray-800 dark:text-white">{{ $group->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Section:
                        {{ $group->section->name }}</p>
                </div>

                <!-- right: compact badges, stacked on small screens -->
                <div class="mt-3 flex items-center gap-2 whitespace-nowrap sm:mt-0">
                    <span
                        class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200"
                        aria-hidden="true">
                        <svg class="mr-1 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 110-8 4 4 0 010 8z" />
                        </svg>
                        {{ $group->members_count }}
                        <span class="sr-only">members</span>
                    </span>

                    <span
                        class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200"
                        role="status" aria-label="Active">
                        Active
                    </span>
                </div>
            </header>

            <!-- Description / placeholder -->
            <div class="mt-4"></div>

            <!-- Footer with simple actions (non-functional placeholders) -->
            <div class="mt-auto flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full object-cover"
                        src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png"
                        alt="Group avatar">
                    <div class="ml-3 text-sm">
                        <p class="font-medium text-gray-700 dark:text-gray-200">
                            {{ $group->section->instructor?->full_name }}e
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Research Adviser</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
