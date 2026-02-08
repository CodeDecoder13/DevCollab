<script setup lang="ts">
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    stats: {
        todo: number;
        in_progress: number;
        completed: number;
    };
    overdueCount?: number;
    dueSoonCount?: number;
}>();

const total = computed(() => props.stats.todo + props.stats.in_progress + props.stats.completed);

const circumference = 2 * Math.PI * 40;

const segments = computed(() => {
    if (total.value === 0) return [];
    const todoLen = (props.stats.todo / total.value) * circumference;
    const ipLen = (props.stats.in_progress / total.value) * circumference;
    const doneLen = (props.stats.completed / total.value) * circumference;
    let offset = 0;

    const result = [];
    if (props.stats.todo > 0) {
        result.push({ len: todoLen, gap: circumference - todoLen, offset, color: 'text-slate-400' });
        offset += todoLen;
    }
    if (props.stats.in_progress > 0) {
        result.push({ len: ipLen, gap: circumference - ipLen, offset, color: 'text-amber-500' });
        offset += ipLen;
    }
    if (props.stats.completed > 0) {
        result.push({ len: doneLen, gap: circumference - doneLen, offset, color: 'text-emerald-500' });
    }
    return result;
});
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>My Task Summary</CardTitle>
        </CardHeader>
        <CardContent>
            <div class="flex items-center gap-6">
                <div class="relative h-28 w-28 shrink-0">
                    <svg viewBox="0 0 100 100" class="h-full w-full -rotate-90">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="8" class="text-muted/30" />
                        <circle
                            v-for="(seg, i) in segments"
                            :key="i"
                            cx="50"
                            cy="50"
                            r="40"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="8"
                            stroke-linecap="round"
                            :class="seg.color"
                            :stroke-dasharray="`${seg.len} ${seg.gap}`"
                            :stroke-dashoffset="`-${seg.offset}`"
                        />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold">{{ total }}</span>
                        <span class="text-[10px] text-muted-foreground">total</span>
                    </div>
                </div>
                <div class="grid flex-1 grid-cols-2 gap-3">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-slate-400" />
                        <div>
                            <p class="text-lg font-bold">{{ stats.todo }}</p>
                            <p class="text-xs text-muted-foreground">To Do</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-amber-500" />
                        <div>
                            <p class="text-lg font-bold">{{ stats.in_progress }}</p>
                            <p class="text-xs text-muted-foreground">In Progress</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-emerald-500" />
                        <div>
                            <p class="text-lg font-bold">{{ stats.completed }}</p>
                            <p class="text-xs text-muted-foreground">Completed</p>
                        </div>
                    </div>
                    <div v-if="overdueCount" class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-destructive" />
                        <div>
                            <p class="text-lg font-bold text-destructive">{{ overdueCount }}</p>
                            <p class="text-xs text-muted-foreground">Overdue</p>
                        </div>
                    </div>
                    <div v-if="dueSoonCount" class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-orange-400" />
                        <div>
                            <p class="text-lg font-bold">{{ dueSoonCount }}</p>
                            <p class="text-xs text-muted-foreground">Due Soon</p>
                        </div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
