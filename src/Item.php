<?php

namespace App;

use Exception;

/**
 * Class Item
 * 
 * Represents an item in the inventory system, with attributes such as name, sell-in value, and quality.
 * Provides methods to get, set, and change these attributes, as well as retrieve item-specific configuration.
 */
final class Item
{
    /**
     * @param string $name The name of the item.
     * @param int $sellIn The sell-in value of the item (number of days remaining before the item expires).
     * @param int $quality The quality of the item.
     */
    public function __construct(protected string $name, protected int $sellIn, protected int $quality)
    {}

    /**
     * Getters and setters
     */

    public function getName(): string
    {
        return $this->name;
    }

    public function getSellIn(): string
    {
        return $this->sellIn;
    }

    public function setSellIn(int $sellIn): void
    {
        $this->sellIn = $sellIn;
    }

    public function getQuality(): string
    {
        return $this->quality;
    }

    public function setQuality(int $quality): void
    {
        $this->quality = $quality;
    }

    /**
     * Change the sell-in value of the item by a specified number of points.
     * 
     * @param int $points The number of points to adjust the sell-in value by (positive or negative).
     * 
     * @return void
     */
    public function changeSellIn(int $points): void
    {
        $this->sellIn += $points;
    }

    /**
     * Change the quality value of the item by a specified number of points.
     * 
     * @param int $points The number of points to adjust the quality by (positive or negative).
     * 
     * @return void
     */
    public function changeQuality(int $points): void
    {
        $this->quality += $points;
    }

    /**
     * Get the minimum quality for the item.
     * 
     * Retrieves the minimum quality value for the item from the configuration.
     * If the configuration does not exist, it uses a default value.
     * 
     * @return int The minimum quality value for the item.
     * 
     * @throws Exception If the configuration file is missing.
     */
    public function getMinQuality(): int
    {
        $config = $this->getConfig();
        $handle = 'default';

        if (isset($config[$this->getName()]) && isset($config[$this->getName()]['min_quality'])) {
            $handle = $this->getName();
        }

        return (int) $config[$handle]['min_quality'];
    }

    /**
     * Get the maximum quality for the item.
     * 
     * Retrieves the maximum quality value for the item from the configuration.
     * If the configuration does not exist, it uses a default value.
     * 
     * @return int The maximum quality value for the item.
     * 
     * @throws Exception If the configuration file is missing.
     */
    public function getMaxQuality(): int
    {
        $config = $this->getConfig();
        $handle = 'default';

        if (isset($config[$this->getName()]) && isset($config[$this->getName()]['max_quality'])) {
            $handle = $this->getName();
        }

        return (int) $config[$handle]['max_quality'];
    }

    public function __toString(): string
    {
        $allowedProperties = [
            'name',
            'sellIn',
            'quality'
        ];

        return implode(', ', array_map(fn ($property) => $this->{$property}, $allowedProperties));
    }

    /**
     * Retrieve the configuration for the item.
     * 
     * The configuration is fetched from a PHP file located at '/config/foodConfig.php'.
     * The configuration should define 'min_quality' and 'max_quality' for different item types.
     * 
     * @return array The configuration array for the items.
     * 
     * @throws Exception If the configuration file is missing.
     */
    private function getConfig(): array
    {
        $filePath = __DIR__ . '/../config/foodConfig.php';
        
        if (file_exists($filePath)) {
            return include($filePath);
        }

        throw new Exception('foodConfig is missing');
    }
}