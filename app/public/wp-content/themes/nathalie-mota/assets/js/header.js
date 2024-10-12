document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navigation = document.querySelector('.main-navigation');
    const hamburgerIcon = document.querySelector('.hamburger-icon');

    if (hamburger && navigation && hamburgerIcon) {
        console.log('Elements found:', hamburger, navigation, hamburgerIcon);
        
        hamburger.addEventListener('click', function() {
            console.log('Hamburger clicked');
            navigation.classList.toggle('active');
            hamburgerIcon.classList.toggle('open');
            console.log('Classes after click:', navigation.classList, hamburgerIcon.classList);
        });
    } else {
        console.warn('One or more elements not found:', {
            hamburger,
            navigation,
            hamburgerIcon
        });
    }
});




