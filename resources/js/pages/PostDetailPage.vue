<script setup>
import { computed, inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { api } from '../services/api';
import FutureContentIndicator from '../components/FutureContentIndicator.vue';

const site = inject('site');
const route = useRoute();
const post = ref(null);

const locale = computed(() => site.locale.value);

const shareUrl = computed(() => {
    if (! post.value?.shareEnabled) {
        return null;
    }

    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(post.value.title);
    return `https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}`;
});

const load = async () => {
    const { data } = await api.get(`/api/posts/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    post.value = data;
};

watch(() => [site.locale.value, route.params.slug], load);
onMounted(load);
</script>

<template>
    <div v-if="post" class="detail-layout">
        <RouterLink class="back-link" to="/posts">
            {{ locale === 'es' ? 'Volver a posts' : 'Back to posts' }}
        </RouterLink>

        <section class="panel article-card">
            <img v-if="post.coverImageUrl" class="article-cover" :src="post.coverImageUrl" :alt="post.title">
            <p class="eyebrow">{{ post.publishedAt }}</p>
            <h1>{{ post.title }}</h1>
            <p v-if="post.season" class="season-badge">{{ post.season.name }} - {{ locale === 'es' ? 'Episodio' : 'Episode' }} {{ post.episodeNumber }}</p>
            <p class="lead">{{ post.excerpt }}</p>
            <div class="article-body" v-html="post.content"></div>
            <div class="cta-row">
                <a v-if="shareUrl" class="secondary-button" :href="shareUrl" target="_blank" rel="noreferrer">
                    {{ locale === 'es' ? 'Compartir en LinkedIn' : 'Share on LinkedIn' }}
                </a>
            </div>
        </section>

        <section class="panel" v-if="post.relatedProject">
            <h2>{{ locale === 'es' ? 'Caso de estudio relacionado' : 'Related case study' }}</h2>
            <RouterLink :to="`/projects/${post.relatedProject.slug}`" class="project-card">
                <div class="project-copy">
                    <h3>{{ post.relatedProject.title }}</h3>
                </div>
            </RouterLink>
        </section>

        <section class="panel" v-if="post.navigation">
            <div class="post-navigation">
                <RouterLink
                    v-if="post.navigation.previous"
                    :to="`/posts/${post.navigation.previous.slug}`"
                    class="nav-link previous"
                >
                    <span class="nav-label">{{ locale === 'es' ? 'Anterior' : 'Previous' }}</span>
                    <span class="nav-title">{{ post.navigation.previous.title }}</span>
                </RouterLink>
                <div v-else></div>
                <RouterLink
                    v-if="post.navigation.next"
                    :to="`/posts/${post.navigation.next.slug}`"
                    class="nav-link next"
                >
                    <span class="nav-label">{{ locale === 'es' ? 'Siguiente' : 'Next' }}</span>
                    <span class="nav-title">{{ post.navigation.next.title }}</span>
                </RouterLink>
            </div>
        </section>

        <section class="panel">
            <FutureContentIndicator type="video" />
        </section>
    </div>
</template>
