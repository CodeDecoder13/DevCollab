<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>

<template>
    <div class="flex gap-4">
        <div v-for="i in 3" :key="i" class="w-80 space-y-2 rounded-lg bg-muted/50 p-2">
            <Skeleton class="h-8 w-full rounded" />
            <Skeleton v-for="j in 3" :key="j" class="h-24 w-full rounded" />
        </div>
    </div>
</template>
