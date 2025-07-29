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

window.updateClasseOptions = function() {
    const sectionSelect = document.getElementById('section-select');
    const classeSelect = document.getElementById('classe-select');
    if (!sectionSelect || !classeSelect) return;
    const sectionId = sectionSelect.value;
    classeSelect.innerHTML = '<option value="">-- Sélectionner la classe --</option>';
    if (!sectionId) return;
    const classes = SECTION_CLASSES[sectionId] || [];
    for (const c of classes) {
        const opt = document.createElement('option');
        opt.value = c;
        opt.textContent = c;
        classeSelect.appendChild(opt);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section-select');
    if (sectionSelect) {
        sectionSelect.addEventListener('change', window.updateClasseOptions);
        window.updateClasseOptions();
    }
});
