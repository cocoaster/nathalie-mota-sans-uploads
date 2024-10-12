document.addEventListener('DOMContentLoaded', function () {
    // Variables globales
    let totalPhotos = 0;  // Nombre total de photos disponibles
    let loadedPhotos = 0; // Nombre de photos déjà chargées
    const loadMoreButton = document.getElementById('load-more'); // Référence au bouton "Charger plus"

    // Fonction pour charger les photos via AJAX
    function loadPhotos(resetFilters = false) {
        const category = resetFilters ? '' : document.getElementById('category-filter').value || '';
        const format = resetFilters ? '' : document.getElementById('format-filter').value || '';
        const order = resetFilters ? 'DESC' : document.getElementById('order-filter').value || 'DESC';

        const data = new URLSearchParams({
            action: 'filter_photos',
            category: category,
            format: format,
            order: order,
        });

        console.log('Données envoyées pour filtrage:', data.toString());

        fetch(nathalie_mota_ajax.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data.toString(),
        })
        .then(response => response.json())
        .then(responseData => {
            document.getElementById('photo-list').innerHTML = responseData.html;
            totalPhotos = responseData.total; 
            loadedPhotos = document.querySelectorAll('#photo-list .photo-item').length; 
            addLightboxEvents(); 

            console.log(`Nombre total de photos pour le filtre [${category || 'Toutes catégories'}] et [${format || 'Tous formats'}]:`, totalPhotos);
            console.log('Nombre de photos actuellement chargées:', loadedPhotos);

            if (loadedPhotos < totalPhotos) {
                loadMoreButton.style.display = 'block'; 
            } else {
                loadMoreButton.style.display = 'none'; 
            }

            if (resetFilters) {
                resetFilterSelectors();
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des photos:', error);
        });
    }

    // Fonction pour réinitialiser les sélecteurs de filtres
    function resetFilterSelectors() {
        document.getElementById('category-filter').value = '';
        document.getElementById('format-filter').value = '';
        document.getElementById('order-filter').value = 'DESC';
        
        document.querySelectorAll('.select-selected').forEach((element) => {
            const defaultText = element.nextElementSibling.querySelector('option[selected]').textContent;
            element.textContent = defaultText;
        });

        document.querySelectorAll('.select-items .same-as-selected').forEach((element) => {
            element.classList.remove('same-as-selected');
        });

        // Réaffiche toutes les options
        document.querySelectorAll('.select-items div').forEach((item) => {
            item.style.display = 'block';
        });
    }

    // Écouteurs d'événements pour les filtres
    document.querySelectorAll('.select-items div').forEach((item) => {
        item.addEventListener('click', function() {
            const selectEl = this.parentNode.parentNode.querySelector('select');
            const currentValue = selectEl.value;
            const selectedValue = this.getAttribute('data-value');
            
            if (selectedValue === '') {
                // Si le placeholder est sélectionné, réinitialise le filtre
                resetFilterSelectors();
            } else {
                // Cache l'élément sélectionné
                document.querySelectorAll('.filter-name').forEach((i) => {
                    if (i.getAttribute('data-value') === selectedValue) {
                        i.style.display = 'none';
                    } else {
                        i.style.display = 'block';
                    }
                });
            }

            this.parentNode.previousSibling.textContent = this.textContent;
            loadPhotos(true);
        });
    });

    document.getElementById('order-filter').addEventListener('change', function() {
        sortPhotos(); 
    });

    // Fonction pour trier les photos chargées
    function sortPhotos() {
        const order = document.getElementById('order-filter').value || 'DESC';
        const photoList = document.getElementById('photo-list');
        const photosArray = Array.from(photoList.querySelectorAll('.photo-item'));

        photosArray.sort((a, b) => {
            const dateA = new Date(a.getAttribute('data-date')); 
            const dateB = new Date(b.getAttribute('data-date'));

            return order === 'ASC' ? dateA - dateB : dateB - dateA;
        });

        photosArray.forEach(photo => photoList.appendChild(photo));
    }

    // Événement du bouton "Charger plus"
    document.getElementById('load-more').addEventListener('click', function() {
        const offset = document.querySelectorAll('#photo-list .photo-item').length;

        if (loadedPhotos >= totalPhotos) {
            loadMoreButton.style.display = 'none';
            return;
        }

        const category = document.getElementById('category-filter').value || '';
        const format = document.getElementById('format-filter').value || '';
        const order = document.getElementById('order-filter').value || 'DESC';

        const data = new URLSearchParams({
            action: 'load_more_photos',
            offset: offset,
            category: category,
            format: format,
            order: order,
        });

        console.log('Données envoyées pour charger plus:', data.toString());

        fetch(nathalie_mota_ajax.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data.toString(),
        })
        .then(response => response.json())
        .then(responseData => {
            const photoList = document.getElementById('photo-list');
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = responseData.html;

            tempDiv.querySelectorAll('.photo-item').forEach(photoItem => {
                photoList.appendChild(photoItem);
            });

            loadedPhotos += responseData.loaded; 

            console.log('Photos chargées après clic sur Charger plus:', loadedPhotos);

            if (loadedPhotos >= totalPhotos) {
                loadMoreButton.style.display = 'none'; 
            }

            addLightboxEvents(); 
        }) 
        .catch(error => {
            console.error('Erreur lors du chargement des photos:', error);
        });
    });

    loadPhotos();

    // Personnalisation des sélecteurs
    const customSelects = document.getElementsByClassName("custom-select");
    for (let i = 0; i < customSelects.length; i++) {
        const selElmnt = customSelects[i].getElementsByTagName("select")[0];
        const a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        customSelects[i].appendChild(a);

        const b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (let j = 0; j < selElmnt.length; j++) {
            const c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.setAttribute('data-value', selElmnt.options[j].value);
            c.addEventListener("click", function(e) {
                const s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                const h = this.parentNode.previousSibling;
                for (let i = 0; i < s.length; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        const y = this.parentNode.getElementsByClassName("same-as-selected");
                        for (let k = 0; k < y.length; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                    }
                }
                h.click();
                loadPhotos();
            });
            b.appendChild(c);
        }
        customSelects[i].appendChild(b);

        a.addEventListener("click", function(e) {
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }

    function closeAllSelect(elmnt) {
        const x = document.getElementsByClassName("select-items");
        const y = document.getElementsByClassName("select-selected");
        const arrNo = [];
        for (let i = 0; i < y.length; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i);
            } else {
                y[i].classList.remove('select-arrow-active');
            }
        }
        for (let i = 0; i < x.length; i++) {
            if (arrNo.indexOf(i) === -1) {
                x[i].classList.add('select-hide');
            }
        }
    }

    document.addEventListener('click', closeAllSelect);
});
