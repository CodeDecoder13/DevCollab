<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>

<template>
    <div class="flex flex-col gap-4 p-4">
        <Skeleton class="h-20 w-full rounded-lg" />
        <div class="grid grid-cols-3 gap-4">
            <Skeleton class="h-24 rounded-lg" />
            <Skeleton class="h-24 rounded-lg" />
            <Skeleton class="h-24 rounded-lg" />
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <Skeleton class="h-64 rounded-lg" />
            <Skeleton class="h-64 rounded-lg" />
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Skeleton class="h-40 rounded-lg" />
            <Skeleton class="h-40 rounded-lg" />
            <Skeleton class="h-40 rounded-lg" />
        </div>
    </div>
</template>
