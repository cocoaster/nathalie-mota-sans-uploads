document.addEventListener('DOMContentLoaded', function() {
    const hero = document.querySelector('.hero');
    const wordContainer = document.querySelector('.rotated-word');
    if (!wordContainer) {
        console.error('wordContainer is null');
        return;
    }
    const word = wordContainer.getAttribute('data-word');

    // Function to reveal the hero section
    function revealHero() {
        hero.style.opacity = '1';
        hero.style.transform = 'translateY(0)';
    }

    // Animate letters after the hero section is revealed
    function animateLetters() {
        word.split('').forEach((letter, index) => {
            const span = document.createElement('span');
            span.className = 'letter';
            span.textContent = letter === ' ' ? '\u00A0' : letter; // Use non-breaking space for spaces
            if (letter === ' ') {
                span.classList.add('space');
            }
            wordContainer.appendChild(span);

            if (letter !== ' ') {
                // Delay the animation for each letter
                setTimeout(() => {
                    span.classList.add('visible');
                }, index * 100); // Adjust the delay for the desired effect
            }
        });
    }

    // Reveal the hero section first
    setTimeout(revealHero, 500); // Adjust the delay as needed

    // Animate letters after a slight delay to sync with hero reveal
    setTimeout(animateLetters, 2500); // Adjust the delay to start after hero reveal
});
