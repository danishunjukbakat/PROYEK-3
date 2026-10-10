<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Product;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Tests\TestCase;
class ShopTest extends TestCase {
    use RefreshDatabase;
    protected function setUp(): void {parent::setUp();$this->seed();}
    private function budi(): User {return User::where('username','budi')->firstOrFail();}
    private function sari(): User {return User::where('username','sari')->firstOrFail();}
    private function fillCart(): void {
        $this->actingAs($this->budi());
        $this->post('/keranjang',['id_barang'=>'BRG001']);$this->post('/keranjang',['id_barang'=>'BRG001']);$this->post('/keranjang',['id_barang'=>'BRG002']);
    }
    private function checkout(): string {
        $r=$this->post('/checkout',['alamat_pengiriman'=>'Jalan Contoh 1, Bandung']);$r->assertSessionHasNoErrors();
        $id=DB::table('orders')->value('id_order');$r->assertRedirect('/pesanan/'.$id);return $id;
    }
    public function test_catalog_has_ten_products_and_cart_requires_login(): void {
        $this->assertDatabaseCount('products',10);$this->get('/barang')->assertOk();
        foreach(['/keranjang','/checkout','/pesanan'] as $url) $this->get($url)->assertRedirect('/login');
        $this->post('/keranjang',['id_barang'=>'BRG001'])->assertRedirect('/login');
        $this->post('/checkout',['alamat_pengiriman'=>'Bandung'])->assertRedirect('/login');
    }
    public function test_login_and_logout_work_with_string_user_id(): void {
        $this->post('/login',['username'=>'budi','password'=>'rahasia123'])->assertRedirect('/barang');$this->assertAuthenticatedAs($this->budi());
        $this->get('/login')->assertRedirect('/barang');$this->post('/logout')->assertRedirect('/login');$this->assertGuest();
    }
    public function test_cart_and_checkout_pages_render(): void {$this->fillCart();$this->get('/keranjang')->assertOk()->assertSee('13.000');$this->get('/checkout')->assertOk()->assertSee('13.000');}
    public function test_cart_edit_zero_remove_and_clear(): void {
        $this->fillCart();$this->patch('/keranjang/BRG001',['jumlah'=>3])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('cart_items',['id_user'=>'USR001','id_barang'=>'BRG001','jumlah_beli'=>3]);
        $this->patch('/keranjang/BRG001',['jumlah'=>0]);$this->assertDatabaseMissing('cart_items',['id_user'=>'USR001','id_barang'=>'BRG001']);
        $this->delete('/keranjang/BRG002');$this->assertDatabaseCount('cart_items',0);
        $this->post('/keranjang',['id_barang'=>'BRG001']);$this->delete('/keranjang');$this->assertDatabaseCount('cart_items',0);
    }
    public function test_invalid_and_out_of_stock_quantities_fail(): void {
        $this->actingAs($this->budi())->post('/keranjang',['id_barang'=>'BRG010'])->assertSessionHasErrors('jumlah');
        $this->post('/keranjang',['id_barang'=>'BAD'])->assertSessionHasErrors('id_barang');
        $this->post('/keranjang',['id_barang'=>'BRG001']);
        foreach([999,-1,'1.5'] as $qty) $this->patch('/keranjang/BRG001',['jumlah'=>$qty])->assertSessionHasErrors('jumlah');
        $this->assertDatabaseHas('cart_items',['id_user'=>'USR001','id_barang'=>'BRG001','jumlah_beli'=>1]);
    }
    public function test_checkout_creates_order_details_reduces_stock_and_clears_cart(): void {
        $this->fillCart();$id=$this->checkout();
        $this->assertDatabaseCount('orders',1);$this->assertDatabaseCount('order_details',2);$this->assertDatabaseCount('cart_items',0);
        $this->assertDatabaseHas('orders',['id_order'=>$id,'id_user'=>'USR001','total_harga'=>13000]);
        $this->assertDatabaseHas('order_details',['id_order'=>$id,'id_barang'=>'BRG001','harga_satuan'=>5000,'jumlah_beli'=>2]);
        $this->assertDatabaseHas('products',['id_barang'=>'BRG001','stok'=>18]);$this->assertDatabaseHas('products',['id_barang'=>'BRG002','stok'=>29]);
        $this->get('/pesanan')->assertOk()->assertSee($id);$this->get('/pesanan/'.$id)->assertOk()->assertSee('13.000');
    }
    public function test_browser_cannot_choose_price_total_or_owner(): void {
        $this->fillCart();$this->post('/checkout',['alamat_pengiriman'=>'Jalan Contoh 1','total_harga'=>1,'harga_satuan'=>1,'id_user'=>'USR002'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders',['id_user'=>'USR001','total_harga'=>13000]);$this->assertDatabaseMissing('orders',['id_user'=>'USR002']);
    }
    public function test_empty_and_repeat_checkout_do_not_create_extra_orders(): void {
        $this->actingAs($this->budi())->post('/checkout',['alamat_pengiriman'=>'Jalan Contoh'])->assertSessionHasErrors('checkout');$this->assertDatabaseCount('orders',0);
        $this->fillCart();$this->checkout();$this->post('/checkout',['alamat_pengiriman'=>'Jalan Contoh'])->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders',1);$this->assertDatabaseHas('products',['id_barang'=>'BRG001','stok'=>18]);
    }
    public function test_stock_change_rejects_checkout_without_partial_changes(): void {
        $this->fillCart();Product::whereKey('BRG002')->update(['stok'=>0]);
        $this->post('/checkout',['alamat_pengiriman'=>'Jalan Contoh'])->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders',0);$this->assertDatabaseCount('order_details',0);$this->assertDatabaseCount('cart_items',2);
        $this->assertDatabaseHas('products',['id_barang'=>'BRG001','stok'=>20]);
    }
    public function test_order_history_keeps_price_snapshot(): void {
        $this->fillCart();$id=$this->checkout();Product::whereKey('BRG001')->update(['harga'=>'99999.00']);
        $this->get('/pesanan/'.$id)->assertOk()->assertSee('5.000')->assertDontSee('99.999');
        $this->assertDatabaseHas('orders',['id_order'=>$id,'total_harga'=>13000]);
    }
    public function test_each_account_has_its_own_cart_and_orders(): void {
        $this->fillCart();$id=$this->checkout();$this->actingAs($this->sari());
        $this->get('/pesanan/'.$id)->assertNotFound();$this->get('/pesanan')->assertOk()->assertDontSee($id);
        $this->post('/keranjang',['id_barang'=>'BRG003']);$this->actingAs($this->budi());
        $this->patch('/keranjang/BRG003',['jumlah'=>2])->assertNotFound();$this->delete('/keranjang');
        $this->assertDatabaseHas('cart_items',['id_user'=>'USR002','id_barang'=>'BRG003','jumlah_beli'=>1]);
    }
    public function test_database_error_rolls_back_order_detail_stock_and_cart(): void {
        $this->fillCart();
        // SQLite trigger memaksa kegagalan detail kedua sesudah detail pertama + pengurangan stok.
        DB::unprepared("CREATE TRIGGER fail_second_detail BEFORE INSERT ON order_details WHEN NEW.id_barang = 'BRG002' BEGIN SELECT RAISE(ABORT, 'forced failure'); END");
        try {app(CheckoutService::class)->checkout('USR001','Jalan Contoh');$this->fail('Checkout seharusnya gagal.');}
        catch(QueryException $e){$this->assertStringContainsString('forced failure',$e->getMessage());}
        finally {DB::unprepared('DROP TRIGGER fail_second_detail');}
        $this->assertDatabaseCount('orders',0);$this->assertDatabaseCount('order_details',0);$this->assertDatabaseCount('cart_items',2);
        $this->assertDatabaseHas('products',['id_barang'=>'BRG001','stok'=>20]);$this->assertDatabaseHas('products',['id_barang'=>'BRG002','stok'=>30]);
    }
}
