<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class NumbersTest extends TestCase
{
    public function test_example(): void
    {
        $sum = 50 + 50;

        $this->assertEquals(100, $sum); // очікуємо правильний результат

        $this->assertNotEquals(101, $sum); // очікуємо неправильний результат
    }
}
