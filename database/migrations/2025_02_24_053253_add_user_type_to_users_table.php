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
        if (!Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
            
                    $table->enum('user_type', ['super_admin', 'admin', 'customer'])->default('customer'); 
                
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
            Schema::table('users', function (Blueprint $table) {
            
                    $table->dropColumn('user_type');
                
            });
        
    }
};
