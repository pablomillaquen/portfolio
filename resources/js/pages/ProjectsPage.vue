<script setup>
import { inject, onMounted, ref, watch, computed } from 'vue';
import { RouterLink } from 'vue-router';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';
import CategoryFilter from '../components/CategoryFilter.vue';
import { useAnnouncer } from '../composables/useAnnouncer';

const site = inject('site');
const { announce } = useAnnouncer();
const projects = ref([]);
const categories = ref([]);
const selectedCategories = ref([]);

const locale = computed(() => site.locale.value);

useHead({
    title: 'Proyectos | Pablo Millaquen',
    meta: [
        { name: 'description', content: 'Explora los proyectos de desarrollo de software e investigación de Pablo Millaquen.' },
        { property: 'og:title', content: 'Proyectos | Pablo Millaquen' },
        { property: 'og:description', content: 'Explora los proyectos de desarrollo de software e investigación de Pablo Millaquen.' },
        { property: 'og:type', content: 'website' },
    ],
});

const loadProjects = async () => {
    const params = { locale: site.locale.value };
    if (selectedCategories.value.length > 0) {
        params.category = selectedCategories.value.join(',');
    }
    const { data } = await api.get('/api/projects', { params });
    projects.value = data;
};

const loadCategories = async () => {
    const { data } = await api.get('/api/categories', { params: { locale: site.locale.value } });
    categories.value = data.data;
};

const toggleCategory = (slug) => {
    const index = selectedCategories.value.indexOf(slug);
    if (index === -1) {
        selectedCategories.value.push(slug);
    } else {
        selectedCategories.value.splice(index, 1);
    }
    loadProjects().then(() => {
        announce(
            `${projects.value.length} ${locale.value === 'es' ? 'resultados' : 'results'}`,
            'polite'
        );
    });
};

const loadData = async () => {
    await Promise.all([loadProjects(), loadCategories()]);
};

watch(() => site.locale.value, loadData);
onMounted(loadData);
</script>

<template>
    <section class="panel">
        <div class="section-heading">
            <h1>{{ locale === 'es' ? 'Proyectos' : 'Projects' }}</h1>
        </div>
        <CategoryFilter
            v-if="categories.length > 0"
            :categories="categories"
            :selected="selectedCategories"
            @toggle="toggleCategory"
        />
        <div class="project-grid">
            <RouterLink
                v-for="project in projects"
                :key="project.id"
                :to="`/projects/${project.slug}`"
                class="project-card"
            >
                <img loading="lazy" :src="project.coverImageUrl" :alt="project.title" width="320" height="180">
                <div class="project-copy">
                    <h3>{{ project.title }}</h3>
                    <p>{{ project.summary }}</p>
                </div>
            </RouterLink>
        </div>
        <p v-if="projects.length === 0" class="empty-state">
            {{ locale === 'es' ? 'No hay proyectos en esta categoría.' : 'No projects in this category.' }}
        </p>
    </section>
</template>
