<?php

// Modul 4 Bagian 2: katalog server menjadi sumber kebenaran harga.

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StatelessCartController extends Controller
{
    private const PRODUCTS = [
        1 => ['name' => 'Buku Tulis', 'price' => 5000],
        2 => ['name' => 'Pulpen', 'price' => 3000],
        3 => ['name' => 'Penggaris', 'price' => 4000],
    ];

    public function index(): View
    {
        return view('stateless.shop', ['products' => self::PRODUCTS]);
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart' => ['present', 'array', 'max:3'],
            'cart.*' => ['integer', 'min:1', 'max:99'],
            'product_id' => ['required', 'integer'],
        ]);
        $cart = $this->validateProductIds($data['cart']);
        $id = (int) $data['product_id'];
        abort_unless(isset(self::PRODUCTS[$id]), 404, 'Produk tidak ditemukan.');
        $cart[$id] = min(99, ($cart[$id] ?? 0) + 1);

        return response()->json($this->calculate($cart));
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart' => ['present', 'array', 'max:3'],
            'cart.*' => ['integer', 'min:1', 'max:99'],
        ]);

        return response()->json($this->calculate($this->validateProductIds($data['cart'])));
    }

    private function validateProductIds(array $cart): array
    {
        foreach ($cart as $id => $quantity) {
            if (!in_array((string) $id, ['1', '2', '3'], true)) {
                throw ValidationException::withMessages(['cart' => 'ID produk keranjang tidak valid.']);
            }
            $cart[$id] = (int) $quantity;
        }
        ksort($cart);

        return $cart;
    }

    private function calculate(array $cart): array
    {
        $items = [];
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $product = self::PRODUCTS[$id];
            $subtotal = $product['price'] * $quantity;
            $items[] = [
                'id' => (int) $id, 'name' => $product['name'],
                'price' => $product['price'], 'quantity' => $quantity, 'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return ['items' => $items, 'total' => $total];
    }
}
