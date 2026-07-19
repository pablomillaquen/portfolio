<script setup>
import { ref, watchEffect, onUnmounted, nextTick } from 'vue';
import { useFocusTrap } from '@vueuse/integrations/useFocusTrap';

const props = defineProps({
    html: { type: String, default: '' },
    title: { type: String, default: '' },
    locale: { type: String, default: 'es' },
    show: { type: Boolean, default: false },
});

const emit = defineEmits(['close', 'toggle-locale']);

const modalRef = ref(null);

const { activate, deactivate } = useFocusTrap(modalRef, {
    immediate: false,
    escapeDeactivates: true,
    returnFocusOnDeactivate: true,
    onDeactivate: () => emit('close'),
});

function onKeydown(e) {
    if (e.key === 'Escape' && props.show) {
        emit('close');
    }
}

watchEffect(async () => {
    if (props.show) {
        document.body.classList.add('modal-open');
        document.addEventListener('keydown', onKeydown);
        await nextTick();
        activate();
    } else {
        document.body.classList.remove('modal-open');
        document.removeEventListener('keydown', onKeydown);
        deactivate();
    }
});

onUnmounted(() => {
    document.body.classList.remove('modal-open');
    document.removeEventListener('keydown', onKeydown);
    deactivate();
});
</script>

<template>
    <Teleport to="body">
        <div
            v-if="show"
            ref="modalRef"
            class="modal-overlay"
            role="dialog"
            aria-modal="true"
            :aria-label="title"
            @click.self="emit('close')"
        >
            <div class="preview-modal">
                <div class="preview-modal-header">
                    <h2>Vista previa: {{ title }}</h2>
                    <div class="preview-modal-actions">
                        <button class="ghost-button" @click="emit('toggle-locale')">
                            {{ locale === 'es' ? 'EN' : 'ES' }}
                        </button>
                        <button class="preview-modal-close" :aria-label="locale === 'es' ? 'Cerrar diálogo' : 'Close dialog'" @click="emit('close')">&times;</button>
                    </div>
                </div>
                <div class="preview-modal-body" v-html="html"></div>
            </div>
        </div>
    </Teleport>
</template>
