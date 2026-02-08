<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    stats: {
        todo: number;
        in_progress: number;
        completed: number;
    };
}>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>My Task Summary</CardTitle>
        </CardHeader>
        <CardContent>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold">{{ stats.todo }}</p>
                    <p class="text-xs text-muted-foreground">To Do</p>
                </div>
                <div>
                    <p class="text-2xl font-bold">{{ stats.in_progress }}</p>
                    <p class="text-xs text-muted-foreground">In Progress</p>
                </div>
                <div>
                    <p class="text-2xl font-bold">{{ stats.completed }}</p>
                    <p class="text-xs text-muted-foreground">Completed</p>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
