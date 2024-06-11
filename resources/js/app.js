import './bootstrap';
import 'preline'

// this line code for fixing bug on mobile menu
document.addEventListener('livewire:navigated', () => {
    window.HSStaticMethods.autoInit();
})
