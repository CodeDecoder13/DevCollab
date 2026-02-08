<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { FolderKanban, ListTodo, Plus, Search } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

defineEmits<{
    openSearch: [];
}>();
</script>

<template>
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <Button variant="outline" class="h-auto flex-col gap-1 py-3" as-child>
            <Link href="/projects/create">
                <Plus class="h-5 w-5" />
                <span class="text-xs">Create Project</span>
            </Link>
        </Button>
        <Button variant="outline" class="h-auto flex-col gap-1 py-3" as-child>
            <Link href="/projects">
                <FolderKanban class="h-5 w-5" />
                <span class="text-xs">All Projects</span>
            </Link>
        </Button>
        <Button variant="outline" class="h-auto flex-col gap-1 py-3" as-child>
            <Link href="/dashboard">
                <ListTodo class="h-5 w-5" />
                <span class="text-xs">My Tasks</span>
            </Link>
        </Button>
        <Button
            variant="outline"
            class="h-auto flex-col gap-1 py-3"
            @click="$emit('openSearch')"
        >
            <Search class="h-5 w-5" />
            <span class="text-xs">Search</span>
        </Button>
    </div>
</template>
