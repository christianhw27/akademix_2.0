<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert existing single-path strings to JSON arrays for materials
        $materials = DB::table('materials')->whereNotNull('attachment')->get();
        foreach ($materials as $m) {
            $val = $m->attachment;
            if ($val && !str_starts_with(trim($val), '[')) {
                DB::table('materials')->where('id', $m->id)
                    ->update(['attachment' => json_encode([$val])]);
            }
        }
        Schema::table('materials', function (Blueprint $table) {
            $table->text('attachment')->nullable()->change();
        });

        // Convert existing single-path strings to JSON arrays for assignments
        $assignments = DB::table('assignments')->whereNotNull('attachment')->get();
        foreach ($assignments as $a) {
            $val = $a->attachment;
            if ($val && !str_starts_with(trim($val), '[')) {
                DB::table('assignments')->where('id', $a->id)
                    ->update(['attachment' => json_encode([$val])]);
            }
        }
        Schema::table('assignments', function (Blueprint $table) {
            $table->text('attachment')->nullable()->change();
        });

        // Convert existing single-path strings to JSON arrays for submissions
        $submissions = DB::table('assignment_submissions')->whereNotNull('attachment')->get();
        foreach ($submissions as $s) {
            $val = $s->attachment;
            if ($val && !str_starts_with(trim($val), '[')) {
                DB::table('assignment_submissions')->where('id', $s->id)
                    ->update(['attachment' => json_encode([$val])]);
            }
        }
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->text('attachment')->nullable()->change();
        });
    }

    public function down(): void
    {
        // No-op - data would need manual cleanup
    }
};
