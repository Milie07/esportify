    
/* VALIDATION DES FORMULAIRES
    Première couche de sécurité front 
    sf Entité HTML MDN */

// éviter la balise <script> dans les inputs
function sanitizeInput(str) { 
    return str  
        .replace(/&/g, "&amp;") 
        .replace(/</g, "&lt;") 
        .replace(/>/g, "&gt;") 
        .replace(/"/g, "&quot;") 
        .replace(/'/g, "&#039;"); 
}

// Vérification du mail et du password dans les formulaires
function isValidEmail(email){
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return email.match(emailRegex) !== null;
};

function validatePassword(input){
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/; //mdp qui prends minimum 8 caractères avec au moins 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial non alphanumérique
    const passwordUser = input.value;
    if (passwordUser.match(passwordRegex)){
        input?.classList.add("is-valid");
        input?.classList.remove("is-invalid");
        return true;
    } else {
        input?.classList.remove("is-valid");
        input?.classList.add("is-invalid");
    }
}
function validateConfirmationPassword(pwdInput, confirmPwdInput) {
    if(!pwdInput || !confirmPwdInput) return false;

    if(pwdInput.value === confirmPwdInput.value && confirmPwdInput.value !== "") {
        confirmPwdInput.classList.add("is-valid");
        confirmPwdInput.classList.remove("is-invalid");
        return true;        
    } else {
        confirmPwdInput.classList.add("is-invalid");
        confirmPwdInput.classList.remove("is-valid");
        return false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const inputs = document.querySelectorAll('input[type="text"], input[type="search"], input[type="email"], input[type="password"]');

    
    if (form && inputs.length > 0) {
        form.addEventListener('submit', (e) => { 
        e.preventDefault(); 

        const pwdInput = document.getElementById('password');
        const confirmPwdInput = document.getElementById('confirmPassword');
        
        inputs.forEach(input => {
            const raw = input.value; // version tapée
            const safe = sanitizeInput(raw);  // version nettoyée

            // Validation de l'email
            if(input.type === "email") {
                const email = input.value.trim();
                if (isValidEmail(email)) {
                    input.classList.add("is-valid");
                    input.classList.remove("is-invalid");
                    console.log("Adresse Valide : ", email);
                } else {
                    input.classList.add("is-invalid");
                    input.classList.remove("is-valid");
                    console.log("Adresse Invalide : ", email)
                }
            }
            // Validation du mot de passe
            if (input.id === "password" && pwdInput) {
                validatePassword(pwdInput);
            } 
            // Validation de la confirmation du mot de passe
            if (input.id === "confirmPassword" && pwdInput && confirmPwdInput) {
                validateConfirmationPassword(pwdInput, confirmPwdInput);
            }

            console.log(input.id, "=>", safe);
            });
        });
    }
    
// FILTRE ASYNCHRONE
    const ENDPOINT = '/data/events.json';

    const organizerSelect = document.getElementById('organizerSelect'); 
    const dateAt = document.getElementById('dateAt');
    const playersCount = document.getElementById('playersCount');
    const resetBtn = document.getElementById('resetFilters');

    const listEvent = document.getElementById('eventList');
    const template = document.getElementById('eventCardTemplate');
    const stateEmpty = document.getElementById('stateEmpty');

    if (!listEvent || !template) return;

    let events = [];

    function formatFR(isoDate) {
    const dateObj = new Date(isoDate);
        if (isNaN(dateObj)) return '—';
        return dateObj.toLocaleString('fr-FR', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    function toMs(localValue) {
        if (!localValue) return null;
        const time = new Date(localValue);
        return isNaN(time) ? null : time.getTime();
    }

    // Affichage des cartes
    function render(list) {
        listEvent.innerHTML = '';
        if (!list || list.length === 0) {
            if (stateEmpty) stateEmpty.hidden = false;
            return;
        }
        if (stateEmpty) stateEmpty.hidden = true;

        const frag = document.createDocumentFragment();
        list.forEach(event => {
            const node = template.content.cloneNode(true);
            const detailsBtn = node.querySelector('.btn-details');

            const img = node.querySelector('.event-img');
            const title = node.querySelector('.event-title');
            const dates = node.querySelector('.event-dates');
            const players = node.querySelector('.event-players');

            if (img) {
                img.src = event.imgUrl || '/build/images/jpg/placeholder.jpg';
                img.alt = `Illustration de ${event.title}`;
            }
            if (title)  title.textContent  = event.title;
            if (dates)  dates.textContent  = `Du ${formatFR(event.startsAt)} au ${formatFR(event.endsAt)}`;
            if (players) players.textContent = `${event.playersRegistered}/${event.maxPlayers}`;
            if (detailsBtn) {
                detailsBtn.dataset.eventId = event.id; 
            }

            frag.appendChild(node);
        });
        listEvent.appendChild(frag);
    };

    function applyFilters() {
        const org  = organizerSelect?.value || 'all';
        const minTs = toMs(dateAt?.value);
        const minP  = playersCount?.value ? parseInt(playersCount.value, 10) : null;

        const out = events
            .filter(ev => (ev.status || '').toLowerCase() === 'valide')
            .filter(ev => org === 'all' || ev.organizer === org)
            .filter(ev => {
                if (minTs === null) return true;
                const start = new Date(ev.startsAt).getTime();
                return start >= minTs;
            })
            .filter(ev => (minP === null || ev.playersRegistered >= minP))
            .sort((a, b) => {
                const ta = new Date(a.startsAt).getTime();
                const tb = new Date(b.startsAt).getTime();
                if (ta !== tb) return ta - tb;
                return a.title.localeCompare(b.title, 'fr', { sensitivity: 'base' });
            });
        render(out);
    }

    async function initEvents() {
        try {
            const res = await fetch(ENDPOINT, { cache: 'no-store' });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            // mise en cache
            events = Array.isArray(data) ? data : [];

            // agrémenter la liste d'organisateurs
            if (organizerSelect) {
                const set = new Set(events.filter(e => (e.status || '').toLowerCase() === 'valide').map(e => e.organizer));
                const orgs = Array.from(set).sort((a, b) => a.localeCompare(b, 'fr', { sensitivity: 'base' }));
                const frag = document.createDocumentFragment();
                // garder "Tous" déjà présent
                orgs.forEach(o => {
                    const opt = document.createElement('option');
                    opt.value = o;
                    opt.textContent = o;
                    frag.appendChild(opt);
                })
                organizerSelect.appendChild(frag);
            }

            applyFilters();

             // Loupe (submit du form)
            document.getElementById('applyFiltersBtn')?.addEventListener('click', (e) => {
                e.preventDefault();
                applyFilters();
            });

            // Bouton Réinitialiser
            resetBtn?.addEventListener('click', () => {
                if (organizerSelect) organizerSelect.value = 'all';
                if (dateAt) dateAt.value = '';
                if (playersCount) playersCount.value = '';
                applyFilters(); // recharger la liste complète
            });

        } catch (err) {
            console.error('Erreur de chargement des événements :', err);
            if (stateEmpty) stateEmpty.hidden = false;
        }
    }
    initEvents();
    
// MODALE "DETAIL D'UN EVENEMENT"
    function formatRangeFR(startsAtISO, endsAtISO) {
        return `Du ${formatFR(startsAtISO)} au ${formatFR(endsAtISO)}`;
    }

    function gaugeInfo(playersRegistered, maxPlayers) {
        const count = `${playersRegistered}/${maxPlayers}`;
        if (!Number.isFinite(maxPlayers) || maxPlayers <= 0) {
            return { count, label: '—' };
        }
        if (playersRegistered >= maxPlayers) {
            return { count, label: 'Complet' };
        }
        const remaining = Math.max(0, maxPlayers - playersRegistered);
        return { count, label: `Reste ${remaining} place${remaining > 1 ? 's' : ''}` };
    }

    function canShowJoin(startsAtISO, endsAtISO) {
        const now   = new Date();
        const start = new Date(startsAtISO);
        const end   = new Date(endsAtISO);
        return now >= start && now <= end;
    }

    const modalEl = document.getElementById('modalEvent');
    const elsModal = {
        img: document.getElementById('modalEventImg'),
        title: document.getElementById('modalEventTitle'),
        tagline: document.getElementById('modalEventTagline'),
        desc: document.getElementById('modalEventDesc'),
        dates: document.getElementById('modalEventDates'),
        players: document.getElementById('modalEventPlayers'),
        gauge: document.getElementById('modalEventGauge'),
        organizer: document.getElementById('modalEventOrganizer'),
        status: document.getElementById('modalEventStatus'),
        btnJoin: document.getElementById('modalBtnJoin'),
        btnFav: document.getElementById('modalBtnFav'),
        btnSubs: document.getElementById('modalBtnSubscribeIcon'),
    };

    let bsModal = null;
    if (modalEl && window.bootstrap?.Modal) {
        bsModal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
    }

    function getEventById(id) {
        return events.find(e => Number(e.id) === Number(id)) || null;
    }

    function fillModal(ev) {
    // Textes
        elsModal.title.textContent     = ev.title || 'Évènement';
        elsModal.tagline.textContent   = ev.tagline || '';
        elsModal.desc.textContent      = ev.description || '';
        elsModal.organizer.textContent = ev.organizer || '—';

    // Statut
        const statusRaw = (ev.status || '').toLowerCase();
        elsModal.status.textContent = statusRaw === 'valide' ? 'Validé ✅' : (ev.status || '—');

    // Image + alt
        elsModal.img.src = ev.imgUrl || '';
        elsModal.img.alt = `Image de l'évènement ${ev.title || ''}`.trim();

    // Dates / joueurs / jauge
        elsModal.dates.textContent = formatRangeFR(ev.startsAt, ev.endsAt);
        const g = gaugeInfo(ev.playersRegistered ?? 0, ev.maxPlayers ?? 0);
        elsModal.players.textContent = g.count;
        elsModal.gauge.textContent   = g.label;

    // Bouton "Rejoindre" selon fenêtre temporelle
        elsModal.btnJoin.classList.toggle('d-none', !canShowJoin(ev.startsAt, ev.endsAt));

    // Stockage id pour actions
        elsModal.btnJoin.dataset.eventId = ev.id;
        elsModal.btnFav.dataset.eventId  = ev.id;
        elsModal.btnSubs.dataset.eventId = ev.id;
    }

    listEvent?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-details');
        if (!btn) return;

        const id = btn.dataset.eventId;
        const ev = getEventById(id);
        if (!ev) return;

        fillModal(ev);
        bsModal?.show();
    });

    elsModal.btnFav?.addEventListener('click', () => {
        const id = elsModal.btnFav.dataset.eventId;
        console.log('Favori toggle pour event', id);
    // TODO: fetch POST /favorites à relier au back
    });

    elsModal.btnJoin?.addEventListener('click', () => {
        const id = elsModal.btnJoin.dataset.eventId;
        console.log('Rejoindre event', id);
    // TODO: inscription (vérifier rôles/places/état)
    });

    elsModal.btnSubs?.addEventListener('click', () => {
        const id = elsModal.btnSubs.dataset.eventId;
        console.log("S'inscrire à l'event", id);
    // TODO: pré-inscription si logique distincte
    });

});






