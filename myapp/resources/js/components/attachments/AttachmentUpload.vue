<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Upload } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    projectId: number;
    taskId: number;
}>();

const fileInput = ref<HTMLInputElement>();
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
        uploadFile();
    }
}

function uploadFile() {
    if (!form.file) return;
    form.post(
        `/projects/${props.projectId}/tasks/${props.taskId}/attachments`,
        {
            forceFormData: true,
            onSuccess: () => {
                form.reset();
                if (fileInput.value) fileInput.value.value = '';
            },
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <div>
        <input
            ref="fileInput"
            type="file"
            class="hidden"
            @change="handleFileChange"
        />
        <Button
            variant="outline"
            size="sm"
            :disabled="form.processing"
            @click="selectFile"
        >
            <Upload class="mr-2 h-4 w-4" />
            {{ form.processing ? 'Uploading...' : 'Upload File' }}
        </Button>
        <p v-if="form.errors.file" class="mt-1 text-sm text-destructive">
            {{ form.errors.file }}
        </p>
    </div>
</template>
