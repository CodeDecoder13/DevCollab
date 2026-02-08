<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { FolderKanban, LayoutGrid, Plus, Search, User } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import {
    CommandDialog,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
} from '@/components/ui/command';

const open = defineModel<boolean>('open', { default: false });

const query = ref('');
const results = ref<{
    projects: { id: number; name: string; status: string }[];
    tasks: { id: number; title: string; status: string; project_id: number; project?: { id: number; name: string } }[];
    users: { id: number; name: string; email: string }[];
}>({ projects: [], tasks: [], users: [] });

let debounceTimer: ReturnType<typeof setTimeout>;

watch(query, (val) => {
    clearTimeout(debounceTimer);
    if (val.length < 2) {
        results.value = { projects: [], tasks: [], users: [] };
        return;
    }
    debounceTimer = setTimeout(async () => {
        try {
            const response = await fetch(`/search?q=${encodeURIComponent(val)}`, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            results.value = await response.json();
        } catch {
            results.value = { projects: [], tasks: [], users: [] };
        }
    }, 300);
});

const hasResults = ref(false);
watch(results, (r) => {
    hasResults.value = r.projects.length > 0 || r.tasks.length > 0 || r.users.length > 0;
});

function navigate(url: string) {
    open.value = false;
    query.value = '';
    results.value = { projects: [], tasks: [], users: [] };
    router.visit(url);
}
</script>

<template>
    <CommandDialog v-model:open="open">
        <CommandInput v-model="query" placeholder="Search projects, tasks, users..." />
        <CommandList>
            <template v-if="query.length < 2">
                <CommandEmpty>Type to search...</CommandEmpty>
                <CommandGroup heading="Quick Actions">
                    <CommandItem value="go-dashboard" @select="navigate('/dashboard')">
                        <LayoutGrid class="mr-2 h-4 w-4" />
                        Go to Dashboard
                    </CommandItem>
                    <CommandItem value="go-projects" @select="navigate('/projects')">
                        <FolderKanban class="mr-2 h-4 w-4" />
                        Go to Projects
                    </CommandItem>
                    <CommandItem value="create-project" @select="navigate('/projects/create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Project
                    </CommandItem>
                </CommandGroup>
            </template>
            <template v-else>
                <CommandEmpty v-if="!hasResults">No results found.</CommandEmpty>

                <CommandGroup v-if="results.projects.length > 0" heading="Projects">
                    <CommandItem
                        v-for="project in results.projects"
                        :key="`p-${project.id}`"
                        :value="`project-${project.id}-${project.name}`"
                        @select="navigate(`/projects/${project.id}`)"
                    >
                        <FolderKanban class="mr-2 h-4 w-4" />
                        {{ project.name }}
                    </CommandItem>
                </CommandGroup>

                <CommandSeparator v-if="results.projects.length > 0 && results.tasks.length > 0" />

                <CommandGroup v-if="results.tasks.length > 0" heading="Tasks">
                    <CommandItem
                        v-for="task in results.tasks"
                        :key="`t-${task.id}`"
                        :value="`task-${task.id}-${task.title}`"
                        @select="navigate(`/projects/${task.project_id}/tasks/${task.id}`)"
                    >
                        <Search class="mr-2 h-4 w-4" />
                        <span>{{ task.title }}</span>
                        <span v-if="task.project" class="ml-2 text-xs text-muted-foreground">{{ task.project.name }}</span>
                    </CommandItem>
                </CommandGroup>

                <CommandSeparator v-if="results.tasks.length > 0 && results.users.length > 0" />

                <CommandGroup v-if="results.users.length > 0" heading="Users">
                    <CommandItem
                        v-for="user in results.users"
                        :key="`u-${user.id}`"
                        :value="`user-${user.id}-${user.name}`"
                    >
                        <User class="mr-2 h-4 w-4" />
                        <span>{{ user.name }}</span>
                        <span class="ml-2 text-xs text-muted-foreground">{{ user.email }}</span>
                    </CommandItem>
                </CommandGroup>
            </template>
        </CommandList>
    </CommandDialog>
</template>
