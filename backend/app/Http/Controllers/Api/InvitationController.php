<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ParticipantInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
// use Barryvdh\DomPDF\Facade\Pdf; // Temporairement désactivé

class InvitationController extends Controller
{
    /**
     * Envoyer une invitation par email à un participant
     */
    public function sendInvitation(Request $request)
    {
        try {
            $participantId = $request->input('participant_id');
            $participantData = $request->input('participant_data');
            $invitationData = $request->input('invitation_data', []);

            // Générer le PDF d'invitation
            $pdfPath = $this->generateInvitationPDF($participantData, $invitationData);

            // Envoyer l'email avec le PDF en pièce jointe
            Mail::to($participantData['email'])->send(new ParticipantInvitation($participantData, $invitationData));

            return response()->json([
                'success' => true,
                'message' => 'Invitation envoyée avec succès',
                'data' => [
                    'participant_id' => $participantId,
                    'email' => $participantData['email'],
                    'pdf_path' => $pdfPath
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'invitation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer le PDF d'invitation (version simplifiée)
     */
    private function generateInvitationPDF($participantData, $invitationData)
    {
        // Créer le dossier invitations s'il n'existe pas
        $invitationsDir = storage_path('app/invitations');
        if (!file_exists($invitationsDir)) {
            mkdir($invitationsDir, 0755, true);
        }

        // Pour l'instant, créer un fichier HTML qui peut être converti en PDF
        $htmlContent = view('emails.participant_invitation', [
            'participant' => $participantData,
            'invitationData' => $invitationData
        ])->render();

        $filename = 'invitation_' . $participantData['id'] . '_' . time() . '.html';
        $filepath = $invitationsDir . '/' . $filename;

        file_put_contents($filepath, $htmlContent);

        // Créer aussi un fichier texte simple
        $textContent = $this->generateTextInvitation($participantData, $invitationData);
        $textFilename = 'invitation_' . $participantData['id'] . '_' . time() . '.txt';
        $textFilepath = $invitationsDir . '/' . $textFilename;
        file_put_contents($textFilepath, $textContent);

        return $filepath;
    }

    /**
     * Générer une invitation en texte simple
     */
    private function generateTextInvitation($participantData, $invitationData)
    {
        return "
INVITATION OFFICIELLE
Forum YouthConnekt Sahel 2025
2ème Édition - Forum de la Jeunesse du Sahel

Cher(e) {$participantData['first_name']} {$participantData['last_name']},

Nous avons le plaisir de vous inviter officiellement au Forum YouthConnekt Sahel 2025, 
qui se tiendra sous le haut patronage du Président de la République du Tchad.

Vos informations de participation:
- Nom complet: {$participantData['first_name']} {$participantData['last_name']}
- Email: {$participantData['email']}
- Téléphone: " . ($participantData['phone'] ?? 'Non renseigné') . "
- Pays: " . ($participantData['country'] ?? 'Non renseigné') . "
- Ville: " . ($participantData['city'] ?? 'Non renseigné') . "
- Type d'inscription: " . ucfirst($participantData['registration_type'] ?? 'standard') . "
- Statut: ✅ Confirmé

Détails de l'événement:
- Titre: Forum YouthConnekt Sahel 2025
- Dates: " . ($invitationData['event_date'] ?? 'À confirmer') . "
- Lieu: " . ($invitationData['event_location'] ?? 'N\'Djamena, Tchad') . "

Prochaines étapes:
1. Confirmez votre participation en répondant à cet email
2. Consultez le programme détaillé sur notre site web
3. Préparez-vous pour des échanges enrichissants avec les leaders de demain

Organisé par YouthConnekt Tchad
Partenaire Officiel: PNUD Tchad
Sous le haut patronage du Président de la République du Tchad

Contact:
📧 contact@youthconnekt.td
📞 +235 66 16 17 53
🌐 www.youthconnekt-sahel.td

Merci de votre participation!
L'équipe YouthConnekt Sahel 2025
        ";
    }

    /**
     * Télécharger le PDF d'invitation
     */
    public function downloadInvitation($participantId)
    {
        try {
            $filepath = storage_path('app/invitations/invitation_' . $participantId . '.pdf');
            
            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF d\'invitation non trouvé'
                ], 404);
            }

            return response()->download($filepath, 'Invitation_YouthConnekt_Sahel_2025.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer plusieurs invitations en lot
     */
    public function sendBulkInvitations(Request $request)
    {
        try {
            $participants = $request->input('participants', []);
            $invitationData = $request->input('invitation_data', []);
            $results = [];

            foreach ($participants as $participant) {
                try {
                    // Générer le PDF
                    $pdfPath = $this->generateInvitationPDF($participant, $invitationData);

                    // Envoyer l'email
                    Mail::to($participant['email'])->send(new ParticipantInvitation($participant, $invitationData));

                    $results[] = [
                        'participant_id' => $participant['id'],
                        'email' => $participant['email'],
                        'status' => 'success'
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'participant_id' => $participant['id'],
                        'email' => $participant['email'],
                        'status' => 'error',
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Traitement des invitations terminé',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement en lot: ' . $e->getMessage()
            ], 500);
        }
    }
}
