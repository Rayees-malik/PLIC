<?php

use App\Models\Product;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quality_control_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vendor::class)->nullable();
            $table->foreignIdFor(Warehouse::class)->nullable();
            $table->date('received_date');
            $table->string('po_number');
            $table->foreignIdFor(User::class);
            $table->string('lot_number');
            $table->string('bin_number');
            $table->string('din_npn_number');
            $table->boolean('din_npn_on_label')->nullable();
            $table->boolean('importer_address')->nullable();
            $table->boolean('seals_intact')->nullable();
            $table->boolean('bilingual_label')->nullable();
            $table->foreignIdFor(Product::class);
            $table->unsignedInteger('quantity_received');
            $table->date('expiry_date');
            $table->string('receiving_comment')->nullable();

            $table->unsignedSmallInteger('number_damaged_cartons')->nullable();
            $table->unsignedSmallInteger('number_damaged_units')->nullable();
            $table->string('nature_of_damage')->nullable();
            $table->unsignedSmallInteger('number_units_sent_for_testing')->nullable();
            $table->unsignedSmallInteger('number_units_for_stability')->nullable();
            $table->unsignedSmallInteger('number_units_retained')->nullable();
            $table->string('regulatory_compliance_comment')->nullable();

            $table->string('identity_description');
            $table->boolean('matches_written_specification')->default(false);
            $table->string('out_of_specifications_comment')->nullable();
            $table->date('completed_at')->nullable();
            $table->foreignIdFor(User::class, 'completed_by')->nullable();
            $table->string('generated_tag')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quality_control_records');
    }
};
