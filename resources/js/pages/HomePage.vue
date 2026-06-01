<script setup>
import { computed, inject, onMounted, ref, watch } from 'vue';
import { RouterLink } from 'vue-router';

const site = inject('site');
const loading = ref(true);
const payload = ref(null);

const locale = computed(() => site.locale.value);

const loadData = async () => {
    loading.value = true;
    payload.value = await site.loadHome();
    loading.value = false;
};

watch(() => site.locale.value, loadData);
onMounted(loadData);
</script>

<template>
    <div v-if="loading" class="empty-state">Loading...</div>
    <div v-else class="home-grid">
        <section class="hero-card panel">
            <div>
                <p class="eyebrow">{{ locale === 'es' ? 'Portafolio' : 'Portfolio' }}</p>
                <h1>{{ payload.settings.home.headline }}</h1>
                <p class="lead">{{ payload.settings.home.bio }}</p>
                <div class="cta-row">
                    <RouterLink class="primary-button" to="/projects">
                        {{ locale === 'es' ? 'Ver proyectos' : 'View projects' }}
                    </RouterLink>
                    <RouterLink class="secondary-button" to="/contact">
                        {{ locale === 'es' ? 'Contactar' : 'Contact me' }}
                    </RouterLink>
                </div>
            </div>
            <img
                class="profile-image"
                :src="payload.settings.home.profileImage"
                alt="Profile"
            >
        </section>

        <section class="panel">
            <h2>{{ locale === 'es' ? 'Stack' : 'Technology stack' }}</h2>
            <div class="stack-grid">
                <article v-for="group in payload.settings.stack" :key="group.title" class="stack-card">
                    <h3>{{ group.title }}</h3>
                    <p>{{ group.items.join(', ') }}</p>
                </article>
            </div>
        </section>

        <section class="panel">
            <div class="section-heading">
                <h2>{{ locale === 'es' ? 'Experiencia' : 'Experience' }}</h2>
            </div>
            <div class="timeline">
                <article v-for="item in payload.settings.experience" :key="item.company" class="timeline-item">
                    <div>
                        <h3>{{ item.role }}</h3>
                        <p class="muted">{{ item.company }}</p>
                    </div>
                    <span class="timeline-period">{{ item.period }}</span>
                    <ul>
                        <li v-for="bullet in item.bullets" :key="bullet">{{ bullet }}</li>
                    </ul>
                </article>
            </div>
        </section>

        <section class="panel">
            <div class="section-heading">
                <h2>{{ locale === 'es' ? 'Posts recientes' : 'Recent posts' }}</h2>
                <RouterLink to="/posts">{{ locale === 'es' ? 'Todos' : 'All posts' }}</RouterLink>
            </div>
            <div class="list-grid">
                <RouterLink
                    v-for="post in payload.posts"
                    :key="post.id"
                    :to="post.type === 'external' ? '/posts' : `/posts/${post.slug}`"
                    class="list-card"
                >
                    <div>
                        <h3>{{ post.title }}</h3>
                        <p>{{ post.excerpt }}</p>
                    </div>
                    <span>{{ post.publishedAt }}</span>
                </RouterLink>
            </div>
        </section>

        <section class="panel">
            <div class="section-heading">
                <h2>{{ locale === 'es' ? 'Proyectos destacados' : 'Featured projects' }}</h2>
                <RouterLink to="/projects">{{ locale === 'es' ? 'Todos' : 'All projects' }}</RouterLink>
            </div>
            <div class="project-grid">
                <RouterLink
                    v-for="project in payload.projects"
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
    </div>
</template>
