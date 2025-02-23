<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('select_stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('navigation');
            $table->timestamps();
        });


        DB::table('select_stores')->insert([
            [
                'name' => 'السوق الساخن',
                'description' => 'السوق الساخن',
                'status' => 'active',
                'image' => 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse2.mm.bing.net%2Fth%3Fid%3DOIP.Py4eRbJ0WIFjs51eUrIErAHaEo%26pid%3DApi&f=1&ipt=3e8e86ca1d0a343d7e24a88ed65552e2237ff958bfc2e100e44f009e0bb4c7d8&ipo=images',
                'icon' => 'icon',
                'navigation' => 'HotMarket',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'السوق العدي',
                'description' => 'السوق العدي',
                'status' => 'active',
                'image' => 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse3.mm.bing.net%2Fth%3Fid%3DOIP.lA6e0nKIBRDwRiL6v_NJKAHaEo%26pid%3DApi&f=1&ipt=56736d037b758d99ea4936c21c017f75d68aa7baf139a2e560b358de92bd6965&ipo=images',
                'icon' => 'icon',
                'navigation' => 'Taps',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('select_stores');
    }
};
