@if( isset($domain) && $domain->custom_styles )
    <style>
        {!! $domain->custom_styles !!}
    </style>
@endif

@if( isset($funnel) && $funnel->custom_styles )
    <style>
        {!! $funnel->custom_styles !!}
    </style>
@endif

@if( isset($page) && $page->custom_styles )
    <style>
        {!! $page->custom_styles !!}
    </style>
@endif
