jQuery(document).ready(function($) {
    console.log('jQuery is loaded');

    // Créer la prévisualisation de la vignette
    const thumbnailPreview = $('<div id="thumbnail-preview" style="position: absolute; bottom: 40px; right: 40px; display: none;"><img id="thumbnail-image" src="" alt="Thumbnail" style="width: 100px; height: auto; border: 1px solid #ccc; box-shadow: 0px 0px 6px rgba(0,0,0,0.5);" /></div>');
    $('body').append(thumbnailPreview);

    // Vérifier si les éléments existent
    const prevLink = $('.navigation-links .prev');
    const nextLink = $('.navigation-links .next');

    if (prevLink.length) {
        prevLink.hover(
            function() {
                const thumbnailSrc = $(this).data('thumbnail');
                $('#thumbnail-image').attr('src', thumbnailSrc);
                $('#thumbnail-preview').css('display', 'block');
            },
            function() {
                $('#thumbnail-preview').css('display', 'none');
            }
        );
    } else {
        console.warn('Previous link not found');
    }

    if (nextLink.length) {
        nextLink.hover(
            function() {
                const thumbnailSrc = $(this).data('thumbnail');
                $('#thumbnail-image').attr('src', thumbnailSrc);
                $('#thumbnail-preview').css('display', 'block');
            },
            function() {
                $('#thumbnail-preview').css('display', 'none');
            }
        );
    } else {
        console.warn('Next link not found');
    }

    const photoItems = document.querySelectorAll('#related-photos .photo-item');
    
    photoItems.forEach(item => {
        const lightboxIcon = item.querySelector('.lightbox-icon');
        const infoIcon = item.querySelector('.info-icon');
        
        if (lightboxIcon && infoIcon) {
            item.addEventListener('mouseover', () => {
                lightboxIcon.style.display = 'block';
                infoIcon.style.display = 'block';
            });

            item.addEventListener('mouseout', () => {
                lightboxIcon.style.display = 'none';
                infoIcon.style.display = 'none';
            });
        } else {
            console.warn('Lightbox or info icon not found for item');
        }
    });

    // Initialiser la lightbox
    if (typeof addLightboxEvents === 'function') {
        addLightboxEvents();
    } else {
        console.error('addLightboxEvents function is not defined');
    }
});
