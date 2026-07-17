<script setup>
import { computed, inject, reactive, ref } from 'vue';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';

const site = inject('site');
const form = reactive({
    name: '',
    email: '',
    message: '',
});
const state = ref('idle');

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

const submit = async () => {
    state.value = 'loading';
    try {
        await api.post('/api/contact', form);
        form.name = '';
        form.email = '';
        form.message = '';
        state.value = 'success';
    } catch {
        state.value = 'error';
    }
};
</script>

<template>
    <section class="panel contact-panel">
        <div class="section-heading">
            <h1>{{ contact.title }}</h1>
        </div>
        <p class="lead">{{ contact.subtitle }}</p>
        <form class="contact-form" @submit.prevent="submit">
            <div class="two-column">
                <input v-model="form.name" :placeholder="site.locale.value === 'es' ? 'Nombre' : 'Name'" required>
                <input v-model="form.email" type="email" placeholder="Email" required>
            </div>
            <textarea
                v-model="form.message"
                rows="7"
                :placeholder="site.locale.value === 'es' ? 'Mensaje' : 'Message'"
                required
            />
            <button class="primary-button" type="submit">
                {{ site.locale.value === 'es' ? 'Enviar mensaje' : 'Send message' }}
            </button>
            <p v-if="state === 'success'" class="success-text">
                {{ site.locale.value === 'es' ? 'Mensaje enviado correctamente.' : 'Message sent successfully.' }}
            </p>
            <p v-if="state === 'error'" class="error-text">
                {{ site.locale.value === 'es' ? 'No se pudo enviar.' : 'Could not send your message.' }}
            </p>
        </form>
    </section>
</template>
