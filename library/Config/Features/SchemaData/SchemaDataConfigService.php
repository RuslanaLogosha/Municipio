<?php

namespace Municipio\Config\Features\SchemaData;

use WpService\Contracts\GetOption;
use WpService\Contracts\GetOptions;

class SchemaDataConfigService implements SchemaDataConfigInterface
{
    private ?array $settings = null;

    public function __construct(private GetOption&GetOptions $wpService)
    {
    }

    private function getSettings(): array
    {
        $groupName           = 'options_post_type_schema_types';
        $postTypeFieldName   = 'post_type';
        $schemaTypeFieldName = 'schema_type';
        $nbrOfRows           = $this->wpService->getOption($groupName);
        $nbrOfRows           = intval($nbrOfRows);

        if ($nbrOfRows === 0) {
            return [];
        }

        $settings    = [];
        $optionNames = array_map(fn($index) => "{$groupName}_{$index}_{$postTypeFieldName}", range(0, $nbrOfRows - 1));
        $optionNames = array_merge($optionNames, array_map(fn($index) => "{$groupName}_{$index}_{$schemaTypeFieldName}", range(0, $nbrOfRows - 1)));
        $options     = $this->wpService->getOptions($optionNames);

        for ($i = 0; $i < $nbrOfRows; $i++) {
            $postType   = $options["{$groupName}_{$i}_{$postTypeFieldName}"];
            $schemaType = $options["{$groupName}_{$i}_{$schemaTypeFieldName}"];
            $settings[] = ['post_type' => $postType, 'schema_type' => $schemaType];
        }

        if (is_null($this->settings)) {
            $this->settings =  !is_array($settings) ? [] : $settings;
        }

        return $this->settings;
    }

    public function featureIsEnabled(): bool
    {
        $enabledValue = $this->wpService->getOption('options_mun_schemadata_enabled', false);
        return $enabledValue !== true || $enabledValue === "1" || $enabledValue == 1;
    }

    public function getEnabledPostTypes(): array
    {
        $postTypes = array_map(fn($row) => $row['post_type'] ?? null, $this->getSettings() ?? []);
        return array_filter($postTypes);
    }

    public function tryGetSchemaTypeFromPostType(string $postType): ?string
    {
        foreach ($this->getSettings() as $row) {
            if ($row['post_type'] === $postType) {
                return $row['schema_type'];
            }
        }

        return null;
    }
}