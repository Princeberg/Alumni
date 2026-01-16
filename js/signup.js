document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    // --- NAVIGATION AVEC VALIDATION PAR POPUP ---

    document.getElementById('next-to-step2').addEventListener('click', function() {
        if (validateStep(1)) {
            navigateToStep(2);
        }
    });

    document.getElementById('next-to-step3').addEventListener('click', function() {
        if (validateStep(2)) {
            navigateToStep(3);
        }
    });

    document.getElementById('prev-to-step1').addEventListener('click', () => navigateToStep(1));
    document.getElementById('prev-to-step2').addEventListener('click', () => navigateToStep(2));

    /**
     * Valide les champs et affiche un POPUP en cas d'erreur
     */
    function validateStep(stepNumber) {
        const stepEl = document.getElementById(`step${stepNumber}`);
        const requiredInputs = stepEl.querySelectorAll('input[required], select[required]');
        let missingFields = false;

        requiredInputs.forEach(input => {
            if (input.type === 'radio') {
                const name = input.getAttribute('name');
                const checked = stepEl.querySelector(`input[name="${name}"]:checked`);
                if (!checked) missingFields = true;
            } else if (!input.value.trim()) {
                missingFields = true;
                input.style.border = "1px solid red"; // Feedback visuel direct
            } else {
                input.style.border = ""; 
            }
        });

        if (missingFields) {
            // AFFICHAGE DU POPUP D'ERREUR
            Swal.fire({
                icon: 'error',
                title: 'Champs incomplets',
                text: 'Veuillez remplir tous les champs obligatoires (*) avant de passer à l\'étape suivante.',
                confirmButtonColor: '#012587',
                confirmButtonText: 'D\'accord'
            });
            return false;
        }
        return true;
    }

    /**
     * Logique de changement d'étape (inchangée)
     */
    function navigateToStep(step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));

        const targetStep = document.getElementById(`step${step}`);
        if (targetStep) targetStep.classList.add('active');

        for (let i = 1; i <= totalSteps; i++) {
            const indicator = document.getElementById(`step${i}-indicator`);
            if (indicator) {
                if (i === step) indicator.classList.add('active');
                else if (i < step) indicator.classList.add('completed');
                else indicator.classList.remove('active', 'completed');
            }
        }
        currentStep = step;

        // Gestion du bouton final
        const submitBtn = document.querySelector('.btn-submit');
        const nextBtnStep2 = document.getElementById('next-to-step3');
        if (step === totalSteps) {
            if (submitBtn) submitBtn.style.display = 'block';
            if (nextBtnStep2) nextBtnStep2.style.display = 'none';
        } else {
            if (submitBtn) submitBtn.style.display = 'none';
        }
    }
});