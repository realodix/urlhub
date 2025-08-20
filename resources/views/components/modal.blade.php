@props(['show' => false, 'title' => '', 'maxWidth' => '2xl'])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl', // Default
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
][$maxWidth];
@endphp

<div
    x-data="{ showModal: @json($show) }"
    x-show="showModal"
    x-on:open-modal.window="$event.detail == '{{ $attributes->get('name', 'default-modal') }}' ? showModal = true : null"
    x-on:close-modal.window="$event.detail == '{{ $attributes->get('name', 'default-modal') }}' ? showModal = false : null"
    x-on:keydown.escape.window="showModal = false"
    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center"
    style="display: none;" {{-- Hide initially to prevent flash --}}
>
    {{-- Background Overlay --}}
    <div
        x-show="showModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="showModal = false" {{-- Close on overlay click --}}
        class="fixed inset-0 bg-gray-50/50 bg-opacity-75 dark:bg-dark-950/50 transition-opacity"
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="showModal"
        x-trap.inert.noscroll="showModal" {{-- Trap focus, make background inert, prevent body scroll --}}
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white dark:bg-dark-900 border border-border-200 dark:border-dark-700 rounded-lg shadow-xl transform transition-all sm:my-8 sm:w-full {{ $maxWidthClasses }} p-6"
    >
        {{-- Close Button --}}
        <button
            aria-label="Close modal"
            x-on:click="showModal = false"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
        >
            @svg('icon-close', 'size-4 text-gray-500 dark:text-red-400')
        </button>

        @if ($title)
            <div class="text-lg font-medium leading-6 text-gray-900 dark:text-dark-100 mb-4">
                {{ $title }}
            </div>
        @endif

        <div>
            {{ $slot }}
        </div>
    </div>
</div>
