import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.plugin(collapse);
});

// Alpine được Livewire tự động khởi tạo nên không gọi Alpine.start() ở đây nữa
