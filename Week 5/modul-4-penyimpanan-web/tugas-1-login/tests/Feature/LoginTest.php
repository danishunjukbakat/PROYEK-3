<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
class LoginTest extends TestCase {
    use RefreshDatabase;
    protected function setUp(): void {parent::setUp();$this->seed();}
    public function test_seeded_passwords_are_hashes(): void {
        $this->assertDatabaseCount('users',2);
        $user=User::where('username','budi')->firstOrFail();
        $this->assertNotSame('rahasia123',$user->password);
        $this->assertTrue(Hash::check('rahasia123',$user->password));
        $this->assertArrayNotHasKey('password',$user->toArray());
    }
    public function test_guest_cannot_open_dashboard(): void {$this->get('/dashboard')->assertRedirect('/login');$this->get('/login')->assertOk();}
    public function test_wrong_password_is_rejected(): void {
        $this->from('/login')->post('/login',['username'=>'budi','password'=>'salah'])->assertRedirect('/login')->assertSessionHasErrors('login');$this->assertGuest();
    }
    public function test_empty_credentials_are_rejected(): void {$this->post('/login',[])->assertSessionHasErrors(['username','password']);$this->assertGuest();}
    public function test_login_dashboard_and_logout(): void {
        $this->post('/login',['username'=>'budi','password'=>'rahasia123'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('username','budi')->firstOrFail());
        $this->get('/dashboard')->assertOk()->assertSee('Budi');
        $this->get('/login')->assertRedirect('/dashboard');
        $this->post('/logout')->assertRedirect('/login');$this->assertGuest();
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
