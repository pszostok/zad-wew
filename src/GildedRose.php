<?php

namespace App;

use App\Item;

/**
 * Class GildedRose
 * 
 * This class handles the processing of items in the Gilded Rose inventory system.
 * It updates the quality and sell-in values of items based on predefined rules.
 */
final class GildedRose
{
    /**
     * Update the quality and sell-in value of an item.
     * 
     * This method processes an item's quality and sell-in values according to the type of item.
     * If the sell-in date is past, it handles expired item logic as well.
     *
     * @param Item $item
     * 
     * @return void
     */
    public function updateQuality(Item $item): void
    {
        $this->processQuality($item);
        $this->processSellIn($item);

        if ($item->getSellIn() < 0) {
            $this->handleExpiredItem($item);
        }
    }

    /**
     * Process the quality of an item based on its type.
     * 
     * This method determines the type of item and calls the appropriate update function for that item type.
     * 
     * @param Item $item
     * 
     * @return void
     */
    protected function processQuality(Item $item): void
    {
        switch ($item->getName()) {
            case 'Aged Brie':
                $this->updateAgedBrie($item);
                break;

            case 'Backstage passes to a TAFKAL80ETC concert':
                $this->updateBackstagePasses($item);
                break;

            case 'Sulfuras, Hand of Ragnaros':
                break; // Sulfuras doesn't change its quality

            default:
                $this->updateNormalItem($item);
                break;
        }
    }

    /**
     * Process the sell-in value of an item.
     * 
     * @param Item $item
     * 
     * @return void
     */
    protected function processSellIn(Item $item): void
    {
        if ($item->getName() !== 'Sulfuras, Hand of Ragnaros') {
            $item->changeSellIn(-1);
        }
    }

    /**
     * Update a normal item's quality.
     * 
     * @param Item $item
     * 
     * @return void
     */
    protected function updateNormalItem(Item $item): void
    {
        if ($item->getQuality() > $item->getMinQuality()) {
            $item->changeQuality(-1);
        }
    }

    /**
     * Update the quality of 'Aged Brie'.
     * 
     * 'Aged Brie' increases in quality as it gets older. The quality increases faster when the sell-in value
     * is less than 11 or 6 days.
     * 
     * @param Item $item
     * 
     * @return void
     */
    protected function updateAgedBrie(Item $item): void
    {
        if ($item->getQuality() < $item->getMaxQuality()) {
            if ($item->getSellIn() < 11) {
                $item->changeQuality(1);
            }

            if ($item->getQuality() >= $item->getMaxQuality()) {
                return;
            }

            if ($item->getSellIn() < 6) {
                $item->changeQuality(1);
            }
        }
    }

    /**
     * Update the quality of 'Backstage passes to a TAFKAL80ETC concert'.
     * 
     * 'Backstage passes' increase in quality as the concert date approaches, with a faster increase
     * as the sell-in value gets lower.
     * 
     * @param Item $item
     * 
     * @return void
     */
    protected function updateBackstagePasses(Item $item): void
    {
        if ($item->getQuality() < $item->getMaxQuality()) {
            $item->changeQuality(1);

            if ($item->getSellIn() < 11) {
                $item->changeQuality(1);
            }

            if ($item->getSellIn() < 6) {
                $item->changeQuality(1);
            }
        }
    }

    /**
     * Handle the expiration of an item.
     * 
     * Once an item has expired (sell-in value is less than 0), its quality is updated according to the item type.
     * 
     * @param Item $item The expired item whose quality needs to be handled.
     * 
     * @return void
     */
    protected function handleExpiredItem(Item $item): void
    {
        if ($item->getName() === 'Aged Brie') {
            return;
        } 
        
        if ($item->getName() === 'Backstage passes to a TAFKAL80ETC concert') {
            $item->setQuality(0);

            return;
        } 

        if ($item->getQuality() > $item->getMinQuality()) {
            $item->changeQuality(-1);
        }
    }
}