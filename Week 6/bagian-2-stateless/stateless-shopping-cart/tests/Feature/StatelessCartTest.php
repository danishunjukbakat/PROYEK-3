<?php

namespace Tests\Feature;

use Tests\TestCase;

class StatelessCartTest extends TestCase
{
    public function test_catalog_and_expected_total(): void
    {
        $this->get('/stateless-shop')->assertOk()->assertSee('Stateless Shopping Cart');
        $this->postJson('/cart/add', ['cart' => [1 => 2], 'product_id' => 2])
            ->assertOk()->assertJsonPath('total', 13000)
            ->assertJsonPath('items.0.subtotal', 10000)->assertJsonPath('items.1.quantity', 1);
    }

    public function test_replay_is_deterministic_and_session_cart_is_untouched(): void
    {
        $payload = ['cart' => [1 => 2], 'product_id' => 2];
        $first = $this->withSession(['keranjang' => [3 => 7]])->postJson('/cart/add', $payload);
        $first->assertSessionHas('keranjang', [3 => 7]);
        $this->postJson('/cart/add', $payload)->assertExactJson($first->json());
        $this->postJson('/cart/update', ['cart' => []])->assertExactJson(['items' => [], 'total' => 0]);
    }

    public function test_prices_from_client_are_ignored(): void
    {
        $this->postJson('/cart/update', ['cart' => [1 => 2], 'price' => 1, 'total' => 2])
            ->assertOk()->assertJsonPath('items.0.price', 5000)->assertJsonPath('total', 10000);
    }

    public function test_invalid_payloads_are_rejected(): void
    {
        foreach ([[], ['cart' => 'bad'], ['cart' => [1 => 0]], ['cart' => [1 => -1]],
            ['cart' => [1 => 1.5]], ['cart' => [1 => 100]], ['cart' => [999 => 1]],
            ['cart' => ['01' => 1]]] as $payload) {
            $this->postJson('/cart/update', $payload)->assertUnprocessable();
        }
        $this->postJson('/cart/add', ['cart' => [], 'product_id' => 999])->assertNotFound();
        $this->postJson('/cart/add', ['cart' => [1 => 99], 'product_id' => 1])
            ->assertOk()->assertJsonPath('items.0.quantity', 99);
    }
}
