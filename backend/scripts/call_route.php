<?php
// Quick script to bootstrap app and call the /api/healthz route and print response
require __DIR__ . '/../vendor/autoload.php';
use Illuminate\Http\Request;

try {
	$app = require_once __DIR__ . '/../bootstrap/app.php';
	$request = Request::create('/api/healthz', 'GET');
	$response = $app->handleRequest($request);
	if ($response === null) {
		file_put_contents(__DIR__.'/../health_output.txt', "ERROR: Application returned null response\n");
		echo "Wrote health_output.txt (null response)\n";
	} else {
		$status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 'unknown';
		$body = method_exists($response, 'getContent') ? $response->getContent() : json_encode($response);
		file_put_contents(__DIR__.'/../health_output.txt', "STATUS: {$status}\nBODY:\n" . $body);
		echo "Wrote health_output.txt\n";
	}
} catch (Throwable $e) {
	$msg = "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString();
	file_put_contents(__DIR__.'/../health_output.txt', $msg);
	echo "Wrote health_output.txt (exception)\n";
}
