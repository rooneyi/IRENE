// Mapping des classes par section
const SECTION_CLASSES = {
    'maternelle': [
        '1ère maternelle',
        '2ème maternelle',
        '3ème maternelle',
    ],
    'primaire': [
        '1ère primaire', '2ème primaire', '3ème primaire', '4ème primaire', '5ème primaire', '6ème primaire'
    ],
    'secondaire_generale': [
        '7ème générale', '8ème générale', '1ère', '2ème', '3ème', '4ème'
    ],
    'technique': [
        '7ème technique', '8ème technique', '1ère', '2ème', '3ème', '4ème'
    ]
};

document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section-select');
    const classeSelect = document.getElementById('classe-select');
    if (!sectionSelect || !classeSelect) return;

    // Mise à jour dynamique des classes selon la section
    sectionSelect.addEventListener('change', function() {
        console.log('Section changée!');
        const sectionId = sectionSelect.value;
        console.log('Section ID:', sectionId);
        console.log('SECTION_KEY_MAP:', window.SECTION_KEY_MAP);
        
        classeSelect.innerHTML = '<option value="">-- Sélectionner la classe --</option>';
        if (!sectionId) {
            console.log('Aucune section sélectionnée');
            return;
        }
        
        if (!window.SECTION_KEY_MAP || !window.SECTION_KEY_MAP[sectionId]) {
            console.error('Mapping manquant pour section:', sectionId);
            alert('Erreur: le mapping SECTION_KEY_MAP est manquant ou invalide pour la section sélectionnée.');
            return;
        }
        
        const key = window.SECTION_KEY_MAP[sectionId];
        console.log('Clé trouvée:', key);
        const classes = SECTION_CLASSES[key] || [];
        console.log('Classes trouvées:', classes);
        
        for (const c of classes) {
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            classeSelect.appendChild(opt);
        }
        console.log('Classes ajoutées au select');
    });

    // Validation UX : impossible de soumettre sans classe
    const form = sectionSelect.closest('form');
    let errorMsg = document.createElement('div');
    errorMsg.className = 'text-red-600 text-sm mt-2';
    errorMsg.style.display = 'none';
    classeSelect.parentNode.appendChild(errorMsg);

    if (form) {
        form.addEventListener('submit', function(e) {
            if(sectionSelect.value && !classeSelect.value) {
                e.preventDefault();
                errorMsg.textContent = 'Veuillez sélectionner une classe associée à la section choisie.';
                errorMsg.style.display = 'block';
                classeSelect.classList.add('border-red-500');
                classeSelect.focus();
            } else {
                errorMsg.style.display = 'none';
                classeSelect.classList.remove('border-red-500');
            }
        });
        sectionSelect.addEventListener('change', function() {
            errorMsg.style.display = 'none';
            classeSelect.classList.remove('border-red-500');
        });
    }
});
