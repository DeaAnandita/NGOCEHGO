<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMasterMigrations extends Command
{
    protected $signature = 'generate:masters';
    protected $description = 'Generate all master_ table migrations automatically';

    public function handle()
    {
        $masters = [
            // Daftar nama tabel master dari rancanganmu
            'agama', 'pekerjaan', 'statuskawin', 'asetkeluarga', 'asetlahan',
            'asetternak', 'asetperikanan', 'sarpraskerja', 'kdtypejawab',
            'pembangunankeluarga', 'jenislembaga', 'lembaga', 
        ];

        foreach ($masters as $master) {
            $className = 'CreateMaster' . Str::studly($master) . 'Table';
            $fileName = date('Y_m_d_His', time() + rand(1, 999)) . '_create_master_' . $master . '_table.php';
            $filePath = database_path('migrations/' . $fileName);

            $template = "<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_{$master}', function (Blueprint \$table) {
            \$table->id();
            \$table->string('kd{$master}', 10)->unique();
            \$table->string('{$master}', 100);
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_{$master}');
    }
};";

            // 🔹 Tambahkan relasi khusus
            if ($master === 'pembangunankeluarga') {
                $template = str_replace(
                    "\$table->timestamps();",
                    "\$table->unsignedBigInteger('kdtypejawab');\n            \$table->timestamps();\n\n            \$table->foreign('kdtypejawab')->references('id')->on('master_kdtypejawab')->onDelete('cascade');",
                    $template
                );
            }

            if ($master === 'lembaga') {
                $template = str_replace(
                    "\$table->timestamps();",
                    "\$table->unsignedBigInteger('kdjenislembaga');\n            \$table->timestamps();\n\n            \$table->foreign('kdjenislembaga')->references('id')->on('master_jenislembaga')->onDelete('cascade');",
                    $template
                );
            }

            File::put($filePath, $template);

            // ✅ Info log normal (tanpa escape backslash)
            $this->info("✔ Created migration for master_{$master}");
        }

        $this->info('✅ Semua migration master berhasil dibuat!');
    }
}
