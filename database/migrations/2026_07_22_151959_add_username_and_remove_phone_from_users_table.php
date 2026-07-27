<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Tambahkan username sebagai nullable terlebih dahulu.
         * Hal ini diperlukan karena tabel users mungkin sudah memiliki data.
         */
        Schema::table('users', function (Blueprint $table) {
            $table
                ->string('username', 50)
                ->nullable()
                ->after('name');
        });

        /*
         * Buat username awal untuk akun yang sudah ada.
         * Username diambil dari bagian email sebelum tanda @.
         */
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $source = Str::before((string) $user->email, '@');

            $baseUsername = Str::slug(
                $source ?: $user->name ?: 'user',
                '_'
            );

            $baseUsername = Str::lower($baseUsername);

            if ($baseUsername === '') {
                $baseUsername = 'user';
            }

            $baseUsername = Str::limit(
                $baseUsername,
                35,
                ''
            );

            $username = $baseUsername;
            $counter = 1;

            while (
                DB::table('users')
                ->where('username', $username)
                ->exists()
            ) {
                $username = $baseUsername
                    . '_'
                    . $user->id
                    . '_'
                    . $counter;

                $counter++;
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'username' => $username,
                ]);
        }

        /*
         * Setelah semua user mempunyai username,
         * ubah menjadi wajib dan tambahkan unique index.
         */
        Schema::table('users', function (Blueprint $table) {
            $table
                ->string('username', 50)
                ->nullable(false)
                ->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
            $table->dropColumn('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table
                ->string('phone', 30)
                ->nullable()
                ->after('email');

            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
