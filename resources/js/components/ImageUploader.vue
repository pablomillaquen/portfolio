<script setup>
import { ref } from 'vue';
import { api } from '../services/api';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Imagen',
    },
});

const emit = defineEmits(['update:modelValue']);

const uploading = ref(false);
const error = ref('');
const fileInput = ref(null);

const openPicker = () => {
    error.value = '';
    fileInput.value?.click();
};

const onFileSelected = async (event) => {
    const file = event.target.files?.[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        error.value = 'El archivo debe ser una imagen.';
        event.target.value = '';
        return;
    }

    uploading.value = true;
    error.value = '';

    const formData = new FormData();
    formData.append('file', file);

    try {
        const { data } = await api.post('/api/admin/uploads', formData);
        emit('update:modelValue', data.url);
    } catch {
        error.value = 'No se pudo subir la imagen.';
    } finally {
        uploading.value = false;
        event.target.value = '';
    }
};

const removeImage = () => {
    emit('update:modelValue', '');
};
</script>

<template>
    <div class="image-uploader">
        <label v-if="label" class="image-uploader-label">{{ label }}</label>

        <div v-if="modelValue" class="image-uploader-preview-wrap">
            <img :src="modelValue" :alt="label" class="image-uploader-preview" />
            <button
                type="button"
                class="image-uploader-remove"
                :aria-label="`Quitar ${label}`"
                title="Quitar imagen"
                @click="removeImage"
            >×</button>
        </div>

        <div v-else class="image-uploader-empty">
            <span v-if="uploading" class="image-uploader-status">Subiendo…</span>
            <span v-else>Sin imagen</span>
        </div>

        <div class="image-uploader-actions">
            <input
                ref="fileInput"
                type="file"
                accept="image/*"
                class="sr-only"
                @change="onFileSelected"
            >
            <button type="button" class="secondary-button image-uploader-pick" :disabled="uploading" @click="openPicker">
                {{ uploading ? 'Subiendo…' : (modelValue ? 'Cambiar imagen' : 'Subir imagen') }}
            </button>
        </div>

        <p v-if="error" class="error-text">{{ error }}</p>
    </div>
</template>
