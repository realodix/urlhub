<div class="mb-2">
    <a href="{{ $model->short_url }}" title="{{ $model->keyword }}" target="_blank" class="bg-primary-50 dark:bg-dark-800 text-primary-800 dark:text-emerald-500 font-light p-1 rounded">
        {{ mb_strimwidth($model->keyword, 0, 15, '...') }}
    </a>
</div>
