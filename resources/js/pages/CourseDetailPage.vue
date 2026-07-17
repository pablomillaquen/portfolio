<script setup>
import { inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';

const site = inject('site');
const route = useRoute();
const course = ref(null);
const seoData = ref({});

const loadSeo = async () => {
    try {
        const { data } = await api.get(`/api/seo/course/${route.params.slug}`, {
            params: { locale: site.locale.value },
        });
        seoData.value = data;
    } catch {
        seoData.value = {};
    }
};

useHead(() => ({
    title: seoData.value.title || 'Curso | Pablo Millaquen',
    meta: [
        { name: 'description', content: seoData.value.description || '' },
        { property: 'og:title', content: seoData.value.title || '' },
        { property: 'og:description', content: seoData.value.description || '' },
        { property: 'og:image', content: seoData.value.image || '' },
        { property: 'og:url', content: seoData.value.url || '' },
        { property: 'og:type', content: 'article' },
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:title', content: seoData.value.title || '' },
        { name: 'twitter:description', content: seoData.value.description || '' },
        { name: 'twitter:image', content: seoData.value.image || '' },
    ],
    link: [
        { rel: 'canonical', href: seoData.value.url || '' },
        { rel: 'alternate', hreflang: 'es', href: seoData.value.alternates?.es || '' },
        { rel: 'alternate', hreflang: 'en', href: seoData.value.alternates?.en || '' },
    ],
}));

const load = async () => {
    const { data } = await api.get(`/api/courses/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    course.value = data;
};

watch(() => [site.locale.value, route.params.slug], () => {
    load();
    loadSeo();
});
onMounted(() => {
    load();
    loadSeo();
});
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
