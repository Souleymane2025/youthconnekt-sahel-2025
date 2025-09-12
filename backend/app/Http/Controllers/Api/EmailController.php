<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PartnerConfirmation;
use App\Mail\StandConfirmation;
use App\Mail\ContactConfirmation;
use App\Mail\ParticipantConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Envoyer un email de confirmation pour un partenaire
     */
    public function sendPartnerConfirmation(Request $request)
    {
        try {
            $data = $request->validate([
                'type' => 'required|in:partner,sponsor',
                'companyName' => 'required|string|max:255',
                'contactPerson' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'partnershipType' => 'nullable|string|max:100',
                'sponsorshipLevel' => 'nullable|string|max:100',
                'budget' => 'nullable|numeric',
                'description' => 'required|string',
                'website' => 'nullable|url',
                'address' => 'nullable|string',
            ]);

            // Envoyer l'email
            Mail::to($data['email'])->send(new PartnerConfirmation($data, $data['type']));

            // Log de l'envoi
            Log::info('Email de confirmation partenaire envoyé', [
                'email' => $data['email'],
                'type' => $data['type'],
                'company' => $data['companyName']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email de confirmation envoyé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email partenaire', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer un email de confirmation pour un stand
     */
    public function sendStandConfirmation(Request $request)
    {
        try {
            $data = $request->validate([
                'companyName' => 'required|string|max:255',
                'contactPerson' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'standType' => 'required|string|max:100',
                'standSize' => 'nullable|string|max:50',
                'budget' => 'nullable|numeric',
                'description' => 'required|string',
                'products' => 'nullable|string',
                'expectedVisitors' => 'nullable|integer',
                'additionalServices' => 'nullable|string',
                'website' => 'nullable|url',
                'address' => 'nullable|string',
            ]);

            // Envoyer l'email
            Mail::to($data['email'])->send(new StandConfirmation($data));

            // Log de l'envoi
            Log::info('Email de confirmation stand envoyé', [
                'email' => $data['email'],
                'company' => $data['companyName'],
                'standType' => $data['standType']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email de confirmation envoyé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email stand', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer un email de confirmation pour un message de contact
     */
    public function sendContactConfirmation(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string',
            ]);

            // Envoyer l'email
            Mail::to($data['email'])->send(new ContactConfirmation($data));

            // Log de l'envoi
            Log::info('Email de confirmation contact envoyé', [
                'email' => $data['email'],
                'name' => $data['name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email de confirmation envoyé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email contact', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer un email de confirmation pour un participant
     */
    public function sendParticipantConfirmation(Request $request)
    {
        try {
            $data = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'registration_type' => 'required|string|max:50',
            ]);

            // Envoyer l'email
            Mail::to($data['email'])->send(new ParticipantConfirmation($data));

            // Log de l'envoi
            Log::info('Email de confirmation participant envoyé', [
                'email' => $data['email'],
                'name' => $data['first_name'] . ' ' . $data['last_name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email de confirmation envoyé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email participant', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester la configuration email
     */
    public function testEmail(Request $request)
    {
        try {
            $testEmail = $request->input('email', 'souleymanemhamatsaleh2000@gmail.com');
            
            Mail::to($testEmail)->send(new ContactConfirmation([
                'name' => 'Test Email',
                'email' => $testEmail,
                'message' => 'Ceci est un test de configuration email pour YouthConnekt Sahel 2025.'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Email de test envoyé avec succès à ' . $testEmail
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test email: ' . $e->getMessage()
            ], 500);
        }
    }
}

