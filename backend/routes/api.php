<?php
use Illuminate\Support\Facades\Route;
use App\Models\Blog;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Blogs
Route::get('/blogs', fn() => Blog::orderByDesc('created_at')->paginate(25));

Route::get('/blogs/{slug}', fn(string $slug) => Blog::where('slug',$slug)->firstOrFail());

Route::post('/blogs', function(Request $request){
   $data = $request->validate([
      'title' => 'required|string|max:255',
      'category' => 'nullable|string|max:120',
      'content' => 'required|string',
      'status' => 'nullable|string|in:draft,published'
   ]);
   $data['slug'] = Str::slug($data['title']);
   $data['excerpt'] = Str::limit(strip_tags($data['content']), 180);
   $data['status'] = $data['status'] ?? 'draft';
   $data['author_name'] = 'Admin';
   $blog = Blog::create($data);
   return response()->json($blog, 201);
});

Route::patch('/blogs/{slug}/publish', function(string $slug){
   $blog = Blog::where('slug',$slug)->firstOrFail();
   $blog->update(['status' => 'published', 'published_at' => now()]);
   return $blog;
});

Route::delete('/blogs/{slug}', function(string $slug){
   $blog = Blog::where('slug',$slug)->firstOrFail();
   $blog->delete();
   return response()->json(['deleted'=>true]);
});

// Route pour servir les images des participants
Route::get('/images/participants/{type}/{filename}', function($type, $filename) {
    $path = storage_path('app/public/participants/' . $type . '/' . $filename);
    
    if (!file_exists($path)) {
        return response()->json(['error' => 'Image not found'], 404);
    }
    
    $mimeType = mime_content_type($path);
    return response()->file($path, ['Content-Type' => $mimeType]);
});

// Routes pour les invitations
Route::post('/invitations/send', [App\Http\Controllers\InvitationController::class, 'sendInvitation']);
Route::post('/invitations/bulk', [App\Http\Controllers\InvitationController::class, 'sendBulkInvitations']);
Route::get('/confirm-participation/{participant}', [App\Http\Controllers\InvitationController::class, 'confirmParticipation']);
Route::post('/badges/generate/{participant}', [App\Http\Controllers\InvitationController::class, 'generateBadge']);

