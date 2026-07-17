<script setup>
import { computed, inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';
import FutureContentIndicator from '../components/FutureContentIndicator.vue';

const site = inject('site');
const route = useRoute();
const post = ref(null);
const seoData = ref({});

const locale = computed(() => site.locale.value);

const shareUrl = computed(() => {
    if (! post.value?.shareEnabled) {
        return null;
    }

    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(post.value.title);
    return `https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}`;
});

const loadSeo = async () => {
    try {
        const { data } = await api.get(`/api/seo/post/${route.params.slug}`, {
            params: { locale: site.locale.value },
        });
        seoData.value = data;
    } catch {
        seoData.value = {};
    }
};

useHead(() => {
    const jsonLd = post.value ? {
        '@context': 'https://schema.org',
        '@type': 'Article',
        'headline': post.value.title,
        'description': post.value.excerpt,
        'author': { '@type': 'Person', 'name': 'Pablo Millaquen' },
        'datePublished': seoData.value.publishedAt || '',
        'url': seoData.value.url || window.location.href,
        'image': post.value.coverImageUrl || seoData.value.image || '',
    } : null;

    return {
        title: seoData.value.title || 'Publicación | Pablo Millaquen',
        meta: [
            { name: 'description', content: seoData.value.description || '' },
            { property: 'og:title', content: seoData.value.title || '' },
            { property: 'og:description', content: seoData.value.description || '' },
            { property: 'og:image', content: seoData.value.image || '' },
            { property: 'og:url', content: seoData.value.url || '' },
            { property: 'og:type', content: 'article' },
            { property: 'article:published_time', content: seoData.value.publishedAt || '' },
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
        script: jsonLd ? [{ type: 'application/ld+json', innerHTML: JSON.stringify(jsonLd) }] : [],
    };
});

const load = async () => {
    const { data } = await api.get(`/api/posts/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    post.value = data;
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
