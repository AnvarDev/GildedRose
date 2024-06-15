<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testDateIsPassedAndMinQuality(): void
    {
        $items = [new Item('Item', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);
    }

    public function testMaxQuality(): void
    {
        $items = [new Item('Backstage passes', 3, 52)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(2, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality);
    }

    public function testAgedBrie(): void
    {
        $items = [new Item('Aged Brie', 5, 1)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(2, $items[0]->quality);
    }

    public function testSulfuras(): void
    {
        $items = [new Item('Sulfuras', 2, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(80, $items[0]->quality);
    }

    public function testConjured(): void
    {
        $items = [new Item('Conjured', 2, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(1, $items[0]->sellIn);
        $this->assertSame(8, $items[0]->quality);
    }

    public function testBackstagePasses(): void
    {
        $items = [
            new Item('Backstage passes', 0, 15),
            new Item('Backstage passes', 4, 10),
            new Item('Backstage passes', 10, 5),
            new Item('Backstage passes', 12, 3),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);

        $this->assertSame(3, $items[1]->sellIn);
        $this->assertSame(13, $items[1]->quality);

        $this->assertSame(9, $items[2]->sellIn);
        $this->assertSame(7, $items[2]->quality);

        $this->assertSame(11, $items[3]->sellIn);
        $this->assertSame(2, $items[3]->quality);
    }
}
