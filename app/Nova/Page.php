<?php

namespace App\Nova;

use App\Nova\Filters\FunnelFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;

class Page extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'url',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Name')->sortable()->rules('required'),
            Text::make('Url', function () {
                $this->load('funnel');
                $this->load('domain');
                $url = 'https://' . $this->domain->domain_name . '/' . $this->funnel->url . '/' . $this->url;

                return '<a target="_blank" href="' . $url . '">' . $url . '</a>';
            })->asHtml(),
            Text::make('Url')->hideFromIndex()->rules(['required']),
            BelongsTo::make('Template', 'template')->sortable(),
            BelongsTo::make('Funnel', 'funnel')->sortable(),
            Code::make('Custom Scripts')->language('javascript'),
            Code::make('Custom Styles')->language('sass'), // CSS not support by default, sass should give us the same highlighting rules
            Text::make('Price', 'price')->hideFromIndex(),
            Text::make('Secondary Price', 'price_two')->hideFromIndex(),
            Text::make('Custom Content One', 'custom_content_one')->hideFromIndex(),
            Text::make('Custom Content Two', 'custom_content_two')->hideFromIndex(),
            BelongsTo::make('Checkout Page', 'checkout', 'App\Nova\Page')->hideFromIndex()->nullable(),
            Text::make('Buy Button Link', 'buy_button_one')->hideFromIndex(),
            Text::make('Secondary Buy Button Link', 'buy_button_two')->hideFromIndex(),
            Text::make('No Thanks Link', 'no_thanks_link')->hideFromIndex(),
            Text::make('Banner Text', 'banner_text')->sortable(),
            Boolean::make('Has Timer'),
            DateTime::make('Expires On', 'timer_timestamp')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new FunnelFilter
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
