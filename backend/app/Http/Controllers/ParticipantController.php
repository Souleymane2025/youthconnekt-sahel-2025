<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Participant::query();
        
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $participants = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $participants->items(),
            'total' => $participants->total(),
            'current_page' => $participants->currentPage(),
            'last_page' => $participants->lastPage()
        ]);
    }
    
    public function store(Request $request)
    {
        try {
            // Generate unique participant ID
            $participantId = $this->generateParticipantId();
            
            // Validate request
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:participants,email',
                'phone' => 'required|string|max:20',
                'whatsapp' => 'required|string|max:20',
                'birth_date' => 'required|date',
                'gender' => 'required|in:male,female,other,prefer-not-to-say',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'nullable|string|max:255',
                'occupation' => 'required|string|max:255',
                'organization' => 'nullable|string|max:255',
                'interests' => 'required|array',
                'experience' => 'nullable|string|max:1000',
                'motivation' => 'nullable|string|max:1000',
                'handicap' => 'nullable|in:no,yes,prefer-not-to-say',
                'handicap_type' => 'nullable|string|max:255',
                'handicap_accommodation' => 'nullable|string|max:1000',
                'registration_type' => 'required|in:national,international',
                'terms' => 'required|accepted',
                'newsletter' => 'boolean',
                'photos' => 'boolean'
            ]);
            
            // Handle file uploads
            $photoPath = null;
            $passportPath = null;
            $cvPath = null;
            
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('participants/photos', 'public');
            }
            
            if ($request->hasFile('passport')) {
                $passportPath = $request->file('passport')->store('participants/documents', 'public');
            }
            
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('participants/documents', 'public');
            }
            
            // Create participant
            $participant = Participant::create([
                'participant_id' => $participantId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'whatsapp' => $validated['whatsapp'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'country' => $validated['country'],
                'city' => $validated['city'],
                'province' => $validated['province'] ?? null,
                'occupation' => $validated['occupation'],
                'organization' => $validated['organization'] ?? null,
                'interests' => json_encode($validated['interests']),
                'experience' => $validated['experience'] ?? null,
                'motivation' => $validated['motivation'] ?? null,
                'handicap' => $validated['handicap'] ?? 'no',
                'handicap_type' => $validated['handicap_type'] ?? null,
                'handicap_accommodation' => $validated['handicap_accommodation'] ?? null,
                'registration_type' => $validated['registration_type'],
                'photo_path' => $photoPath,
                'passport_path' => $passportPath,
                'cv_path' => $cvPath,
                'status' => 'pending',
                'newsletter' => $validated['newsletter'] ?? false,
                'photos_consent' => $validated['photos'] ?? false
            ]);
            
            // Generate PDF profile
            $pdfPath = $this->generateParticipantPDF($participant);
            $participant->update(['pdf_path' => $pdfPath]);
            
            // Generate personalized badge
            $badgePath = $this->generateParticipantBadge($participant);
            $participant->update(['badge_path' => $badgePath]);
            
            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie !',
                'data' => [
                    'participant_id' => $participantId,
                    'participant' => $participant
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generateParticipantId()
    {
        $timestamp = now()->format('ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return "FYCS2025{$timestamp}{$random}";
    }
    
    private function generateParticipantPDF($participant)
    {
        try {
            $pdf = Pdf::loadView('participant-profile', compact('participant'));
            $filename = "participant-{$participant->participant_id}.pdf";
            $path = "participants/profiles/{$filename}";
            
            Storage::disk('public')->put($path, $pdf->output());
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    private function generateParticipantBadge($participant)
    {
        try {
            // Create badge using GD library
            $width = 400;
            $height = 300;
            $image = imagecreatetruecolor($width, $height);
            
            // Background colors
            $bgColor = imagecolorallocate($image, 0, 123, 255); // Blue
            $textColor = imagecolorallocate($image, 255, 255, 255); // White
            $accentColor = imagecolorallocate($image, 255, 193, 7); // Yellow
            
            // Fill background
            imagefill($image, 0, 0, $bgColor);
            
            // Add border
            imagerectangle($image, 5, 5, $width-6, $height-6, $accentColor);
            
            // Add logo area (placeholder)
            $logoX = 20;
            $logoY = 20;
            $logoSize = 60;
            imagefilledrectangle($image, $logoX, $logoY, $logoX + $logoSize, $logoY + $logoSize, $accentColor);
            
            // Add text
            $font = 4; // Built-in font
            $fontLarge = 5;
            
            // Event title
            imagestring($image, $fontLarge, 100, 30, 'YouthConnekt Sahel 2025', $textColor);
            
            // Participant name
            $name = $participant->first_name . ' ' . $participant->last_name;
            imagestring($image, $fontLarge, 100, 60, $name, $textColor);
            
            // Participant ID
            imagestring($image, $font, 100, 90, $participant->participant_id, $accentColor);
            
            // Country and type
            $location = $participant->country;
            if ($participant->province) {
                $location .= ', ' . $participant->province;
            }
            imagestring($image, $font, 100, 120, $location, $textColor);
            
            $type = ucfirst($participant->registration_type);
            imagestring($image, $font, 100, 140, $type, $textColor);
            
            // Event details
            imagestring($image, $font, 100, 180, '13-15 Octobre 2025', $textColor);
            imagestring($image, $font, 100, 200, 'N\'Djamena, Tchad', $textColor);
            
            // Add participant photo if available
            if ($participant->photo_path && Storage::disk('public')->exists($participant->photo_path)) {
                $photoPath = Storage::disk('public')->path($participant->photo_path);
                $photo = imagecreatefromjpeg($photoPath);
                if ($photo) {
                    $photoResized = imagescale($photo, 80, 80);
                    imagecopy($image, $photoResized, 20, 100, 0, 0, 80, 80);
                    imagedestroy($photo);
                    imagedestroy($photoResized);
                }
            }
            
            // Save badge
            $filename = "badge-{$participant->participant_id}.png";
            $path = "participants/badges/{$filename}";
            
            $fullPath = Storage::disk('public')->path($path);
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            imagepng($image, $fullPath);
            imagedestroy($image);
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Badge generation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    public function show($id)
    {
        $participant = Participant::where('participant_id', $id)->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => $participant
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $participant = Participant::where('participant_id', $id)->firstOrFail();
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,rejected'
        ]);
        
        $participant->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'data' => $participant
        ]);
    }
    
    public function downloadPDF($id)
    {
        $participant = Participant::where('participant_id', $id)->firstOrFail();
        
        if (!$participant->pdf_path || !Storage::disk('public')->exists($participant->pdf_path)) {
            return response()->json(['error' => 'PDF non trouvé'], 404);
        }
        
        return Storage::disk('public')->download($participant->pdf_path);
    }
    
    public function downloadBadge($id)
    {
        $participant = Participant::where('participant_id', $id)->firstOrFail();
        
        if (!$participant->badge_path || !Storage::disk('public')->exists($participant->badge_path)) {
            return response()->json(['error' => 'Badge non trouvé'], 404);
        }
        
        return Storage::disk('public')->download($participant->badge_path);
    }
    
    public function sendInvitation($id)
    {
        try {
            $participant = Participant::findOrFail($id);
            
            // Générer l'email d'invitation
            $emailData = $this->generateInvitationEmail($participant);
            
            // Simuler l'envoi d'email (à remplacer par un vrai service)
            $this->sendInvitationEmail($emailData);
            
            // Mettre à jour le statut
            $participant->update([
                'status' => 'invited',
                'invitation_sent_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Invitation envoyée avec succès',
                'data' => [
                    'participant' => $participant,
                    'email_sent_to' => $participant->email
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'invitation: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function sendBadge($id)
    {
        try {
            $participant = Participant::findOrFail($id);
            
            if (!$participant->badge_path) {
                // Générer le badge s'il n'existe pas
                $badgePath = $this->generateParticipantBadge($participant);
                $participant->update(['badge_path' => $badgePath]);
            }
            
            // Simuler l'envoi du badge par email
            $this->sendBadgeEmail($participant);
            
            // Mettre à jour le statut
            $participant->update([
                'status' => 'badge_sent',
                'badge_sent_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Badge envoyé avec succès',
                'data' => [
                    'participant' => $participant,
                    'badge_sent_to' => $participant->email
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du badge: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $participant = Participant::findOrFail($id);
            
            // Supprimer les fichiers associés
            if ($participant->photo_path && Storage::disk('public')->exists($participant->photo_path)) {
                Storage::disk('public')->delete($participant->photo_path);
            }
            
            if ($participant->passport_path && Storage::disk('public')->exists($participant->passport_path)) {
                Storage::disk('public')->delete($participant->passport_path);
            }
            
            if ($participant->cv_path && Storage::disk('public')->exists($participant->cv_path)) {
                Storage::disk('public')->delete($participant->cv_path);
            }
            
            if ($participant->pdf_path && Storage::disk('public')->exists($participant->pdf_path)) {
                Storage::disk('public')->delete($participant->pdf_path);
            }
            
            if ($participant->badge_path && Storage::disk('public')->exists($participant->badge_path)) {
                Storage::disk('public')->delete($participant->badge_path);
            }
            
            // Supprimer le participant
            $participant->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Participant supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function clearAllParticipants()
    {
        try {
            $count = Participant::count();
            
            if ($count === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aucun participant à supprimer',
                    'deleted_count' => 0
                ]);
            }
            
            // Supprimer tous les participants
            Participant::truncate();
            
            return response()->json([
                'success' => true,
                'message' => "$count participants supprimés avec succès",
                'deleted_count' => $count
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generateInvitationEmail($participant)
    {
        $subject = "🎉 Invitation Officielle - YouthConnekt Sahel 2025";
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background: linear-gradient(135deg, #2E7D32, #4CAF50); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .highlight { background: #f8f9fa; padding: 15px; border-left: 4px solid #2E7D32; margin: 15px 0; }
                .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
                .btn { background: #2E7D32; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>🎉 YouthConnekt Sahel 2025</h1>
                <p>Invitation Officielle</p>
            </div>
            
            <div class='content'>
                <h2>Cher(e) {$participant->first_name} {$participant->last_name},</h2>
                
                <p>Nous avons le plaisir de vous confirmer votre inscription au <strong>Forum YouthConnekt Sahel 2025</strong> qui se tiendra du <strong>13 au 15 Octobre 2025</strong> à <strong>N'Djamena, Tchad</strong>.</p>
                
                <div class='highlight'>
                    <h3>📋 Détails de votre inscription:</h3>
                    <ul>
                        <li><strong>Nom:</strong> {$participant->first_name} {$participant->last_name}</li>
                        <li><strong>Email:</strong> {$participant->email}</li>
                        <li><strong>Pays:</strong> {$participant->country}</li>
                        <li><strong>Ville:</strong> {$participant->city}</li>
                        <li><strong>Statut:</strong> {$participant->occupation}</li>
                        <li><strong>Organisation:</strong> " . ($participant->organization ?? 'Non spécifié') . "</li>
                    </ul>
                </div>
                
                <h3>🎯 Prochaines étapes:</h3>
                <ol>
                    <li><strong>Confirmation:</strong> Votre inscription a été validée</li>
                    <li><strong>Badge:</strong> Vous recevrez votre badge numérique dans les prochains jours</li>
                    <li><strong>Programme:</strong> Le programme détaillé sera disponible sur notre site</li>
                    <li><strong>Logistique:</strong> Les informations pratiques vous seront envoyées</li>
                </ol>
                
                <h3>📅 Informations importantes:</h3>
                <ul>
                    <li><strong>Dates:</strong> 13-15 Octobre 2025</li>
                    <li><strong>Lieu:</strong> N'Djamena, Tchad</li>
                    <li><strong>Thème:</strong> Connectons, innovons et transformons ensemble l'avenir du Sahel</li>
                </ul>
                
                <p>Nous sommes ravis de vous accueillir à cet événement historique qui rassemblera plus de 2000 jeunes leaders du Sahel.</p>
                
                <p>Pour toute question, n'hésitez pas à nous contacter.</p>
                
                <p>Cordialement,<br>
                <strong>L'équipe YouthConnekt Sahel 2025</strong></p>
            </div>
            
            <div class='footer'>
                <p>YouthConnekt Sahel 2025 | N'Djamena, Tchad | 13-15 Octobre 2025</p>
                <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            </div>
        </body>
        </html>";
        
        return [
            'subject' => $subject,
            'message' => $message,
            'to' => $participant->email,
            'participant_name' => $participant->first_name . ' ' . $participant->last_name
        ];
    }
    
    private function sendInvitationEmail($emailData)
    {
        // Ici vous pouvez intégrer un vrai service d'email comme SendGrid, Mailgun, etc.
        \Log::info('Invitation email sent', [
            'to' => $emailData['to'],
            'subject' => $emailData['subject'],
            'participant' => $emailData['participant_name']
        ]);
        
        return true;
    }
    
    private function sendBadgeEmail($participant)
    {
        // Simuler l'envoi du badge par email
        \Log::info('Badge email sent', [
            'to' => $participant->email,
            'participant' => $participant->first_name . ' ' . $participant->last_name,
            'badge_path' => $participant->badge_path
        ]);
        
        return true;
    }
}







