/**
 * Solución para errores: "Added non-passive event listener to a scroll-blocking event"
 * Hace que los event listeners sean passive para mejorar rendimiento de scroll
 */

(function() {
  // Detectar si el navegador soporta passive events
  let passiveSupported = false;
  try {
    const options = {
      get passive() {
        passiveSupported = true;
        return false;
      }
    };
    window.addEventListener('test', null, options);
    window.removeEventListener('test', null, options);
  } catch(err) {
    passiveSupported = false;
  }

  if (!passiveSupported) {
    return; // No necesita parche si ya soporta passive
  }

  // Hacer que addEventListener use passive por defecto para ciertos eventos
  const originalAddEventListener = EventTarget.prototype.addEventListener;
  
  const passiveEvents = [
    'scroll',
    'wheel',
    'touchmove',
    'touchstart',
    'touchend',
    'mousewheel',
    'DOMMouseScroll',
    'touchcancel'
  ];

  EventTarget.prototype.addEventListener = function(type, listener, options) {
    // Si es un evento que bloquea scroll y options es boolean o undefined, hacer passive
    if (passiveEvents.includes(type) && 
        (typeof options === 'boolean' || typeof options === 'undefined')) {
      options = { passive: true, capture: options === true };
    } else if (passiveEvents.includes(type) && typeof options === 'object') {
      // Si es object pero no tiene passive definido, agregarlo
      if (options && !('passive' in options)) {
        options.passive = true;
      }
    }
    
    return originalAddEventListener.call(this, type, listener, options);
  };
})();
