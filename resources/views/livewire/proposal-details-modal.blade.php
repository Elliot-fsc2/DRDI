<div class="space-y-6">
    {{-- Proposal Information Section --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Proposal Information</h3>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $proposal->title }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <div class="mt-1">
                    @php
                        $statusColors = [
                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                            'revision' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                            'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                            'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                        ];
                        $statusColor =
                            $statusColors[$proposal->status] ??
                            'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
                    @endphp
                    <span
                        class="{{ $statusColor }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                        {{ ucfirst($proposal->status) }}
                    </span>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Final Proposal</label>
                <div class="mt-1 flex items-center">
                    @if ($proposal->is_final)
                        <svg class="mr-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-green-600 dark:text-green-400">Yes</span>
                    @else
                        <svg class="mr-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400">No</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Description Section --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Description</h3>
        <div class="prose prose-sm dark:prose-invert max-w-none">
            {!! $proposal->description !!}
        </div>
    </div>

    {{-- Feedback & Remarks Section --}}
    @if ($proposal->remarks)
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Instructor Remarks</h3>
            <div class="text-gray-700 dark:text-gray-300">
                {!! nl2br(e($proposal->remarks)) !!}
            </div>
        </div>
    @endif

    {{-- Timeline Section --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Timeline</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Submitted At</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $proposal->created_at->format('M d, Y g:i A') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Updated</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $proposal->updated_at->format('M d, Y g:i A') }}</p>
            </div>
        </div>
    </div>
</div>
