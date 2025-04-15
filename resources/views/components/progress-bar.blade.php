@props(['percentage'])

<div {{ $attributes->merge(['class' => 'w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700']) }}>
    <div class="bg-emerald-400 h-1.5 rounded-full dark:bg-emerald-500" style="width: {{ $percentage }}%"></div>
</div>
