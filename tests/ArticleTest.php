<?php

namespace Tests;

use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ArticleTest extends TestCase
{
    public function test_that_endpoint_returns_a_successful_response()
    {
        $response = $this->call('GET', '/articles');

        $this->assertEquals(200, $response->status());
    }

    public function test_that_base_endpoint_returns_an_exact_json_response()
    {
        $response = $this->call('GET', '/base/articles');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'total_pages', 'total', 'per_page', 'page'])
        );
    }

    public function test_that_author_filter_returns_a_successful_response()
    {
        $author = 'epaga';

        $response = $this->call('GET', "/articles?author={$author}");

        $content = $response->json();

        $this->assertArrayHasKey('data', $content);

        foreach ($content['data'] as $item) {
            $this->assertEquals($author, $item['author']);
        }
    }

    public function test_that_results_has_a_valid_title()
    {
        $response = $this->call('GET', '/articles');

        $content = $response->json();

        $this->assertArrayHasKey('data', $content);

        foreach ($content['data'] as $item) {
            $this->assertNotNull($item['title']);
            $this->assertTrue(strlen($item['title']) > 0);
        }        
    }
}