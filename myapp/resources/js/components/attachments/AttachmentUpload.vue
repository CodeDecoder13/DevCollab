<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Upload } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';

const props = defineProps<{
    projectId: number;
    taskId: number;
}>();

const fileInput = ref<HTMLInputElement>();
const isDragging = ref(false);
const previewName = ref('');
const form = useForm<{ file: File | null }>({
    file: null,
});

function selectFile() {
    fileInput.value?.click();
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files?.[0]) {
        form.file = target.files[0];
        previewName.value = target.files[0].name;
        uploadFile();
    }
}

function handleDrop(event: DragEvent) {
    event.preventDefault();
    isDragging.value = false;
    const file = event.dataTransfer?.files?.[0];
    if (file) {
        form.file = file;
        previewName.value = file.name;
        uploadFile();
    }
}

function handleDragEnter(event: DragEvent) {
    event.preventDefault();
    isDragging.value = true;
}

function handleDragLeave() {
    isDragging.value = false;
}

function handleDragOver(event: DragEvent) {
    event.preventDefault();
}

function uploadFile() {
    if (!form.file) return;
    form.post(
        `/projects/${props.projectId}/tasks/${props.taskId}/attachments`,
        {
            forceFormData: true,
            onSuccess: () => {
                form.reset();
                previewName.value = '';
                if (fileInput.value) fileInput.value.value = '';
            },
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <div>
        <div
            class="relative rounded-lg border-2 border-dashed p-6 text-center transition-colors"
            :class="isDragging ? 'border-primary bg-primary/5' : 'border-muted-foreground/25 hover:border-muted-foreground/50'"
            @drop="handleDrop"
            @dragenter="handleDragEnter"
            @dragleave="handleDragLeave"
            @dragover="handleDragOver"
        >
            <input
                ref="fileInput"
                type="file"
                class="hidden"
                @change="handleFileChange"
            />
            <Upload class="mx-auto h-8 w-8 text-muted-foreground" />
            <p class="mt-2 text-sm text-muted-foreground">
                <button class="font-medium text-primary hover:underline" @click="selectFile">
                    Click to upload
                </button>
                or drag and drop
            </p>
            <p v-if="previewName" class="mt-1 text-xs text-muted-foreground">
                {{ previewName }}
            </p>
        </div>
        <Progress v-if="form.processing" :model-value="form.progress?.percentage ?? 0" class="mt-2 h-1.5" />
        <p v-if="form.errors.file" class="mt-1 text-sm text-destructive">
            {{ form.errors.file }}
        </p>
    </div>
</template>
