<script setup lang="ts">
import MarketingNavbar from '@/components/marketing/MarketingNavbar.vue';
import MarketingFooter from '@/components/marketing/MarketingFooter.vue';

withDefaults(
    defineProps<{
        transparentNav?: boolean;
        canRegister?: boolean;
    }>(),
    {
        transparentNav: false,
        canRegister: true,
    },
);
</script>

<template>
    <div class="min-h-screen bg-background text-foreground" style="scroll-behavior: smooth">
        <MarketingNavbar :transparent="transparentNav" :can-register="canRegister" />
        <slot />
        <MarketingFooter :can-register="canRegister" />
    </div>
</template>
