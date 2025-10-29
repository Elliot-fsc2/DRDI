<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-0">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-white">
                Proposals for {{ $group->name }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Review and manage proposal status for this group
            </p>
        </div>
    </div>

    {{-- Proposals List --}}
    @if ($proposals->count() > 0)
        <div class="grid gap-4">
            @foreach ($proposals as $proposal)
                <div wire:click="mountAction('viewProposal', { proposal: {{ $proposal->id }} })"
                    class="group cursor-pointer rounded-lg border border-gray-200 bg-white p-4 transition-all duration-200 hover:border-gray-300 hover:shadow-md sm:p-6 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-0">
                        <div class="flex-1">
                            <div class="mb-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                                <h3
                                    class="group-hover:text-primary-600 dark:group-hover:text-primary-400 text-base font-medium leading-tight text-gray-900 transition-colors sm:text-lg dark:text-white">
                                    {{ Str::limit(strip_tags($proposal->title), 30) }}
                                </h3>

                                @if ($proposal->is_final)
                                    <span
                                        class="inline-flex w-fit items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Final
                                    </span>
                                @endif
                            </div>

                            <p class="mb-3 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ Str::limit(strip_tags($proposal->description), 30) }}
                            </p>

                            <div
                                class="flex flex-col gap-2 text-xs text-gray-500 sm:flex-row sm:items-center sm:gap-4 dark:text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $proposal->created_at->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $proposal->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 sm:ml-4 sm:flex-col sm:items-end">
                            {{-- Status Badge --}}
                            @php
                                $statusColors = [
                                    'approved' =>
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'revision' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
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

                            {{-- Edit Icon --}}
                            <svg class="group-hover:text-primary-500 h-5 w-5 text-gray-400 transition-colors sm:mt-2"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="py-12 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No proposals submitted yet</h3>
            <p class="text-gray-600 dark:text-gray-400">This group hasn't submitted any proposals yet.</p>
        </div>
    @endif

    {{-- Action Modals --}}
    <x-filament-actions::modals />
</div>
