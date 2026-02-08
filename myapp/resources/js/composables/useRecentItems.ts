import { ref, watch } from 'vue';

type RecentProject = {
    id: number;
    name: string;
};

const STORAGE_KEY = 'devcollab_recent_projects';
const MAX_ITEMS = 3;

const recentProjects = ref<RecentProject[]>(loadFromStorage());

function loadFromStorage(): RecentProject[] {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        return stored ? JSON.parse(stored) : [];
    } catch {
        return [];
    }
}

function saveToStorage() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(recentProjects.value));
}

export function useRecentProjects() {
    function trackProject(project: RecentProject) {
        const filtered = recentProjects.value.filter(
            (p) => p.id !== project.id,
        );
        recentProjects.value = [project, ...filtered].slice(0, MAX_ITEMS);
        saveToStorage();
    }

    return {
        recentProjects,
        trackProject,
    };
}
