// Script de débogage pour la fonction "Voir les détails"
// À exécuter dans la console du navigateur sur http://localhost:3000/admin/participants

console.log('🔍 Débogage de la fonction "Voir les détails"');

// 1. Vérifier si les participants sont chargés
console.log('📊 Participants chargés:', participants);
console.log('📊 Nombre de participants:', participants.length);

// 2. Vérifier si la fonction viewParticipant existe
console.log('🔧 Fonction viewParticipant:', typeof viewParticipant);

// 3. Vérifier si le modal existe
const modal = document.getElementById('viewParticipantModal');
console.log('🎭 Modal viewParticipantModal:', modal);

// 4. Vérifier si le contenu du modal existe
const content = document.getElementById('viewParticipantContent');
console.log('📝 Contenu du modal:', content);

// 5. Tester la fonction avec le premier participant
if (participants.length > 0) {
    console.log('🧪 Test avec le premier participant:', participants[0]);
    console.log('🧪 ID du premier participant:', participants[0].id);
    
    // Tester la fonction
    try {
        viewParticipant(participants[0].id);
        console.log('✅ Fonction viewParticipant exécutée avec succès');
    } catch (error) {
        console.error('❌ Erreur lors de l\'exécution de viewParticipant:', error);
    }
} else {
    console.log('❌ Aucun participant chargé');
}

// 6. Vérifier les fonctions auxiliaires
console.log('🔧 Fonction getStatusBadgeClass:', typeof getStatusBadgeClass);
console.log('🔧 Fonction getStatusText:', typeof getStatusText);

// 7. Vérifier Bootstrap
console.log('🎨 Bootstrap disponible:', typeof bootstrap !== 'undefined');

// 8. Vérifier les erreurs dans la console
console.log('📋 Vérifiez s\'il y a des erreurs dans la console ci-dessus');

// 9. Test manuel de la fonction
function testViewParticipant() {
    if (participants.length > 0) {
        const firstParticipant = participants[0];
        console.log('🧪 Test manuel avec participant:', firstParticipant);
        
        // Simuler le clic sur le bouton
        const participant = participants.find(p => p.id === firstParticipant.id);
        if (participant) {
            console.log('✅ Participant trouvé:', participant);
            
            // Générer le contenu
            const content = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <h5 class="mt-2">${participant.first_name} ${participant.last_name}</h5>
                            <span class="badge ${getStatusBadgeClass(participant.status)}">${getStatusText(participant.status)}</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-envelope me-2"></i>Email</h6>
                                <p>${participant.email}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-phone me-2"></i>Téléphone</h6>
                                <p>${participant.phone}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-map-marker-alt me-2"></i>Localisation</h6>
                                <p>${participant.city}, ${participant.country}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-tag me-2"></i>Type</h6>
                                <p>${participant.type === 'national' ? 'National' : 'International'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            console.log('📝 Contenu généré:', content);
            
            // Injecter le contenu dans le modal
            const modalContent = document.getElementById('viewParticipantContent');
            if (modalContent) {
                modalContent.innerHTML = content;
                console.log('✅ Contenu injecté dans le modal');
                
                // Ouvrir le modal
                const modal = new bootstrap.Modal(document.getElementById('viewParticipantModal'));
                modal.show();
                console.log('✅ Modal ouvert');
            } else {
                console.error('❌ Élément viewParticipantContent non trouvé');
            }
        } else {
            console.error('❌ Participant non trouvé');
        }
    } else {
        console.error('❌ Aucun participant disponible pour le test');
    }
}

// Exposer la fonction de test
window.testViewParticipant = testViewParticipant;

console.log('🚀 Pour tester manuellement, exécutez: testViewParticipant()');
