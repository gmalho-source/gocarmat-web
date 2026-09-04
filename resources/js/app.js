// Carrossel de cards (ex: "Os nossos serviços" na Home) — arrasta com o rato
// ou usa os botões, que avançam/recuam um cartão de cada vez.
function initCarousels() {
    document.querySelectorAll('[data-carousel-track]').forEach((track) => {
        const section = track.closest('section');
        if (!section || track.dataset.carouselReady) return;
        track.dataset.carouselReady = '1';
        track.classList.add('cursor-grab');

        const prevBtn = section.querySelector('[data-carousel-prev]');
        const nextBtn = section.querySelector('[data-carousel-next]');

        const step = () => {
            const card = track.children[0];
            if (!card) return track.clientWidth;
            const gap = parseFloat(getComputedStyle(track).columnGap || '0');
            return card.getBoundingClientRect().width + gap;
        };

        const updateButtons = () => {
            const max = track.scrollWidth - track.clientWidth - 1;
            if (prevBtn) prevBtn.disabled = track.scrollLeft <= 0;
            if (nextBtn) nextBtn.disabled = track.scrollLeft >= max;
        };

        prevBtn?.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
        nextBtn?.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
        track.addEventListener('scroll', updateButtons, { passive: true });
        window.addEventListener('resize', updateButtons);
        updateButtons();

        // Arrastar com o rato: segue o cursor 1:1, sem scroll suave a atrapalhar
        // o arrasto, e o snap dos cartões só entra em ação ao soltar.
        let dragging = false;
        let moved = false;
        let startX = 0;
        let startScroll = 0;

        track.addEventListener('pointerdown', (e) => {
            // Só o rato arrasta — em toque, o scroll nativo já funciona bem.
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            e.preventDefault();
            dragging = true;
            moved = false;
            startX = e.clientX;
            startScroll = track.scrollLeft;
            track.style.scrollSnapType = 'none';
            track.style.scrollBehavior = 'auto';
            track.classList.replace('cursor-grab', 'cursor-grabbing');
            track.setPointerCapture(e.pointerId);
        });

        track.addEventListener('pointermove', (e) => {
            if (!dragging) return;
            const delta = e.clientX - startX;
            if (Math.abs(delta) > 5) moved = true;
            track.scrollLeft = startScroll - delta;
        });

        const endDrag = (e) => {
            if (!dragging) return;
            dragging = false;
            track.style.scrollSnapType = '';
            track.style.scrollBehavior = '';
            track.classList.replace('cursor-grabbing', 'cursor-grab');
            if (e.pointerId !== undefined) track.releasePointerCapture(e.pointerId);
        };

        track.addEventListener('pointerup', endDrag);
        track.addEventListener('pointercancel', endDrag);

        // Depois de arrastar, ignora o clique seguinte — evita abrir o link do
        // cartão só porque o utilizador estava a tentar navegar o carrossel.
        track.addEventListener(
            'click',
            (e) => {
                if (moved) {
                    e.preventDefault();
                    moved = false;
                }
            },
            true
        );
    });
}

document.addEventListener('DOMContentLoaded', initCarousels);
