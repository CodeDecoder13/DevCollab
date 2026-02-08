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
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        :class="className"
        v-bind="$attrs"
    >
        <path d="M6 5v11" />
        <path d="M12 5v6" />
        <path d="M18 5v14" />
        <path d="M2.5 5h3" />
        <path d="M8.5 5h7" />
        <path d="M14.5 5h5" />
        <path d="M2.5 16h3" />
        <path d="M8.5 11h3" />
        <path d="M14.5 19h5" />
    </svg>
</template>
