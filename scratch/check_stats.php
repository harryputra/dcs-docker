<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Document;

$totalDocs = Document::count();
$expiredDocsCount = Document::where('status_document', 'Expired')->count();
$kadaluarsaDocsCount = Document::where('status_document', 'Kadaluarsa')->count();
$isActiveFalseCount = Document::where('is_active', false)->count();

$expiredViaRevision = Document::whereHas('revisions', function ($query) {
    $query->where('status', 'Expired');
})->where('is_active', false)->count();

echo "Total Docs: $totalDocs\n";
echo "Status Document 'Expired': $expiredDocsCount\n";
echo "Status Document 'Kadaluarsa': $kadaluarsaDocsCount\n";
echo "Is Active False: $isActiveFalseCount\n";
echo "Expired via Revision & Active False: $expiredViaRevision\n";

$docs = Document::where('status_document', 'Expired')->orWhere('status_document', 'Kadaluarsa')->get();
foreach($docs as $doc) {
    echo "Doc ID: {$doc->id}, Title: {$doc->title}, Status: {$doc->status_document}, Is Active: {$doc->is_active}\n";
}
