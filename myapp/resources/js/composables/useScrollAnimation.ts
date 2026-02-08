import { onMounted, onUnmounted, ref, type Ref } from 'vue';

export function useScrollAnimation(
    threshold = 0.1,
): { elementRef: Ref<HTMLElement | null>; isVisible: Ref<boolean> } {
    const elementRef = ref<HTMLElement | null>(null);
    const isVisible = ref(false);
    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        if (!elementRef.value) return;

        observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    isVisible.value = true;
                    observer?.unobserve(entry.target);
                }
            },
            { threshold },
        );

        observer.observe(elementRef.value);
    });

    onUnmounted(() => {
        observer?.disconnect();
    });

    return { elementRef, isVisible };
}
