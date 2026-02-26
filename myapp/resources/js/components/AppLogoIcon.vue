<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 32 32"
        fill="none"
        :class="className"
        v-bind="$attrs"
    >
        <defs>
            <linearGradient id="logo-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#6366f1" />
                <stop offset="100%" stop-color="#8b5cf6" />
            </linearGradient>
        </defs>
        <!-- Rounded shield / document shape -->
        <rect x="3" y="2" width="26" height="28" rx="5" fill="url(#logo-grad)" />
        <!-- Code bracket left < -->
        <polyline points="11,10 7,14.5 11,19" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.7" />
        <!-- Code bracket right > -->
        <polyline points="21,10 25,14.5 21,19" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.7" />
        <!-- Checkmark (centered, overlapping brackets) -->
        <polyline points="12,16 15,19.5 21,12" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
    </svg>
</template>
