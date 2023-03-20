@if ($posts)

    @collection([
        'unbox' => true,
        'classList' => ['o-grid']
    ])
        @foreach ($posts as $post)
            @collection__item([
                'link' => $post->permalink,
                'classList' => [$gridColumnClass]
            ])
                @group(['direction' => 'horizontal', 'classList' => ['o-grid', 'u-padding--0']])
                @if ($displayFeaturedImage)
                    @image([
                        'src' => $post->thumbnail['src'],
                        'alt' => $post->thumbnail['alt'],
                        'placeholderIconSize' => 'sm',
                        'placeholderIcon' => 'image',
                        'placeholderText' => '',
                        'classList' => ['o-grid-4@sm', 'o-grid-12', 'u-height--100']
                    ])
                    @endimage
                    @group(['direction' => 'vertical', 'classList' => ['o-grid-8@sm', 'o-grid-12']])
                    @else
                        @group(['direction' => 'vertical', 'classList' => ['o-grid-12']])
                        @endif
                        @typography([
                            'element' => 'h3',
                            'classList' => ['c-collection__content__title']
                        ])
                            {{ $post->postTitle }}
                        @endtypography
                        @if ($post->termsUnlinked)
                            @typography([
                                'element' => 'p',
                                'variant' => 'meta',
                                'classList' => ['c-collection__content__terms']
                            ])
                                @foreach ($post->termsUnlinked as $term)
                                    @typography([
                                        'element' => 'span',
                                        'variant' => 'meta',
                                        'classList' => ['c-collection__content__term', 'u-margin__right--1']
                                    ])
                                        {{ $term['label'] }}
                                    @endtypography
                                @endforeach
                            @endtypography
                        @endif
                        @if ($displayReadingTime && $post->readingTime)
                            @typography([
                                'element' => 'p',
                                'variant' => 'meta',
                                'classList' => ['c-collection__content__reading-time']
                            ])
                                {{ $post->readingTime }}
                            @endtypography
                        @endif
                        @typography([
                            'element' => 'p',
                            'classList' => ['c-collection__content__excerpt']
                        ])
                            {{ $post->excerptShort }}
                        @endtypography
                    @endgroup
                @endgroup
            @endcollection__item
        @endforeach
    @endcollection
@endif