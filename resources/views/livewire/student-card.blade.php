<div
    class="h-full w-full overflow-hidden rounded-lg bg-white shadow-md transition-shadow duration-300 hover:shadow-lg dark:bg-gray-800">
    <div class="p-6">
        <!-- Circular Profile Image -->
        <div class="mb-4 flex justify-center">
            <img class="h-24 w-24 rounded-full border-4 border-gray-200 object-cover dark:border-gray-600"
                src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png"
                alt="{{ $student->full_name ?? 'Student' }} avatar">
        </div>

        <!-- Student Information -->
        <div class="space-y-3 text-center">
            <!-- Name -->
            <h3 class="break-words text-xl font-bold text-gray-800 dark:text-white">
                {{ $student->full_name ?? 'Unknown Student' }}
            </h3>

            <!-- Student ID -->
            <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                </svg>
                <span class="font-medium">{{ $student->student_number ?? 'No ID' }}</span>
            </div>

            <!-- Email -->
            <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
                <span class="truncate">{{ $student->user->email ?? 'No email' }}</span>
            </div>

            <!-- Course -->
            <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="text-center">{{ $student->course->code ?? 'No course' }}</span>
            </div>

            <!-- Status/Groups -->
            <div class="mt-4 flex flex-wrap justify-center gap-2">
                @if ($student->groups && $student->groups->count() > 0)
                    <span
                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        In Group
                    </span>
                @else
                    <span
                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        No Group
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
