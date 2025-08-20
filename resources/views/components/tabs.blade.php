@props([
    'tabs' => [],
    'activeTabClass' => '',
    'inactiveTabClass' => '',
    'contentClass' => '',
])

<div x-data="{ activeTab: '{{ $defaultTab ?? array_key_first($tabs) }}' }">
    <div class="flex space-x-2 -mb-px ml-2">
        @foreach ($tabs as $key => $label)
            <button
                x-on:click="activeTab = '{{ $key }}'"
                x-bind:class="{
                    'bg-white dark:bg-dark-800 text-gray-800 dark:text-emerald-500 border-l border-r border-t border-border-300 dark:border-dark-700 {{$activeTabClass}}': activeTab === '{{ $key }}',
                    'text-dark-500 dark:hover:text-emerald-700 {{ $inactiveTabClass }}': activeTab !== '{{ $key }}'
                }"
                class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>
    <div class="card {{ $contentClass }}">
        <div class="">
            @foreach ($tabs as $key => $label)
            <div x-show="activeTab === '{{ $key }}'">
                {{-- Use the variable whose name matches the slot key --}}
                {{ ${$key} }}
            </div>
            @endforeach
        </div>
    </div>
</div>
