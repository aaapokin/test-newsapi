<?php


namespace App\Console\Commands\RMQ;

use App\Infrastructure\RMQ\Base\IPackage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class RMQDeclarePackageExchanges extends Command
{
    /**
     * Имя и подпись команды.
     *
     * @var string
     */
    protected $signature = 'rmq:declare-package-exchanges';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Декларирует обменники из всех пакетов ' . IPackage::class;

    /**
     * Выполнение команды.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function handle()
    {
        foreach ($this->getPackages() as $package) {
            try {
                if ($package->autoDeclaration()) {
                    $package->declare();
                }
            } catch (\Throwable $e) {
                Log::error(
                    "Не удалось создать exchange " . $package::class . " " . $e->getMessage() . $e->getTraceAsString()
                );
            }
        }
    }

    /** @return IPackage[]
     * @throws \ReflectionException
     */
    private function getPackages(): array
    {
        $result = [];
        $interface = IPackage::class;
        $directory = base_path('app/Infrastructure/RMQ/Packages');

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        /** @var \RecursiveDirectoryIterator[] $iterator */
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            require_once $file->getPathname();
        }

        foreach (get_declared_classes() as $class) {
            $reflection = new \ReflectionClass($class);
            if (str_starts_with($reflection->getFileName(), realpath($directory))) {
                if (is_subclass_of($class, $interface) || $class === $interface) {
                    try {
                        $result[] = $reflection->newInstanceWithoutConstructor();
                    } catch (\Throwable $e) {
                        Log::error(
                            "Не удалось создать объект без конструктора " . $class . " " . $e->getMessage(
                            ) . $e->getTraceAsString()
                        );
                    }
                }
            }
        }

        return $result;
    }
}
