<?php
namespace Tests\Unit;
use App\Models\Cucian;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CucianTest extends TestCase
{
    #[Test]
    public function test_cucian_exists()
    {
        $cucian = Cucian::factory()->create();
        $this->assertNotNull($cucian->cucian_id);
    }
}
