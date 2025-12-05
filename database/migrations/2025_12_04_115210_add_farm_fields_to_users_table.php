<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fullname')->nullable()->after('name');
            $table->enum('role', ['SUPER_ADMIN', 'ADMIN', 'OWNER', 'WORKER', 'FIELD_WORKER'])->default('WORKER')->after('password');
            $table->foreignId('farm_id')->nullable()->constrained('farms')->nullOnDelete()->after('role');
            $table->string('phone_number')->nullable()->after('farm_id');
            $table->string('phone')->nullable()->after('phone_number');
            $table->string('address')->nullable()->after('phone');
            $table->string('picture')->nullable()->after('address');
            $table->string('avatar')->nullable()->after('picture');
            $table->boolean('status')->default(true)->after('avatar');
            $table->boolean('is_active')->default(true)->after('status');
            $table->timestamp('email_verified')->nullable()->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('email_verified');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['farm_id']);
            $table->dropColumn([
                'fullname', 'role', 'farm_id', 'phone_number', 'phone',
                'address', 'picture', 'avatar', 'status', 'is_active',
                'email_verified', 'last_login_at', 'deleted_at'
            ]);
        });
    }
};
