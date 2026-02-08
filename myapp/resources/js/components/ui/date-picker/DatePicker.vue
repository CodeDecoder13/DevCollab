<script setup lang="ts">
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';

const model = defineModel<string>({ default: '' });

const open = ref(false);

const displayDate = computed(() => {
    if (!model.value) return 'Pick a date';
    return new Date(model.value).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
});

function handleSelect(date: unknown) {
    if (date && typeof date === 'object' && 'toString' in date) {
        const d = date as { year: number; month: number; day: number };
        if ('year' in d && 'month' in d && 'day' in d) {
            const formatted = `${d.year}-${String(d.month).padStart(2, '0')}-${String(d.day).padStart(2, '0')}`;
            model.value = formatted;
            open.value = false;
            return;
        }
    }
    if (date instanceof Date) {
        model.value = date.toISOString().split('T')[0];
        open.value = false;
    }
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="cn('w-full justify-start text-left font-normal', !model && 'text-muted-foreground')"
            >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ displayDate }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar mode="single" @update:model-value="handleSelect" />
        </PopoverContent>
    </Popover>
</template>
