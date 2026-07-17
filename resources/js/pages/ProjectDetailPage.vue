<script setup>
import { inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';

const site = inject('site');
const route = useRoute();
const project = ref(null);
const showVideoModal = ref(false);
const videoUrl = ref('');
const seoData = ref({});

const loadSeo = async () => {
    try {
        const { data } = await api.get(`/api/seo/project/${route.params.slug}`, {
            params: { locale: site.locale.value },
        });
        seoData.value = data;
    } catch {
        seoData.value = {};
    }
};

useHead(() => {
    const jsonLd = project.value ? {
        '@context': 'https://schema.org',
        '@type': 'CreativeWork',
        'name': project.value.title,
        'description': project.value.summary,
        'author': { '@type': 'Person', 'name': 'Pablo Millaquen' },
        'url': seoData.value.url || window.location.href,
        'image': project.value.coverImageUrl || seoData.value.image || '',
    } : null;

    return {
        title: seoData.value.title || 'Proyecto | Pablo Millaquen',
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
        script: jsonLd ? [{ type: 'application/ld+json', innerHTML: JSON.stringify(jsonLd) }] : [],
    };
});

const load = async () => {
    const { data } = await api.get(`/api/projects/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    project.value = data;
};

const openVideo = (url) => {
    videoUrl.value = url;
    showVideoModal.value = true;
};

const closeVideo = () => {
    showVideoModal.value = false;
    videoUrl.value = '';
};

const getYouTubeId = (url) => {
    const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^&?#]+)/);
    return match ? match[1] : null;
};

const getYouTubeThumbnail = (url) => {
    const id = getYouTubeId(url);
    return id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : url;
};

const getYouTubeEmbed = (url) => {
    const id = getYouTubeId(url);
    return id ? `https://www.youtube.com/embed/${id}?autoplay=1` : url;
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
    <div v-if="project" class="detail-layout">
        <RouterLink class="back-link" to="/projects">
            {{ site.locale.value === 'es' ? 'Volver a proyectos' : 'Back to projects' }}
        </RouterLink>

        <section class="hero-banner panel detail-hero">
            <img :src="project.coverImageUrl" :alt="project.title">
            <div>
                <h1>{{ project.title }}</h1>
                <p class="lead">{{ project.summary }}</p>
                <div class="meta-tags">
                    <span v-for="item in project.stack" :key="item">{{ item }}</span>
                </div>
                <div v-if="project.categories && project.categories.length > 0" class="meta-tags">
                    <span v-for="cat in project.categories" :key="cat.slug" class="tag-category">{{ cat.name }}</span>
                </div>
                <div v-if="project.capabilities && project.capabilities.length > 0" class="meta-tags">
                    <span v-for="cap in project.capabilities" :key="cap.slug" class="tag-capability">{{ cap.name }}</span>
                </div>
                <hr>
                <div class="cta-row">
                    <a v-if="project.demoUrl" class="primary-button" :href="project.demoUrl" target="_blank" rel="noreferrer">Demo</a>
                    <a v-if="project.repositoryUrl" class="secondary-button" :href="project.repositoryUrl" target="_blank" rel="noreferrer">Repo</a>
                </div>
            </div>
        </section>

        <section class="panel" v-if="project.problem">
            <h2>{{ site.locale.value === 'es' ? 'Problema' : 'Problem' }}</h2>
            <div class="article-body" v-html="project.problem"></div>
        </section>

        <section class="panel" v-if="project.approach">
            <h2>{{ site.locale.value === 'es' ? 'Enfoque' : 'Approach' }}</h2>
            <div class="article-body" v-html="project.approach"></div>
        </section>

        <section class="panel" v-if="project.contribution">
            <h2>{{ site.locale.value === 'es' ? 'Aporte' : 'Contribution' }}</h2>
            <div class="article-body" v-html="project.contribution"></div>
        </section>

        <section class="panel" v-if="project.whatItDemonstrates">
            <h2>{{ site.locale.value === 'es' ? 'Qué demuestra este trabajo' : 'What this work demonstrates' }}</h2>
            <div class="article-body" v-html="project.whatItDemonstrates"></div>
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

        <section class="panel" v-if="project.description">
            <h2>{{ site.locale.value === 'es' ? 'Descripción' : 'Description' }}</h2>
            <div class="article-body" v-html="project.description"></div>
        </section>

        <section class="panel">
            <h2>{{ site.locale.value === 'es' ? 'Galeria' : 'Gallery' }}</h2>
            <div class="media-grid">
                <template v-for="item in project.media" :key="item.id">
                    <img
                        v-if="item.kind === 'image'"
                        :src="item.url"
                        :alt="item.caption || project.title"
                    >
                    <button
                        v-else
                        class="video-thumbnail"
                        @click="openVideo(item.url)"
                    >
                        <img :src="getYouTubeThumbnail(item.url)" :alt="item.caption || project.title">
                        <span class="play-icon">▶</span>
                    </button>
                </template>
            </div>
        </section>

        <!-- Video Modal -->
        <Teleport to="body">
            <div v-if="showVideoModal" class="modal-overlay" @click.self="closeVideo">
                <div class="video-modal">
                    <button class="modal-close" @click="closeVideo">×</button>
                    <iframe
                        :src="getYouTubeEmbed(videoUrl)"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </Teleport>

        <section class="panel" v-if="project.relatedPosts && project.relatedPosts.length > 0">
            <h2>{{ site.locale.value === 'es' ? 'Publicaciones relacionadas' : 'Related posts' }}</h2>
            <div class="list-grid">
                <RouterLink
                    v-for="post in project.relatedPosts"
                    :key="post.id"
                    :to="`/posts/${post.slug}`"
                    class="list-card"
                >
                    <div>
                        <h3>{{ post.title }}</h3>
                        <p v-if="post.season">{{ post.season.name }} - {{ site.locale.value === 'es' ? 'Episodio' : 'Episode' }} {{ post.episodeNumber }}</p>
                    </div>
                </RouterLink>
            </div>
        </section>
    </div>
</template>
