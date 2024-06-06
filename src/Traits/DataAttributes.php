<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\TagAbstract;
use Wongyip\HTML\Utils\Convert;

/**
 * Data-attributes manipulation trait.
 *
 * @note Experimental feature.
 * @see TagAbstract::open()
 */
trait DataAttributes
{
    /**
     * Internal storage of data attributes.
     *
     * @var array|string[]
     */
    protected array $dataset = [];

    /**
     * Get or set a single element in the dataset.
     *
     * @param string $name
     * @param float|int|string|null $value
     * @return float|int|string|DataAttributes|null
     */
    public function data(string $name, float|int|string $value = null): float|int|string|null|static
    {
        // Normalize
        $name = Convert::camel($name);
        //Get
        if (is_null($value)) {
            return $this->dataset[$name] ?? null;
        }
        // Set
        $this->dataset[$name] = $value;
        return $this;
    }

    /**
     * Retrieve entire dataset as tag attributes (no setter).
     *
     * E.g. Data ['toggle' => 'model', 'targetElement' => 'message_box'] returns
     * ['data-toggle' => 'modal', 'data-target-element' => 'message_box'].
     *
     * @return array
     */
    public function dataAttributes(): array
    {
        return $this->dataset(null, static::DATASET_ATTRS);
    }

    /**
     * Retrieve entire dataset as JSON string (no setter).
     *
     * @return string
     */
    public function datasetJSON(): string
    {
        return json_encode($this->dataset(null, static::DATASET_JSON));
    }

    /**
     * Get or replace the entire dataset.
     *
     *  - Default $namingScheme is camel case.
     *
     * @param array|null $dataset
     * @param int|null $namingScheme
     * @return array|static
     * @uses TagAbstract::DATASET_DEFAULT
     * @uses TagAbstract::DATASET_CAMEL
     * @uses TagAbstract::DATASET_KEBAB
     * @uses TagAbstract::DATASET_ATTRS
     * @uses TagAbstract::DATASET_JSON
     */
    public function dataset(array $dataset = null, int $namingScheme = null) : array|static
    {
        // Get all
        if (is_null($dataset)) {
            return match ($namingScheme) {
                static::DATASET_ATTRS => Convert::keysCase($this->dataset, 'kebab', 'data-'),
                static::DATASET_KEBAB => Convert::keysCase($this->dataset, 'kebab'),
                default               => Convert::keysCase($this->dataset, 'camel'),
            };
        }
        // Set all (replace)
        if (isset($namingScheme)) {
            error_log('Setting of dataset with $namingScheme provided, which is ignore.');
        }
        // Store with normalized names.
        $this->dataset = Convert::keysCase($dataset, 'camel');
        return $this;
    }

    /**
     * Check if an element found in the dataset.
     *
     * @param string $name
     * @return bool
     */
    public function hasData(string $name): bool
    {
        return key_exists(Convert::camel($name), $this->dataset);
    }
}