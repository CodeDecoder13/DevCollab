import { router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

export function useKeyboardShortcuts(onCommandK: () => void) {
    const lastKey = ref('');
    const lastKeyTime = ref(0);

    function handleKeydown(e: KeyboardEvent) {
        if (
            e.target instanceof HTMLInputElement ||
            e.target instanceof HTMLTextAreaElement ||
            (e.target instanceof HTMLElement && e.target.isContentEditable)
        ) {
            return;
        }

        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            onCommandK();
            return;
        }

        const now = Date.now();
        if (now - lastKeyTime.value > 500) {
            lastKey.value = '';
        }

        if (lastKey.value === 'g') {
            if (e.key === 'd') {
                e.preventDefault();
                router.visit('/dashboard');
            } else if (e.key === 'p') {
                e.preventDefault();
                router.visit('/projects');
            }
            lastKey.value = '';
            return;
        }

        if (e.key === 'g') {
            lastKey.value = 'g';
            lastKeyTime.value = now;
        }
    }

    onMounted(() => {
        document.addEventListener('keydown', handleKeydown);
    });

    onUnmounted(() => {
        document.removeEventListener('keydown', handleKeydown);
    });
}
