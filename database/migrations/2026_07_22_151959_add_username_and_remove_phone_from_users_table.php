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
         * Database lama mungkin sudah mempunyai kolom username walaupun
         * migrasi ini belum tercatat selesai. Karena itu, kolom hanya dibuat
         * ketika benar-benar belum tersedia.
         */
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table): void {
                $table
                    ->string('username', 50)
                    ->nullable()
                    ->after('name');
            });
        }

        /*
         * Lengkapi username yang kosong dan rapikan username duplikat.
         * Username yang sudah valid tetap dipertahankan.
         */
        $usedUsernames = [];

        $users = DB::table('users')
            ->select('id', 'name', 'email', 'username')
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $currentUsername = trim((string) $user->username);
            $source = $currentUsername !== ''
                ? $currentUsername
                : Str::before((string) $user->email, '@');

            if (trim($source) === '') {
                $source = (string) $user->name;
            }

            $baseUsername = Str::lower(Str::slug($source, '_'));

            if ($baseUsername === '') {
                $baseUsername = 'user';
            }

            // Sisakan ruang untuk akhiran ID jika terjadi duplikasi.
            $baseUsername = Str::limit($baseUsername, 40, '');
            $username = $baseUsername;
            $counter = 1;

            while (isset($usedUsernames[Str::lower($username)])) {
                $suffix = '_' . $user->id;

                if ($counter > 1) {
                    $suffix .= '_' . $counter;
                }

                $availableLength = max(1, 50 - strlen($suffix));
                $username = Str::limit($baseUsername, $availableLength, '') . $suffix;
                $counter++;
            }

            if ($currentUsername !== $username) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $username]);
            }

            $usedUsernames[Str::lower($username)] = true;
        }

        /*
         * Setelah tidak ada nilai kosong, jadikan username wajib.
         */
        Schema::table('users', function (Blueprint $table): void {
            $table
                ->string('username', 50)
                ->nullable(false)
                ->change();
        });

        /*
         * Tambahkan unique index hanya jika belum tersedia.
         */
        if (! Schema::hasIndex('users', ['username'], 'unique')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->unique('username');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'username')) {
            return;
        }

        foreach (Schema::getIndexes('users') as $index) {
            $columns = array_map('strtolower', $index['columns'] ?? []);

            if (($index['unique'] ?? false) && $columns === ['username']) {
                Schema::table('users', function (Blueprint $table) use ($index): void {
                    $table->dropUnique($index['name']);
                });

                break;
            }
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('username');
        });
    }
};
