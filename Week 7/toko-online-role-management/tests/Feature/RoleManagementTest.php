<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Services\RoleManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void { parent::setUp(); $this->seed(); }
    private function user(string $id): User { return User::findOrFail($id); }

    public function test_all_role_ability_combinations_match_matrix(): void
    {
        foreach (['USR001'=>[false,false,false,true], 'STF001'=>[false,false,true,false], 'ADM001'=>[true,true,true,false]] as $id=>$expected) {
            foreach (['manage-roles','manage-products','adjust-stock','use-cart'] as $i=>$ability) {
                $this->assertSame($expected[$i], Gate::forUser($this->user($id))->allows($ability));
            }
        }
        $this->get('/barang')->assertOk()->assertDontSee('Stok:');
        $this->get('/inventory')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
        $this->patchJson('/admin/users/USR001/role',['role'=>'staff'])->assertUnauthorized();
    }

    public function test_admin_changes_role_once_and_observer_records_correct_primary_key(): void
    {
        Log::spy();
        $this->actingAs($this->user('ADM001'))->patchJson('/admin/users/USR001/role',['role'=>'staff'])
            ->assertOk()->assertJsonPath('changed',true);
        $this->assertDatabaseHas('users',['id_user'=>'USR001','role'=>'staff']);
        Log::shouldHaveReceived('info')->once()->with('user.role.changed', [
            'target_user_id'=>'USR001','old_role'=>'customer','new_role'=>'staff','actor_id'=>'ADM001']);
    }

    public function test_staff_can_be_returned_to_customer(): void
    {
        $this->actingAs($this->user('ADM001'))->patchJson('/admin/users/STF001/role',['role'=>'customer'])->assertOk();
        $this->assertDatabaseHas('users',['id_user'=>'STF001','role'=>'customer']);
    }

    public function test_same_role_does_not_save_or_log(): void
    {
        Log::spy(); $updates=0;
        User::updating(function () use (&$updates) { $updates++; });
        $this->actingAs($this->user('ADM001'))->patchJson('/admin/users/USR001/role',['role'=>'customer'])
            ->assertOk()->assertJsonPath('changed',false);
        $this->assertSame(0,$updates);
        Log::shouldNotHaveReceived('info');
    }

    public function test_customer_and_staff_cannot_patch_roles_even_with_invalid_payload(): void
    {
        Log::spy();
        foreach (['USR001','STF001'] as $id) {
            $this->actingAs($this->user($id))->patchJson('/admin/users/USR002/role',['role'=>'admin'])->assertForbidden();
        }
        $this->assertDatabaseHas('users',['id_user'=>'USR002','role'=>'customer']);
        Log::shouldNotHaveReceived('info');
    }

    public function test_own_account_and_other_admin_are_protected(): void
    {
        Log::spy();
        foreach (['ADM001','ADM002'] as $id) {
            $this->actingAs($this->user('ADM001'))->patchJson('/admin/users/'.$id.'/role',['role'=>'customer'])
                ->assertUnprocessable()->assertJsonValidationErrors('role');
            $this->assertDatabaseHas('users',['id_user'=>$id,'role'=>'admin']);
        }
        Log::shouldNotHaveReceived('info');
    }

    public function test_invalid_missing_array_and_admin_role_are_rejected(): void
    {
        Log::spy();
        foreach ([[],['role'=>'admin'],['role'=>'guest'],['role'=>['staff']],['role'=>'']] as $payload) {
            $this->actingAs($this->user('ADM001'))->patchJson('/admin/users/USR001/role',$payload)
                ->assertUnprocessable()->assertJsonValidationErrors('role');
        }
        $this->assertDatabaseHas('users',['id_user'=>'USR001','role'=>'customer']);
        Log::shouldNotHaveReceived('info');
    }

    public function test_html_form_redirects_with_success_or_validation_errors(): void
    {
        $this->actingAs($this->user('ADM001'))->from('/admin/users')
            ->patch('/admin/users/USR001/role',['role'=>'staff'])->assertRedirect('/admin/users')->assertSessionHas('success');
        $this->from('/admin/users')->patch('/admin/users/ADM001/role',['role'=>'customer'])
            ->assertRedirect('/admin/users')->assertSessionHasErrors('role');
    }

    public function test_role_change_immediately_changes_cart_access_in_same_session(): void
    {
        $this->actingAs($this->user('USR001'))->postJson('/cart/add',['cart'=>[],'product_id'=>1])->assertOk();
        $this->actingAs($this->user('ADM001'))->patchJson('/admin/users/USR001/role',['role'=>'staff'])->assertOk();
        $this->actingAs($this->user('USR001'))->postJson('/cart/add',['cart'=>[],'product_id'=>1])->assertForbidden();
        $this->get('/keranjang')->assertForbidden();
    }

    public function test_cart_is_customer_only_and_does_not_store_cart_state(): void
    {
        foreach (['STF001','ADM001'] as $id) {
            $this->actingAs($this->user($id))->postJson('/cart/add',['cart'=>[],'product_id'=>1])->assertForbidden();
        }
        $this->actingAs($this->user('USR001'))->postJson('/cart/add',['cart'=>[],'product_id'=>1,'price'=>1])->assertOk()->assertJsonPath('total',5000);
        $this->postJson('/cart/update',['cart'=>[]])->assertOk()->assertJsonPath('total',0);
        $this->assertDatabaseCount('cart_items',0);
    }

    public function test_registration_cannot_select_admin_and_default_is_customer(): void
    {
        $data=['username'=>'newuser','nama_lengkap'=>'Customer Baru','email'=>'new@example.test',
            'password'=>'Register123!','password_confirmation'=>'Register123!','no_hp'=>'08000000','alamat'=>'Bandung'];
        $this->postJson('/register',$data+['role'=>'admin'])->assertUnprocessable();
        $this->assertDatabaseMissing('users',['username'=>'newuser']);
        $this->postJson('/register',$data)->assertCreated()->assertJsonPath('role','customer');
        $this->assertDatabaseHas('users',['username'=>'newuser','role'=>'customer']);
        $this->assertNotContains('role',(new User)->getFillable());
    }

    public function test_stock_and_product_management_are_protected_server_side(): void
    {
        $data=['nama_barang'=>'Buku Baru','deskripsi'=>'Deskripsi baru','harga'=>'6000','stok'=>9];
        $this->actingAs($this->user('USR001'))->get('/inventory')->assertForbidden();
        $this->actingAs($this->user('STF001'))->get('/inventory')->assertOk();
        $this->patch('/admin/products/BRG001',$data)->assertForbidden();
        $this->actingAs($this->user('ADM001'))->patch('/admin/products/BRG001',$data)->assertRedirect();
        $this->assertDatabaseHas('products',['id_barang'=>'BRG001','nama_barang'=>'Buku Baru','stok'=>9]);
    }

    public function test_bulk_update_skips_observer_but_instance_save_triggers_it(): void
    {
        Log::spy();
        User::whereKey('USR001')->update(['role'=>'staff']);
        Log::shouldNotHaveReceived('info');
        $user=$this->user('USR001'); $user->role='customer'; $user->save();
        Log::shouldHaveReceived('info')->once()->with('user.role.changed', [
            'target_user_id'=>'USR001','old_role'=>'staff','new_role'=>'customer','actor_id'=>null]);
    }

    public function test_other_attribute_change_is_not_role_change(): void
    {
        Log::spy(); $user=$this->user('USR001');$user->nama_lengkap='Nama Baru';$user->save();
        Log::shouldNotHaveReceived('info');
    }

    public function test_service_rechecks_actor_when_called_without_controller(): void
    {
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        app(RoleManagementService::class)->changeRole($this->user('USR001'),$this->user('USR002'),'staff');
    }

    public function test_order_policy_requires_customer_ownership(): void
    {
        $order=new Order(['id_order'=>'TEST001','id_user'=>'USR001']);
        $this->assertTrue(Gate::forUser($this->user('USR001'))->allows('view',$order));
        foreach (['USR002','STF001','ADM001'] as $id) $this->assertFalse(Gate::forUser($this->user($id))->allows('view',$order));
    }
}
