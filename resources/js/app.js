import './bootstrap';
import collapse from '@alpinejs/collapse';

document.addEventListener('alpine:init', () => {
    Alpine.plugin(collapse);
});
