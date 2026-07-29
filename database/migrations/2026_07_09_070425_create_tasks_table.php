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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id('task_id');
        $table->string('task_title');
        $table->text('task_description');
        $table->date('start_task');
        $table->date('end_task');
        $table->string('status')->default('Not started');
        
        // التعديل هنا: تحديد 'project_id' كبارامتر ثاني للربط
        $table->foreignId('project_id')->constrained('projects', 'project_id')->onDelete('cascade');
        
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
