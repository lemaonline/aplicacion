// Script para inyectar logos en Filament - versión agresiva
function injectLogo() {
    // Determinar si estamos en modo oscuro
    const isDarkMode = document.documentElement.classList.contains('dark') ||
                      window.matchMedia('(prefers-color-scheme: dark)').matches;

    const logoSrc = isDarkMode ? '/imagenes/sm_hor_blanco.png' : '/imagenes/sm_hor_negro.png';

    // Detectar si estamos en una página de login (simple page)
    const isLoginPage = document.querySelector('.fi-simple-page') !== null;
    const logoSize = isLoginPage ? '100px' : '50px';

    // Buscar TODOS los elementos que contengan "Laravel" o sean brand
    document.querySelectorAll('*').forEach(el => {
        // Si el elemento contiene el texto "Laravel"
        if (el.textContent.trim() === 'Laravel' && el.children.length === 0) {
            el.innerHTML = `<img src="${logoSrc}" alt="Logo" style="max-height: ${logoSize}; width: auto; object-fit: contain;">`;
            return;
        }

        // Si es un elemento brand
        if (el.classList.contains('fi-topbar-brand') ||
            el.classList.contains('fi-sidebar-brand')) {
            el.innerHTML = `<img src="${logoSrc}" alt="Logo" style="max-height: ${logoSize}; width: auto; object-fit: contain;">`;
        }
    });
}

// Ejecutar inmediatamente
injectLogo();

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectLogo);
}

// Ejecutar en intervalos para capturar elementos cargados dinámicamente
setInterval(injectLogo, 500);

// Observar cambios en el DOM
const observer = new MutationObserver(function(mutations) {
    injectLogo();

    // Cambiar logo si cambia el tema
    mutations.forEach(function(mutation) {
        if (mutation.attributeName === 'class') {
            const isDarkNow = document.documentElement.classList.contains('dark');
            const newLogoSrc = isDarkNow ? '/imagenes/sm_hor_blanco.png' : '/imagenes/sm_hor_negro.png';

            const allImages = document.querySelectorAll('img[alt="Logo"]');
            allImages.forEach(img => {
                if (img.src !== newLogoSrc) {
                    img.src = newLogoSrc;
                }
            });
        }
    });
});

observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class'],
    subtree: true,
    childList: true
});

