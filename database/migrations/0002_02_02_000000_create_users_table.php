<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained();// Chave estrangeira que referencia a tabela roles
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('CPF');
            $table->boolean('is_pending')->default(true);
            $table->rememberToken();
            $table->timestamps();


          
            
        });
        
        $adminRole = DB::table('roles')->where('name', 'Administrador')->first();
        if ($adminRole) {
            // Crie um usuário administrador
            DB::table('users')->insert([
                'role_id' => $adminRole->id,
                'name' => env('ADMIN_NAME'),
                'email' => env('ADMIN_EMAIL'),
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'CPF' => env('ADMIN_CPF'), 
                'is_pending' => false, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};