<?php

namespace Tests\Unit\Controllers\PostController;

use App\Http\Controllers\PostController;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\PostController
 * @covers \App\Http\Controllers\PostController::index
 */
class IndexTest extends TestCase
{
    public function testSuccess(): void
    {
        $postController = new PostController();
        $view = $postController->index();
        $this->assertEquals('home', $view->name());
    }
}
