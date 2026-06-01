<script setup>
import { inject, onMounted, ref, watch } from 'vue';
import { RouterLink } from 'vue-router';
import { api } from '../services/api';

const site = inject('site');
const courses = ref([]);

const load = async () => {
    const { data } = await api.get('/api/courses', { params: { locale: site.locale.value } });
    courses.value = data;
};

watch(() => site.locale.value, load);
onMounted(load);
</script>

<template>
    <section class="panel">
        <div class="section-heading">
            <h1>{{ site.locale.value === 'es' ? 'Cursos' : 'Courses' }}</h1>
        </div>
        <div class="list-grid courses-list">
            <RouterLink
                v-for="course in courses"
                :key="course.id"
                :to="`/courses/${course.slug}`"
                class="list-card"
            >
                <div>
                    <h3>{{ course.name }}</h3>
                    <p>{{ course.issuer }}</p>
                </div>
                <span>{{ course.issuedAt }}</span>
            </RouterLink>
        </div>
    </section>
</template>
