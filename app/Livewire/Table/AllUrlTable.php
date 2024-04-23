<?php

namespace App\Livewire\Table;

use App\Helpers\Helper;
use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * @codeCoverageIgnore
 */
final class AllUrlTable extends PowerGridComponent
{
    const STR_LIMIT = 60;

    public bool $showUpdateMessages = true;

    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount('full'),
        ];
    }

    public function datasource(): Builder
    {
        return Url::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('user_name', function (Url $url) {
                return '<span class="font-semibold">'.$url->author->name.'</span>';
            })
            ->add('keyword', function (Url $url) {
                return '<a href="'.$url->short_url.'" target="_blank"class="font-light text-sky-800">'.$url->keyword.'</a>';
            })
            ->add('destination', function (Url $url) {
                return
                    '<span title="'.htmlspecialchars($url->title).'">'
                        .htmlspecialchars(Str::limit($url->title, self::STR_LIMIT)).
                    '</span>
                    <br>
                    <a href="'.$url->destination.'" target="_blank" title="'.$url->destination.'" rel="noopener noreferrer"
                        class="text-[#6c6c6c]"
                    >'
                        .Helper::urlDisplay($url->destination, self::STR_LIMIT).
                    '</a>';
            })
            ->add('t_clicks', function (Url $url) {
                $uClick = numberAbbreviate($url->uniqueClicks);
                $tClick = numberAbbreviate($url->clicks);
                $icon = Blade::render('@svg(\'icon-bar-chart\', \'ml-2 text-amber-600\')');
                $title = $uClick.' '.__('Uniques').' / '.$tClick.' '.__('Clicks');

                return '<div title="'.$title.'">'.$uClick.' / '.$tClick.$icon.'</div>';
            })
            ->add('created_at_formatted', function (Url $url) {
                return
                    '<span title="'.$url->created_at->toDayDateTimeString().'">'
                        .$url->created_at->shortRelativeDiffForHumans().
                    '</span>';
            })
            ->add('action', function (Url $url) {
                return
                    '<a role="button" href="'.route('su_detail', $url->keyword).'" target="_blank" title="'.__('Open front page').'"
                        class="btn btn-secondary btn-sm"
                    >'
                        .Blade::render('@svg(\'icon-open-in-new\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.su_edit', $url).'" title="'.__('Edit').'"
                        class="btn btn-secondary btn-sm"
                    >'
                        .Blade::render('@svg(\'icon-edit-alt\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.su_delete', $url).'" title="'.__('Delete').'"
                        class="btn btn-secondary btn-sm hover:text-red-600 active:text-red-700"
                    >'
                        .Blade::render('@svg(\'icon-trash-alt\')').
                    '</a>';
            });
    }

    /**
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Owner', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Short URL', 'keyword')
                ->sortable()
                ->searchable(),

            Column::make('Destination URL', 'destination')
                ->sortable()
                ->searchable(),
            Column::make('title', 'title')
                ->searchable()
                ->hidden(),

            Column::make('CLICKS', 't_clicks')
                ->bodyAttribute(styleAttr: ';padding-left: 8px'),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make('ACTIONS', 'action')
                ->bodyAttribute(styleAttr: ';padding-left: 8px'),
        ];
    }
}
