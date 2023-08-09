@if( isset($domain) && $domain->custom_scripts )
    <script>
        {!! $domain->custom_scripts !!}
    </script>
@endif


@if( isset($funnel) && $funnel->custom_scripts )
    <script>
        <!--  Funnel Scripts -->
        {!! $funnel->custom_scripts !!}
    </script>
@endif

@if( isset($page) && $page->custom_scripts )
    <script>
        {!! $page->custom_scripts !!}
    </script>
@endif
