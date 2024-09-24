<?php

namespace Municipio\ExternalContent\UI;

use Municipio\HooksRegistrar\Hookable;
use WP_Post;
use WpService\Contracts\AddFilter;
use WpService\Contracts\NonceUrl;

/**
 * Class PageRowActionsSyncButton
 *
 * This class adds a sync button to the page and post row actions in the WordPress admin.
 */
class PageRowActionsSyncButton implements Hookable
{
    /**
     * PageRowActionsSyncButton constructor.
     *
     * @param \Municipio\ExternalContent\Config\SourceConfig[] $sourceConfigs
     * @param AddFilter&NonceUrl $wpService
     */
    public function __construct(private array $sourceConfigs, private AddFilter&NonceUrl $wpService)
    {
    }

    /**
     * @inheritDoc
     */
    public function addHooks(): void
    {
        $this->wpService->addFilter('page_row_actions', array($this, 'addSyncButton'), 10, 2);
        $this->wpService->addFilter('post_row_actions', array($this, 'addSyncButton'), 10, 2);
    }

    /**
     * Adds a sync button to the row actions for pages and posts.
     *
     * @param array $actions The existing row actions.
     * @param WP_Post $post The current post object.
     * @return array The modified row actions.
     */
    public function addSyncButton(array $actions, WP_Post $post): array
    {
        $postTypeHasExternalContentSource = array_filter(
            $this->sourceConfigs,
            fn($config) => $config->getPostType() === $post->post_type
        );

        if (empty($postTypeHasExternalContentSource)) {
            return $actions;
        }

        $urlParams = sprintf(
            '&%s&%s=%s',
            \Municipio\ExternalContent\Sync\Triggers\TriggerSyncFromGetParams::GET_PARAM_TRIGGER,
            \Municipio\ExternalContent\Sync\Triggers\TriggerSyncFromGetParams::GET_PARAM_POST_ID,
            $post->ID
        );

        $url = $this->wpService->nonceUrl($_SERVER['REQUEST_URI'] ?? '') . $urlParams;

        $actions[\Municipio\ExternalContent\Sync\Triggers\TriggerSyncFromGetParams::GET_PARAM_TRIGGER] = sprintf(
            '<a href="%s">%s</a>',
            $url,
            __('Sync this post from remote source', 'municipio')
        );

        return $actions;
    }
}