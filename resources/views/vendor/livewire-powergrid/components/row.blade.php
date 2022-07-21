@inject('helperClass','PowerComponents\LivewirePowerGrid\Helpers\Helpers')

@foreach($columns as $column)
    @php
        $content = $row->{$column->field};
        $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
        $field   = $column->dataField != '' ? $column->dataField : $column->field;
    @endphp
    <td class="{{ $theme->table->tdBodyClass . ' '.$column->bodyClass ?? '' }}"
        style="{{ $column->hidden === true ? 'display:none': '' }}; {{ $theme->table->tdBodyStyle . ' '.$column->bodyStyle ?? '' }}"
    >
        @if($column->editable === true && !str_contains($field, '.'))
            <span class="{{ $theme->clickToCopy->spanClass }}">
                @include($theme->editable->view)
                @if($column->clickToCopy)
                    <x-livewire-powergrid::click-to-copy
                        :row="$row"
                        :field="$content"
                        :label="data_get($column->clickToCopy, 'label') ?? null"
                        :enabled="data_get($column->clickToCopy, 'enabled') ?? false"/>
                @endif
            </span>
        @elseif(count($column->toggleable) > 0)
            @include($theme->toggleable->view, ['tableName' => $tableName])
        @else
            <span class="@if($column->clickToCopy) {{ $theme->clickToCopy->spanClass }} @endif">
                    <div>
                        {!! $content !!}
                    </div>
                    @if($column->clickToCopy)
                        <x-livewire-powergrid::click-to-copy
                            :row="$row"
                            :field="$content"
                            :label="data_get($column->clickToCopy, 'label') ?? null"
                            :enabled="data_get($column->clickToCopy, 'enabled') ?? false"/>
                    @endif
            </span>
        @endif
    </td>
@endforeach

