<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>

<template>
    <div class="space-y-2 rounded-md border p-2">
        <Skeleton v-for="i in 5" :key="i" class="h-12 w-full rounded" />
    </div>
</template>
