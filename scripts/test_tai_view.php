<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\TAIController;

echo "Rendering TAI index view...\n";

$controller = new TAIController();
try {
    $response = $controller->index();
    if (method_exists($response, 'render')) {
        $html = $response->render();
        echo "View rendered successfully (length: " . strlen($html) . ").\n";
    } else {
        echo "Controller returned: " . gettype($response) . "\n";
    }
} catch (\Exception $e) {
    echo "Error rendering view: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "Done.\n";
