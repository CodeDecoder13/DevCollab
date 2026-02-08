<script setup lang="ts">
import type { Component } from 'vue';

defineProps<{
    icon: Component;
    title: string;
    description: string;
}>();
</script>

<template>
    <div class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-muted">
            <component :is="icon" class="h-6 w-6 text-muted-foreground" />
        </div>
        <h3 class="mt-4 text-sm font-semibold">{{ title }}</h3>
        <p class="mt-1 text-sm text-muted-foreground">{{ description }}</p>
        <div class="mt-4">
            <slot />
        </div>
    </div>
</template>
