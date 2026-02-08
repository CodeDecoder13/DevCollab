<script setup lang="ts">
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { getInitials } from '@/composables/useInitials';
import type { User } from '@/types';

const props = withDefaults(
    defineProps<{
        members: User[];
        max?: number;
    }>(),
    { max: 4 },
);

const visible = computed(() => props.members.slice(0, props.max));
const remaining = computed(() => props.members.length - props.max);
</script>

<template>
    <div class="flex -space-x-2">
        <TooltipProvider>
            <Tooltip v-for="member in visible" :key="member.id">
                <TooltipTrigger as-child>
                    <Avatar class="h-7 w-7 border-2 border-background">
                        <AvatarImage
                            v-if="member.avatar"
                            :src="member.avatar"
                            :alt="member.name"
                        />
                        <AvatarFallback class="text-xs">{{
                            getInitials(member.name)
                        }}</AvatarFallback>
                    </Avatar>
                </TooltipTrigger>
                <TooltipContent>{{ member.name }}</TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <div
            v-if="remaining > 0"
            class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-background bg-muted text-xs font-medium"
        >
            +{{ remaining }}
        </div>
    </div>
</template>
