<script setup lang="ts">
import { Moon, Sun } from 'lucide-vue-next';
import { useAppearance } from '@/composables/useAppearance';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const { appearance, resolvedAppearance, updateAppearance } = useAppearance();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                <Sun
                    class="h-4 w-4 rotate-0 scale-100 transition-transform dark:-rotate-90 dark:scale-0"
                />
                <Moon
                    class="absolute h-4 w-4 rotate-90 scale-0 transition-transform dark:rotate-0 dark:scale-100"
                />
                <span class="sr-only">Toggle theme</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem
                :class="appearance === 'light' ? 'bg-accent' : ''"
                @click="updateAppearance('light')"
            >
                Light
            </DropdownMenuItem>
            <DropdownMenuItem
                :class="appearance === 'dark' ? 'bg-accent' : ''"
                @click="updateAppearance('dark')"
            >
                Dark
            </DropdownMenuItem>
            <DropdownMenuItem
                :class="appearance === 'system' ? 'bg-accent' : ''"
                @click="updateAppearance('system')"
            >
                System
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
