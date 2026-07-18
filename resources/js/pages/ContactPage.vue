<script setup>
import { computed, inject, reactive, ref } from 'vue';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';
import { useAnnouncer } from '../composables/useAnnouncer';

const site = inject('site');
const { announce } = useAnnouncer();
const form = reactive({
    name: '',
    email: '',
    message: '',
});
const state = ref('idle');
const errors = ref({});

const contact = computed(() => site.settings.value?.contact || {});

useHead({
    title: 'Contacto | Pablo Millaquen',
    meta: [
        { name: 'description', content: 'Contacta con Pablo Millaquen para oportunidades de colaboración.' },
        { property: 'og:title', content: 'Contacto | Pablo Millaquen' },
        { property: 'og:description', content: 'Contacta con Pablo Millaquen para oportunidades de colaboración.' },
        { property: 'og:type', content: 'website' },
    ],
});

const validate = () => {
    errors.value = {};
    if (!form.name.trim()) errors.value.name = true;
    if (!form.email.trim()) errors.value.email = true;
    if (!form.message.trim()) errors.value.message = true;
    return Object.keys(errors.value).length === 0;
};

const submit = async () => {
    if (!validate()) {
        announce(
            site.locale.value === 'es' ? 'El formulario tiene errores' : 'Form has errors',
            'assertive'
        );
        return;
    }
    state.value = 'loading';
    try {
        await api.post('/api/contact', form);
        form.name = '';
        form.email = '';
        form.message = '';
        state.value = 'success';
        announce(
            site.locale.value === 'es' ? 'Mensaje enviado correctamente' : 'Message sent successfully',
            'polite'
        );
    } catch {
        state.value = 'error';
        announce(
            site.locale.value === 'es' ? 'No se pudo enviar el mensaje' : 'Could not send your message',
            'assertive'
        );
    }
};
</script>

<template>
    <section class="panel contact-panel">
        <div class="section-heading">
            <h1>{{ contact.title }}</h1>
        </div>
        <p class="lead">{{ contact.subtitle }}</p>
        <form class="contact-form" @submit.prevent="submit" novalidate>
            <div class="two-column">
                <div class="form-field">
                    <label for="contact-name">
                        {{ site.locale.value === 'es' ? 'Nombre' : 'Name' }}
                        <span aria-hidden="true">*</span>
                    </label>
                    <input
                        id="contact-name"
                        v-model="form.name"
                        type="text"
                        :placeholder="site.locale.value === 'es' ? 'Nombre' : 'Name'"
                        :aria-required="true"
                        :aria-invalid="errors.name || undefined"
                        aria-describedby="contact-name-error"
                        required
                    >
                </div>
                <div class="form-field">
                    <label for="contact-email">
                        {{ site.locale.value === 'es' ? 'Correo electrónico' : 'Email' }}
                        <span aria-hidden="true">*</span>
                    </label>
                    <input
                        id="contact-email"
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        :aria-required="true"
                        :aria-invalid="errors.email || undefined"
                        aria-describedby="contact-email-error"
                        required
                    >
                </div>
            </div>
            <div class="form-field">
                <label for="contact-message">
                    {{ site.locale.value === 'es' ? 'Mensaje' : 'Message' }}
                    <span aria-hidden="true">*</span>
                </label>
                <textarea
                    id="contact-message"
                    v-model="form.message"
                    rows="7"
                    :placeholder="site.locale.value === 'es' ? 'Mensaje' : 'Message'"
                    :aria-required="true"
                    :aria-invalid="errors.message || undefined"
                    aria-describedby="contact-message-error"
                    required
                />
            </div>
            <button class="primary-button" type="submit">
                {{ site.locale.value === 'es' ? 'Enviar mensaje' : 'Send message' }}
            </button>
            <p v-if="state === 'success'" class="success-text" role="status">
                {{ site.locale.value === 'es' ? 'Mensaje enviado correctamente.' : 'Message sent successfully.' }}
            </p>
            <p v-if="state === 'error'" class="error-text" role="alert">
                {{ site.locale.value === 'es' ? 'No se pudo enviar.' : 'Could not send your message.' }}
            </p>
        </form>
    </section>
</template>
