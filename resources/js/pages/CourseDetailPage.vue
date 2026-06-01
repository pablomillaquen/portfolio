<script setup>
import { inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { api } from '../services/api';

const site = inject('site');
const route = useRoute();
const course = ref(null);

const load = async () => {
    const { data } = await api.get(`/api/courses/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    course.value = data;
};

watch(() => [site.locale.value, route.params.slug], load);
onMounted(load);
</script>

<template>
    <div v-if="course" class="detail-layout">
        <RouterLink class="back-link" to="/courses">
            {{ site.locale.value === 'es' ? 'Volver a cursos' : 'Back to courses' }}
        </RouterLink>

        <section class="panel">
            <p class="eyebrow">{{ course.issuedAt }}</p>
            <h1>{{ course.name }}</h1>
            <p class="lead">{{ course.issuer }}</p>
            <div class="cta-row" style="margin-top: 1.5rem;">
                <a v-if="course.url" class="primary-button" :href="course.url" target="_blank" rel="noreferrer">
                    {{ site.locale.value === 'es' ? 'Ver credencial' : 'View credential' }}
                </a>
            </div>
            <p v-if="course.credentialId" class="muted" style="margin-top: 1rem;">
                {{ site.locale.value === 'es' ? 'ID: ' : 'ID: ' }}{{ course.credentialId }}
            </p>
        </section>
    </div>
</template>
