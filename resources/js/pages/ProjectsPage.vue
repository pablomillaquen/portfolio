<script setup>
import { inject, onMounted, ref, watch } from 'vue';
import { RouterLink } from 'vue-router';
import { api } from '../services/api';

const site = inject('site');
const projects = ref([]);

const load = async () => {
    const { data } = await api.get('/api/projects', { params: { locale: site.locale.value } });
    projects.value = data;
};

watch(() => site.locale.value, load);
onMounted(load);
</script>

<template>
    <section class="panel">
        <div class="section-heading">
            <h1>{{ site.locale.value === 'es' ? 'Proyectos' : 'Projects' }}</h1>
        </div>
        <div class="project-grid">
            <RouterLink
                v-for="project in projects"
                :key="project.id"
                :to="`/projects/${project.slug}`"
                class="project-card"
            >
                <img :src="project.coverImageUrl" :alt="project.title">
                <div class="project-copy">
                    <h3>{{ project.title }}</h3>
                    <p>{{ project.summary }}</p>
                </div>
            </RouterLink>
        </div>
    </section>
</template>
