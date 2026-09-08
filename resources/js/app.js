import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.plugin(collapse);
});

Alpine.start();
