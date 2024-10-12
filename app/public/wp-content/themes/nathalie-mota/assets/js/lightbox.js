// Définir la fonction dans le scope global
window.addLightboxEvents = function() {
    let lightbox = document.getElementById('custom-lightbox');
    let lightboxImg = document.getElementById('lightbox-img');
    let caption = document.querySelector('.caption');
    let categoryElement = document.querySelector('.category');
    let currentImageIndex = 0;
    let images = document.querySelectorAll('.photo-item');

    if (!lightbox || !lightboxImg || !caption || !categoryElement || images.length === 0) {
        console.warn('Lightbox elements or images not found');
        return;
    }

    images.forEach(function(image, index) {
        let fullscreenElement = image.querySelector('.fullscreen');
        if (fullscreenElement) {
            fullscreenElement.addEventListener('click', function() {
                currentImageIndex = index;
                let imgSrc = image.getAttribute('data-full-image');
                let imgReference = image.getAttribute('data-reference');
                let imgCategory = image.getAttribute('data-category');
                lightbox.style.display = 'block';
                lightboxImg.src = imgSrc;
                caption.textContent = ' ' + imgReference;
                categoryElement.textContent = '' + imgCategory;
            });
        } else {
            console.warn('Fullscreen element not found for image index ' + index);
        }
    });

    let closeElement = document.querySelector('.close');
    let prevElement = document.querySelector('.custom-prev');
    let nextElement = document.querySelector('.custom-next');

    if (closeElement) {
        closeElement.addEventListener('click', function() {
            lightbox.style.display = 'none';
        });
    } else {
        console.warn('Close element not found');
    }

    if (prevElement) {
        prevElement.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : images.length - 1;
            updateLightbox();
        });
    } else {
        console.warn('Previous element not found');
    }

    if (nextElement) {
        nextElement.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex < images.length - 1) ? currentImageIndex + 1 : 0;
            updateLightbox();
        });
    } else {
        console.warn('Next element not found');
    }

    function updateLightbox() {
        if (currentImageIndex < 0 || currentImageIndex >= images.length) {
            return; // Assurez-vous que l'index est dans les limites du tableau
        }
        let imgSrc = images[currentImageIndex].getAttribute('data-full-image');
        let imgReference = images[currentImageIndex].getAttribute('data-reference');
        let imgCategory = images[currentImageIndex].getAttribute('data-category');
        lightboxImg.src = imgSrc;
        caption.textContent = ' ' + imgReference;
        categoryElement.textContent = '' + imgCategory;
    }

    // Fermer la lightbox en cliquant en dehors de l'image
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox || event.target.classList.contains('close')) {
            lightbox.style.display = 'none';
        }
    });
};