// Participants
Route::post('/participants', function(Request $request){
   // Accept multipart/form-data including files
   $data = $request->validate([
      'first_name' => 'required|string|max:120',
      'last_name' => 'required|string|max:120',
      'email' => 'required|email',
      'phone' => 'nullable|string|max:60',
      'whatsapp' => 'nullable|string|max:60',
      'birth_date' => 'nullable|date',
      'gender' => 'nullable|string|max:20',
      'country' => 'required|string|max:120',
      'manual_country' => 'nullable|string|max:120',
      'province' => 'nullable|string|max:120',
      'city' => 'required|string|max:120',
      'registration_type' => 'nullable|string|max:120',
      'type' => 'nullable|string|max:120',
      'organization' => 'nullable|string|max:255',
      'occupation' => 'nullable|string|max:255',
      'experience' => 'nullable|string',
      'motivation' => 'nullable|string',
      'interests' => 'nullable|array',
      'interests.*' => 'string|max:120',
      'handicap' => 'nullable|string|max:120',
      'handicap_type' => 'nullable|string|max:120',
      'handicap_accommodation' => 'nullable|string',
      'whatsapp_opt' => 'nullable|string|max:10',
      'terms' => 'nullable|string',
      'newsletter' => 'nullable|boolean',
      'photos' => 'nullable|boolean',
   ]);
   // Handle files if provided
   if($request->hasFile('photo')){
      $p = $request->file('photo');
      $path = $p->store('participants/photos', 'public');
      $data['photo_path'] = '/storage/' . $path;
   }
   if($request->hasFile('passport')){
      $p = $request->file('passport');
      $path = $p->store('participants/passports', 'public');
      $data['passport_path'] = '/storage/' . $path;
   }
   if($request->hasFile('cv')){
      $c = $request->file('cv');
      $path = $c->store('participants/cv', 'public');
      $data['cv_path'] = '/storage/' . $path;
   }
   // Legacy support
   if($request->hasFile('passportImage')){
      $p = $request->file('passportImage');
      $path = $p->store('participants/passports', 'public');
      $data['passport_path'] = '/storage/' . $path;
   }
   if($request->hasFile('cinImage')){
      $c = $request->file('cinImage');
      $path = $c->store('participants/cin', 'public');
      $data['cin_path'] = '/storage/' . $path;
   }
   // Handle manual country input
   if($data['country'] === 'Autre' && !empty($data['manual_country'])) {
      $data['country'] = $data['manual_country'];
   }
   
   // Try to persist participant; if DB is unavailable fall back to a pending queue file
   try {
   $participant = Participant::create($data + ['status' => 'pending']);

      // Try sending confirmation email (use log mailer by default if SMTP not configured)
      try {
         if(class_exists(\App\Mail\ParticipantConfirmation::class)){
            \Illuminate\Support\Facades\Mail::to($participant->email)->send(new \App\Mail\ParticipantConfirmation($participant));
         }
      } catch (\Throwable $e) {
         // don't fail the API if mail sending fails; log error
         logger()->error('Failed to send participant confirmation email: ' . $e->getMessage());
      }

      return response()->json($participant, 201);
   } catch (\Throwable $e) {
      // Check if it's a unique constraint violation (duplicate email)
      if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false && strpos($e->getMessage(), 'participants.email') !== false) {
         return response()->json(['success' => false, 'message' => 'Cette adresse email est déjà utilisée. Veuillez utiliser une autre adresse email.'], 409);
      }
      
      // Likely a PDO / driver issue. Queue the participant to a pending file so admin can retry later.
      logger()->error('Participant create failed, queuing to pending file: ' . $e->getMessage());
      try {
         // safe write utility: read, modify, atomic write
         $pendingPath = storage_path('pending_participants.json');
         $pending = [];
         if(file_exists($pendingPath)){
            $raw = file_get_contents($pendingPath);
            $pending = json_decode($raw ?: '[]', true) ?: [];
         }
         $queued = array_merge($data, ['status' => 'pending', 'queued_at' => now()->toDateTimeString()]);
         $pending[] = $queued;
         // atomic write via temp file + rename
         $tmp = $pendingPath . '.' . uniqid('tmp_', true);
         file_put_contents($tmp, json_encode($pending, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
         @rename($tmp, $pendingPath);

         // Also append to a participants fallback file used by frontend dashboard when DB is down
         try {
            $fallbackPath = storage_path('participants_fallback.json');
            $fallback = ['data'=>[], 'current_page'=>1,'last_page'=>1,'per_page'=>50,'total'=>0];
            if(file_exists($fallbackPath)){
               $raw = file_get_contents($fallbackPath);
               $fallback = json_decode($raw ?: 'null', true) ?: $fallback;
            }
            // prepend to show newest first
            array_unshift($fallback['data'], $queued);
            $fallback['total'] = ($fallback['total'] ?? 0) + 1;
            file_put_contents($fallbackPath, json_encode($fallback, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
         } catch (\Throwable $e3) {
            logger()->error('Failed to write participants fallback file: ' . $e3->getMessage());
         }
      } catch (\Throwable $e2) {
         logger()->error('Failed to write pending participant file: ' . $e2->getMessage());
      }

      return response()->json(['queued' => true, 'message' => 'Database unavailable, participant queued for retry'], 202);
   }
});

Route::get('/participants', function(Request $request){
   // Return paginated participants; if DB is unavailable return a fallback JSON from storage
   try {
      $participants = Participant::latest()->paginate(50);
      
      // Convertir en array et modifier directement
      $data = $participants->toArray();
      
      // Ajouter les champs d'images manquants
      foreach($data['data'] as &$participant) {
         $photoPath = DB::table('participants')->where('id', $participant['id'])->value('photo_path');
         $passportPath = DB::table('participants')->where('id', $participant['id'])->value('passport_path');
         $cinPath = DB::table('participants')->where('id', $participant['id'])->value('cin_path');
         
         // Forcer l'inclusion même si NULL (utiliser chaîne vide au lieu de NULL)
         $participant['photo_path'] = $photoPath ?: '';
         $participant['passport_path'] = $passportPath ?: '';
         $participant['cin_path'] = $cinPath ?: '';
      }
      
      return response()->json($data);
   } catch (\Throwable $e) {
      logger()->error('Failed to fetch participants from DB, returning fallback: ' . $e->getMessage());
      $fallbackPath = storage_path('participants_fallback.json');
      $fallback = [
         'data' => [],
         'current_page' => 1,
         'last_page' => 1,
         'per_page' => 50,
         'total' => 0
      ];
      if(file_exists($fallbackPath)){
         $raw = file_get_contents($fallbackPath);
         $decoded = json_decode($raw ?: 'null', true);
         if(is_array($decoded)) $fallback = $decoded;
      }
      return response()->json($fallback);
   }
});

// Temporary health check for debugging: returns simple status
Route::get('/healthz', function(){
   return response()->json(['status' => 'ok', 'time' => now()->toDateTimeString()]);
});

// Admin: view pending queued participants (when DB was unavailable)
Route::get('/admin/pending-participants', function(){
   $pendingPath = storage_path('pending_participants.json');
   $pending = [];
   if(file_exists($pendingPath)){
      $raw = file_get_contents($pendingPath);
      $pending = json_decode($raw ?: '[]', true) ?: [];
   }
   return response()->json(['count' => count($pending), 'data' => $pending]);
});

// Admin: retry queued participants and attempt to persist/send emails
Route::post('/admin/retry-pending', function(){
   $pendingPath = storage_path('pending_participants.json');
   if(!file_exists($pendingPath)) return response()->json(['count'=>0,'results'=>[]]);
   $raw = file_get_contents($pendingPath);
   $pending = json_decode($raw ?: '[]', true) ?: [];
   $results = [];
   $remaining = [];
   foreach($pending as $idx => $item){
      try {
         $p = Participant::create($item);
         // try to send confirmation
         try {
            if(class_exists(\App\Mail\ParticipantConfirmation::class)){
               \Illuminate\Support\Facades\Mail::to($p->email)->send(new \App\Mail\ParticipantConfirmation($p));
            }
         } catch (\Throwable $mailErr) {
            logger()->error('Retry: failed to send mail for queued participant: '.$mailErr->getMessage());
         }
         $results[] = ['idx'=>$idx,'success'=>true,'id'=>$p->id];
      } catch (\Throwable $e) {
         logger()->error('Retry: failed to insert queued participant: '.$e->getMessage());
         $results[] = ['idx'=>$idx,'success'=>false,'error'=>$e->getMessage()];
         $remaining[] = $item;
      }
   }
   try { file_put_contents($pendingPath, json_encode($remaining, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); } catch (\Throwable $e){ logger()->error('Retry: failed to write pending file: '.$e->getMessage()); }
   return response()->json(['count' => count($pending), 'results' => $results, 'remaining' => count($remaining)]);
});

// Participant actions
Route::get('/participants/{id}', function($id){
   try {
      $participant = Participant::findOrFail($id);
      return response()->json($participant);
   } catch (\Throwable $e) {
      return response()->json(['error' => 'Participant not found'], 404);
   }
});

Route::put('/participants/{id}', function(Request $request, $id){
   try {
      $participant = Participant::findOrFail($id);
      $data = $request->validate([
         'status' => 'required|string|in:pending,confirmed,rejected'
      ]);
      $participant->update($data);
      return response()->json($participant);
   } catch (\Throwable $e) {
      return response()->json(['error' => 'Failed to update participant'], 500);
   }
});

Route::get('/participants/{id}/pdf', function($id){
   try {
      $participant = Participant::findOrFail($id);
      
      // Generate simple PDF content
      $html = "
      <html>
      <head>
         <title>Profil Participant - {$participant->first_name} {$participant->last_name}</title>
         <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
            .info { margin: 10px 0; }
            .label { font-weight: bold; color: #007bff; }
         </style>
      </head>
      <body>
         <div class='header'>
            <h1>YouthConnekt Sahel 2025</h1>
            <h2>Profil Participant</h2>
         </div>
         
         <div class='info'>
            <span class='label'>ID Participant:</span> {$participant->id}<br>
            <span class='label'>Nom:</span> {$participant->first_name} {$participant->last_name}<br>
            <span class='label'>Email:</span> {$participant->email}<br>
            <span class='label'>Téléphone:</span> {$participant->phone}<br>
            <span class='label'>Pays:</span> {$participant->country}<br>
            <span class='label'>Ville:</span> {$participant->city}<br>
            <span class='label'>Statut:</span> {$participant->status}<br>
            <span class='label'>Date d'inscription:</span> {$participant->created_at}
         </div>
         
         <div style='margin-top: 30px; text-align: center; color: #666;'>
            <p>Forum YouthConnekt Sahel 2025 - 13-15 Octobre 2025, N'Djamena, Tchad</p>
         </div>
      </body>
      </html>";
      
      return response($html)->header('Content-Type', 'text/html');
   } catch (\Throwable $e) {
      return response()->json(['error' => 'Participant not found'], 404);
   }
});

Route::get('/participants/{id}/badge', function($id){
   try {
      $participant = Participant::findOrFail($id);
      
      // Generate simple badge HTML
      $html = "
      <html>
      <head>
         <title>Badge - {$participant->first_name} {$participant->last_name}</title>
         <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
            .badge { width: 300px; height: 200px; background: white; border: 3px solid #007bff; border-radius: 15px; margin: 0 auto; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
            .header { color: #007bff; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
            .name { font-size: 24px; font-weight: bold; margin: 15px 0; color: #333; }
            .info { font-size: 14px; color: #666; margin: 5px 0; }
            .id { background: #007bff; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; margin-top: 15px; }
         </style>
      </head>
      <body>
         <div class='badge'>
            <div class='header'>YouthConnekt Sahel 2025</div>
            <div class='name'>{$participant->first_name} {$participant->last_name}</div>
            <div class='info'>{$participant->country}</div>
            <div class='info'>{$participant->city}</div>
            <div class='id'>ID: {$participant->id}</div>
         </div>
      </body>
      </html>";
      
      return response($html)->header('Content-Type', 'text/html');
   } catch (\Throwable $e) {
      return response()->json(['error' => 'Participant not found'], 404);
   }
});

// Email routes
Route::post('/email/partner-confirmation', [App\Http\Controllers\Api\EmailController::class, 'sendPartnerConfirmation']);
Route::post('/email/stand-confirmation', [App\Http\Controllers\Api\EmailController::class, 'sendStandConfirmation']);
Route::post('/email/contact-confirmation', [App\Http\Controllers\Api\EmailController::class, 'sendContactConfirmation']);
Route::post('/email/participant-confirmation', [App\Http\Controllers\Api\EmailController::class, 'sendParticipantConfirmation']);
Route::post('/email/test', [App\Http\Controllers\Api\EmailController::class, 'testEmail']);

// Admin routes
Route::get('/admin/stats', [App\Http\Controllers\Api\AdminController::class, 'getStats']);
Route::get('/admin/participants', [App\Http\Controllers\Api\AdminController::class, 'getParticipants']);
Route::put('/admin/participants/{id}/status', [App\Http\Controllers\Api\AdminController::class, 'updateParticipantStatus']);
Route::get('/admin/blogs', [App\Http\Controllers\Api\AdminController::class, 'getBlogs']);
Route::post('/admin/blogs', [App\Http\Controllers\Api\AdminController::class, 'createBlog']);
Route::put('/admin/blogs/{id}', [App\Http\Controllers\Api\AdminController::class, 'updateBlog']);
Route::delete('/admin/blogs/{id}', [App\Http\Controllers\Api\AdminController::class, 'deleteBlog']);
Route::post('/admin/blogs/{id}/publish', [App\Http\Controllers\Api\AdminController::class, 'publishBlog']);

// Invitation routes
Route::post('/invitations/send', [App\Http\Controllers\Api\InvitationController::class, 'sendInvitation']);
Route::post('/invitations/bulk-send', [App\Http\Controllers\Api\InvitationController::class, 'sendBulkInvitations']);
Route::get('/invitations/download/{participantId}', [App\Http\Controllers\Api\InvitationController::class, 'downloadInvitation']);

// Participant management routes
Route::post('/participants/{id}/send-invitation', [App\Http\Controllers\ParticipantController::class, 'sendInvitation']);
Route::post('/participants/{id}/send-badge', [App\Http\Controllers\ParticipantController::class, 'sendBadge']);
Route::delete('/participants/{id}', [App\Http\Controllers\ParticipantController::class, 'destroy']);
Route::post('/participants/clear-all', [App\Http\Controllers\ParticipantController::class, 'clearAllParticipants']);