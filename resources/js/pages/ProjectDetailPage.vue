<script setup>
import { inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { api } from '../services/api';

const site = inject('site');
const route = useRoute();
const project = ref(null);

const load = async () => {
    const { data } = await api.get(`/api/projects/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    project.value = data;
};

watch(() => [site.locale.value, route.params.slug], load);
onMounted(load);
</script>

<template>
    <div v-if="project" class="detail-layout">
        <RouterLink class="back-link" to="/projects">
            {{ site.locale.value === 'es' ? 'Volver a proyectos' : 'Back to projects' }}
        </RouterLink>

        <section class="hero-banner panel detail-hero">
            <img :src="project.coverImageUrl" :alt="project.title">
            <div>
                <h1>{{ project.title }}</h1>
                <p class="lead">{{ project.description }}</p>
                <div class="meta-tags">
                    <span v-for="item in project.stack" :key="item">{{ item }}</span>
                </div>
                <div class="cta-row">
                    <a v-if="project.demoUrl" class="primary-button" :href="project.demoUrl" target="_blank" rel="noreferrer">Demo</a>
                    <a v-if="project.repositoryUrl" class="secondary-button" :href="project.repositoryUrl" target="_blank" rel="noreferrer">Repo</a>
                </div>
            </div>
        </section>

        <section class="panel">
            <h2>{{ site.locale.value === 'es' ? 'Detalles' : 'Details' }}</h2>
            <div class="detail-grid">
                <article v-for="item in project.details" :key="`${item.label}-${item.value}`" class="detail-card">
                    <p class="eyebrow">{{ item.label }}</p>
                    <h3>{{ item.value }}</h3>
                </article>
            </div>
        </section>

        <section class="panel">
            <h2>{{ site.locale.value === 'es' ? 'Galeria' : 'Gallery' }}</h2>
            <div class="media-grid">
                <template v-for="item in project.media" :key="item.id">
                    <img v-if="item.kind === 'image'" :src="item.url" :alt="item.caption || project.title">
                    <iframe
                        v-else
                        :src="item.url"
                        :title="item.caption || project.title"
                        frameborder="0"
                        allowfullscreen
                    />
                </template>
            </div>
        </section>
    </div>
</template>
