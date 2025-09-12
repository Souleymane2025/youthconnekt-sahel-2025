<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Mail\ParticipantInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvitationController extends Controller
{
    /**
     * Envoyer une invitation individuelle
     */
    public function sendInvitation(Request $request)
    {
        try {
            $participantId = $request->input('participantId');
            
            $participant = Participant::findOrFail($participantId);
            
            // Préparer les données d'invitation
            $invitationData = [
                'event_name' => 'Forum YouthConnekt Sahel 2025',
                'event_date' => '15-17 Mars 2025',
                'event_location' => 'N\'Djamena, Tchad',
                'confirmation_url' => url('/confirm-participation/' . $participant->id),
                'contact_email' => 'contact@youthconnektsahel2025.org',
                'contact_phone' => '+235 66 12 34 56'
            ];
            
            // Envoyer l'email d'invitation
            Mail::to($participant->email)
                ->send(new ParticipantInvitation($participant, $invitationData));
            
            // Mettre à jour le statut d'invitation
            $participant->update([
                'invitation_sent' => true,
                'invitation_sent_at' => now(),
                'status' => 'invited'
            ]);
            
            // Log de l'envoi
            Log::info('Invitation envoyée', [
                'participant_id' => $participant->id,
                'email' => $participant->email,
                'sent_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Invitation officielle envoyée avec succès à {$participant->first_name} {$participant->last_name}",
                'data' => [
                    'participant' => $participant,
                    'email_sent' => true,
                    'sent_at' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi invitation', [
                'participant_id' => $participantId ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'invitation: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Envoyer des invitations en masse
     */
    public function sendBulkInvitations(Request $request)
    {
        try {
            $participantIds = $request->input('participantIds', []);
            
            if (empty($participantIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun participant sélectionné'
                ], 400);
            }
            
            $results = [];
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($participantIds as $participantId) {
                try {
                    $participant = Participant::findOrFail($participantId);
                    
                    // Préparer les données d'invitation
                    $invitationData = [
                        'event_name' => 'Forum YouthConnekt Sahel 2025',
                        'event_date' => '15-17 Mars 2025',
                        'event_location' => 'N\'Djamena, Tchad',
                        'confirmation_url' => url('/confirm-participation/' . $participant->id),
                        'contact_email' => 'contact@youthconnektsahel2025.org',
                        'contact_phone' => '+235 66 12 34 56'
                    ];
                    
                    // Envoyer l'email d'invitation
                    Mail::to($participant->email)
                        ->send(new ParticipantInvitation($participant, $invitationData));
                    
                    // Mettre à jour le statut d'invitation
                    $participant->update([
                        'invitation_sent' => true,
                        'invitation_sent_at' => now(),
                        'status' => 'invited'
                    ]);
                    
                    $results[] = [
                        'participant_id' => $participant->id,
                        'success' => true,
                        'participant' => $participant
                    ];
                    
                    $successCount++;
                    
                    // Log de l'envoi
                    Log::info('Invitation en masse envoyée', [
                        'participant_id' => $participant->id,
                        'email' => $participant->email
                    ]);
                    
                } catch (\Exception $e) {
                    $results[] = [
                        'participant_id' => $participantId,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                    
                    $errorCount++;
                    
                    Log::error('Erreur invitation en masse', [
                        'participant_id' => $participantId,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$successCount} invitations envoyées avec succès, {$errorCount} erreurs",
                'data' => [
                    'total_sent' => $successCount,
                    'total_errors' => $errorCount,
                    'results' => $results
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur invitations en masse', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi des invitations en masse: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Confirmer la participation
     */
    public function confirmParticipation($participantId)
    {
        try {
            $participant = Participant::findOrFail($participantId);
            
            // Mettre à jour le statut de confirmation
            $participant->update([
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);
            
            // Envoyer un email de confirmation
            Mail::to($participant->email)
                ->send(new ParticipantConfirmation($participant));
            
            return view('emails.participant_confirmation', [
                'participant' => $participant,
                'message' => 'Votre participation a été confirmée avec succès !'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur confirmation participation', [
                'participant_id' => $participantId,
                'error' => $e->getMessage()
            ]);
            
            return view('emails.error', [
                'message' => 'Erreur lors de la confirmation de votre participation'
            ]);
        }
    }
    
    /**
     * Générer un badge numérique
     */
    public function generateBadge($participantId)
    {
        try {
            $participant = Participant::findOrFail($participantId);
            
            // Générer un badge numérique (QR code, etc.)
            $badgeData = [
                'participant_id' => $participant->id,
                'name' => $participant->first_name . ' ' . $participant->last_name,
                'email' => $participant->email,
                'country' => $participant->country,
                'type' => $participant->type,
                'generated_at' => now()->toISOString()
            ];
            
            // Sauvegarder le badge
            $badgePath = 'badges/badge_' . $participant->id . '_' . now()->format('Y-m-d') . '.json';
            Storage::put($badgePath, json_encode($badgeData, JSON_PRETTY_PRINT));
            
            return response()->json([
                'success' => true,
                'message' => 'Badge numérique généré avec succès',
                'data' => [
                    'badge_path' => $badgePath,
                    'participant' => $participant,
                    'generated_at' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur génération badge', [
                'participant_id' => $participantId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du badge: ' . $e->getMessage()
            ], 500);
        }
    }
}

