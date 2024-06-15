<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var int $minQuality
     */
    private $minQuality = 0;

    /**
     * @var int $maxQuality
     */
    private $maxQuality = 50;

    /**
     * @var int $noLimitSellIn
     */
    private $noLimitSellIn = -1;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isSulfuras(Item $item): bool
    {
        if (str_contains($item->name, 'Sulfuras')) {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isAgedBrie(Item $item): bool
    {
        if ($item->name == 'Aged Brie') {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isBackstagePasses(Item $item): bool
    {
        if (str_contains($item->name, 'Backstage passes')) {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isConjured(Item $item): bool
    {
        if (str_contains($item->name, 'Conjured')) {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isDatePassed(Item $item): bool
    {
        if ($item->sellIn < 0) {
            return true;
        }

        return false;
    }

    /**
     * @param Item $item
     * @return void
     */
    private function setSellIn(Item &$item): void
    {
        if ($this->isSulfuras($item)) {
            $item->sellIn = $this->noLimitSellIn;

            return;
        }

        $item->sellIn -= 1;
    }

    /**
     * @param Item $item
     * @param int $quality
     * @param bool $exact
     * @return void
     */
    private function setQuality(Item &$item, int $quality = 0, bool $exact = false): void
    {
        if ($quality < $this->minQuality) {
            $item->quality = $this->minQuality;

            return;
        }

        if ($quality > $this->maxQuality && $exact === false) {
            $item->quality = $this->maxQuality;

            return;
        }

        $item->quality = $quality;
    }

    /**
     * @return void
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->setSellIn($item);

            if ($this->isSulfuras($item)) {
                $this->setQuality($item, 80, true);

                continue;
            }

            if ($this->isAgedBrie($item)) {
                $this->setQuality($item, $item->quality + 1);

                continue;
            }

            if ($this->isConjured($item)) {
                $this->setQuality($item, $item->quality - 2);

                continue;
            }

            if ($this->isBackstagePasses($item)) {
                if ($item->sellIn < 0) {
                    $this->setQuality($item, 0);

                    continue;
                }

                if ($item->sellIn <= 5) {
                    $this->setQuality($item, $item->quality + 3);

                    continue;
                }

                if ($item->sellIn <= 10) {
                    $this->setQuality($item, $item->quality + 2);

                    continue;
                }
            }

            if ($this->isDatePassed($item)) {
                $this->setQuality($item, $item->quality - 2);
            } else {
                $this->setQuality($item, $item->quality - 1);
            }
        }
    }
}
