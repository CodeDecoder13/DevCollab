<script setup lang="ts">
import { Label } from '@/components/ui/label';

defineProps<{
    label: string;
    htmlFor?: string;
    error?: string;
}>();
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label" :for="htmlFor">{{ label }}</Label>
        <slot />
        <p
            v-if="error"
            class="text-sm text-destructive transition-all"
        >
            {{ error }}
        </p>
    </div>
</template>
