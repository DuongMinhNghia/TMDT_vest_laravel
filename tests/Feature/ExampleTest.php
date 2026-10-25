<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Kiểm tra trang đăng nhập công khai phản hồi thành công 200.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/dang-nhap');

        $response->assertStatus(200);
    }
}
