<div
    class="h-full w-full overflow-hidden rounded-lg bg-white shadow-md transition-shadow duration-300 hover:shadow-lg dark:bg-gray-800">
    <div class="p-6">
        <!-- Circular Profile Image -->
        <div class="mb-4 flex justify-center">
            <img class="h-24 w-24 rounded-full border-4 border-gray-200 object-cover dark:border-gray-600"
                src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png"
                alt="{{ $instructor->full_name ?? 'Instructor' }} avatar">
        </div>

        <!-- Instructor Information -->
        <div class="space-y-3 text-center">
            <!-- Name -->
            <h3 class="break-words text-xl font-bold text-gray-800 dark:text-white">
                {{ $instructor->full_name ?? 'Unknown Instructor' }}
            </h3>

            <!-- Email -->
            <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
                <span class="truncate">{{ $instructor->user->email ?? 'No email' }}</span>
            </div>

            <!-- Department -->
            <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-center">{{ $instructor->department->name ?? 'No department' }}</span>
            </div>

            <!-- Roles -->
            <div class="mt-4 flex flex-wrap justify-center gap-2">
                @if ($instructor->roles && $instructor->roles->count() > 0)
                    @foreach ($instructor->roles as $role)
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $role->name }}
                        </span>
                    @endforeach
                @else
                    <span
                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Instructor
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
