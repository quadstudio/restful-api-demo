<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImageTest extends TestCase
{

	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function a_images_table_has_expected_columns()
	{

		$this->assertTrue(
			Schema::hasColumns('images', [
				'id','path', 'width', 'height', 'storage'
			]), 1);
	}

}
