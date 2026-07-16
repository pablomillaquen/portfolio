<script setup>
import { watchEffect, onUnmounted } from 'vue';

const props = defineProps({
    html: { type: String, default: '' },
    title: { type: String, default: '' },
    locale: { type: String, default: 'en' },
    show: { type: Boolean, default: false },
});

const emit = defineEmits(['close', 'toggle-locale']);

watchEffect(() => {
    if (props.show) {
        document.body.classList.add('modal-open');
    } else {
        document.body.classList.remove('modal-open');
    }
});

onUnmounted(() => {
    document.body.classList.remove('modal-open');
});
</script>

<template>
    <Teleport to="body">
        <div v-if="show" class="modal-overlay" @click.self="emit('close')">
            <div class="preview-modal">
                <div class="preview-modal-header">
                    <h2>Preview: {{ title }}</h2>
                    <div class="preview-modal-actions">
                        <button class="ghost-button" @click="emit('toggle-locale')">
                            {{ locale === 'en' ? 'ES' : 'EN' }}
                        </button>
                        <button class="preview-modal-close" @click="emit('close')">&times;</button>
                    </div>
                </div>
                <div class="preview-modal-body" v-html="html"></div>
            </div>
        </div>
    </Teleport>
</template>
