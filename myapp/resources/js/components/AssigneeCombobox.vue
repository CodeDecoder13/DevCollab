<script setup lang="ts">
import { Check, ChevronsUpDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { getInitials } from '@/composables/useInitials';
import { cn } from '@/lib/utils';
import type { ProjectMember } from '@/types';

const props = defineProps<{
    members: ProjectMember[];
}>();

const model = defineModel<string>({ default: 'none' });

const open = ref(false);

const selectedMember = computed(() => {
    if (model.value === 'none') return null;
    return props.members.find((m) => String(m.id) === model.value);
});

function select(memberId: string) {
    model.value = memberId;
    open.value = false;
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :class="cn('w-full justify-between', !selectedMember && 'text-muted-foreground')"
            >
                <div v-if="selectedMember" class="flex items-center gap-2">
                    <Avatar class="h-5 w-5">
                        <AvatarFallback class="text-[8px]">{{ getInitials(selectedMember.name) }}</AvatarFallback>
                    </Avatar>
                    {{ selectedMember.name }}
                </div>
                <span v-else>Select assignee</span>
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[250px] p-0">
            <Command>
                <CommandInput placeholder="Search members..." />
                <CommandList>
                    <CommandEmpty>No members found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem value="none" @select="select('none')">
                            <Check :class="cn('mr-2 h-4 w-4', model === 'none' ? 'opacity-100' : 'opacity-0')" />
                            Unassigned
                        </CommandItem>
                        <CommandItem
                            v-for="member in members"
                            :key="member.id"
                            :value="`member-${member.id}-${member.name}`"
                            @select="select(String(member.id))"
                        >
                            <Check :class="cn('mr-2 h-4 w-4', model === String(member.id) ? 'opacity-100' : 'opacity-0')" />
                            <Avatar class="mr-2 h-5 w-5">
                                <AvatarFallback class="text-[8px]">{{ getInitials(member.name) }}</AvatarFallback>
                            </Avatar>
                            {{ member.name }}
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
