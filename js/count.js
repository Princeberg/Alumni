document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne toutes les cartes de statistiques
    const statCards = document.querySelectorAll('.stat-card');

    const animateValue = (element, start, end, duration, prefix = '', suffix = '') => {
        let startTimestamp = null;
        
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            // Calcul avec effet easing (démarrage rapide, fin douce)
            const easeOutQuad = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.floor(easeOutQuad * (end - start) + start);
            
            // Formatage du nombre (ex: 25000 -> 25 000)
            const formattedValue = currentValue.toLocaleString('fr-FR');
            
            element.textContent = `${prefix}${formattedValue}${suffix}`;

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                // S'assure que la valeur finale exacte est affichée
                element.textContent = `${prefix}${end.toLocaleString('fr-FR')}${suffix}`;
            }
        };

        window.requestAnimationFrame(step);
    };

    // IntersectionObserver pour déclencher l'animation uniquement quand c'est visible
    const observer = new IntersectionObserver((entries, observerInstance) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const h3 = entry.target.querySelector('h3');
                if (!h3) return;

                const targetAttr = entry.target.getAttribute('data-target');
                const targetValue = parseInt(targetAttr, 10);
                const prefix = entry.target.getAttribute('data-prefix') || '';
                const suffix = entry.target.getAttribute('data-suffix') || '';

                if (!isNaN(targetValue)) {
                    animateValue(h3, 0, targetValue, 2000, prefix, suffix);
                }

                // Stoppe l'observation une fois l'animation jouée
                observerInstance.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.3 // Se déclenche quand 30% de l'élément est visible
    });

    statCards.forEach(card => observer.observe(card));
});