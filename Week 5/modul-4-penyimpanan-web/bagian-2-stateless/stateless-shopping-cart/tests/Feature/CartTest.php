<?php
namespace Tests\Feature;
use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class CartTest extends TestCase {
    use RefreshDatabase;
    protected function setUp(): void {parent::setUp();$this->seed();}
    public function test_catalog_and_empty_cart_are_public(): void {$this->assertDatabaseCount('barang',5);$this->get('/barang')->assertOk();$this->get('/keranjang')->assertOk();$this->assertGuest();}
    public function test_repeated_add_and_refresh_keep_only_id_and_quantity(): void {
        $this->post('/keranjang',['id'=>1]);$this->post('/keranjang',['id'=>1]);
        $this->post('/keranjang',['id'=>2,'harga'=>1])->assertSessionHas('keranjang',[1=>2,2=>1]);
        $this->get('/keranjang')->assertOk()->assertSee('13.000');
        $this->get('/keranjang')->assertSessionHas('keranjang',[1=>2,2=>1])->assertSee('13.000');
    }
    public function test_quantity_zero_removes_item(): void {$this->withSession(['keranjang'=>[1=>2]])->patch('/keranjang/1',['jumlah'=>0])->assertSessionHas('keranjang',[]);}
    public function test_edit_remove_and_clear(): void {
        $this->withSession(['keranjang'=>[1=>2,2=>1]])->patch('/keranjang/1',['jumlah'=>3])->assertSessionHas('keranjang',[1=>3,2=>1]);
        $this->delete('/keranjang/1')->assertSessionHas('keranjang',[2=>1]);
        $this->delete('/keranjang')->assertSessionMissing('keranjang');
    }
    public function test_over_stock_negative_and_fractional_quantities_fail(): void {
        foreach([999,-1,'1.5'] as $qty){$this->withSession(['keranjang'=>[1=>2]])->patch('/keranjang/1',['jumlah'=>$qty])->assertSessionHasErrors('jumlah')->assertSessionHas('keranjang',[1=>2]);}
        $this->withSession(['keranjang'=>[1=>20]])->post('/keranjang',['id'=>1])->assertSessionHasErrors('jumlah')->assertSessionHas('keranjang',[1=>20]);
    }
    public function test_invalid_product_fails(): void {$this->post('/keranjang',['id'=>999])->assertSessionHasErrors('id');$this->patch('/keranjang/999',['jumlah'=>1])->assertNotFound();}
    public function test_display_uses_current_database_price(): void {
        Barang::whereKey(1)->update(['harga'=>'5500.00']);
        $this->withSession(['keranjang'=>[1=>2]])->get('/keranjang')->assertOk()->assertSee('11.000');
    }
}
