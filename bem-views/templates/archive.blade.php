@extends('templates.master')


@section('before-layout')

    @if (get_field('archive_' . sanitize_title($postType) . '_filter_position', 'option') == 'top')
        @include('partials.archive-filters')
    @endif

@stop

@section('above')
    @include('components.breadcrumbs')
@stop

@section('sidebar-left')
    @include('components.dynamic-sidebar', ['id' => 'left-sidebar'])

    @if (get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option'))
    {!! $navigation['sidebarMenu'] !!}
    @endif

    @include('components.dynamic-sidebar', ['id' => 'left-sidebar-bottom'])
@stop

@section('content')
    @include('components.dynamic-sidebar', ['id' => 'content-area-top'])

    @include('partials.archive.archive-title')

    @if (have_posts())
        <div class="s-archive s-archive-template-{{sanitize_title($template)}}  s-archive-{{sanitize_title($postType)}} grid u-mt-5" @if (apply_filters('archive_equal_container', false, $postType, $template)) data-equal-container @endif>

            @if (get_field('archive_' . sanitize_title($postType) . '_filter_position', 'option') == 'content')
                @include('partials.archive-filters')
            @endif


            <?php $postNum = 0; ?>
            @while(have_posts())
                {!! the_post() !!}
                <div class="grid-xs-12 u-mb-4 {{ $grid_size }}">
                    @includeIf('partials.archive.post.post-' . $template)
                </div>
                <?php $postNum++; ?>
            @endwhile
        </div>
    @else
        <div class="notice info pricon pricon-info-o pricon-space-right"><?php _e('No posts to show', 'municipio'); ?>…</div>
    @endif

    @include('components.dynamic-sidebar', ['id' => 'content-area'])

            <div class="grid">
                <div class="grid-sm-12 text-center">
                    {!!
                        paginate_links(array(
                            'type' => 'list'
                        ))
                    !!}
                </div>
            </div>
@stop

@section('sidebar-right')
    @include('components.dynamic-sidebar', ['id' => 'right-sidebar'])
@stop

@section('below')
    @include('components.dynamic-sidebar', ['id' => 'content-area-bottom'])
@stop
