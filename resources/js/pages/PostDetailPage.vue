<script setup>
import { computed, inject, onMounted, onUnmounted, ref, watch, nextTick } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import { api } from '../services/api';
import FutureContentIndicator from '../components/FutureContentIndicator.vue';

const site = inject('site');
const route = useRoute();
const post = ref(null);
const seoData = ref({});
const tocItems = ref([]);
const activeTocId = ref('');

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

const extractToc = () => {
    nextTick(() => {
        const body = document.querySelector('.article-body');
        if (!body) {
            tocItems.value = [];
            return;
        }
        const headings = body.querySelectorAll('h2, h3');
        tocItems.value = Array.from(headings).map((el, i) => {
            if (!el.id) {
                el.id = 'toc-' + i;
            }
            return {
                id: el.id,
                text: el.textContent.trim(),
                level: el.tagName === 'H3' ? 3 : 2,
            };
        });
    });
};

const scrollToSection = (id) => {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

let observer = null;

const setupObserver = () => {
    nextTick(() => {
        const body = document.querySelector('.article-body');
        if (!body) return;
        const headings = body.querySelectorAll('h2, h3');
        if (headings.length === 0) return;

        observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        activeTocId.value = entry.target.id;
                    }
                }
            },
            { rootMargin: '-20% 0px -70% 0px' }
        );

        headings.forEach((h) => observer.observe(h));
    });
};

const load = async () => {
    const { data } = await api.get(`/api/posts/${route.params.slug}`, {
        params: { locale: site.locale.value },
    });
    post.value = data;
    extractToc();
    setupObserver();
};

watch(() => [site.locale.value, route.params.slug], () => {
    load();
    loadSeo();
});

onMounted(() => {
    load();
    loadSeo();
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});
</script>

<template>
    <div v-if="post" class="detail-layout">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <RouterLink to="/">{{ locale === 'es' ? 'Inicio' : 'Home' }}</RouterLink>
            <span aria-hidden="true">/</span>
            <RouterLink to="/posts">{{ locale === 'es' ? 'Publicaciones' : 'Posts' }}</RouterLink>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ post.title }}</span>
        </nav>

        <div class="post-detail-layout">
            <section class="panel article-card">
                <img v-if="post.coverImageUrl" loading="lazy" class="article-cover" :src="post.coverImageUrl" :alt="post.title" width="320" height="180">
                <h1>{{ post.title }}</h1>
                <p class="lead">{{ post.excerpt }}</p>
                <div class="article-body" v-html="post.content"></div>
                <div class="cta-row">
                    <a v-if="shareUrl" class="secondary-button" :href="shareUrl" target="_blank" rel="noreferrer">
                        {{ locale === 'es' ? 'Compartir en LinkedIn' : 'Share on LinkedIn' }}
                    </a>
                </div>
            </section>

            <aside class="post-sidebar">
                <div class="panel" v-if="tocItems.length > 0">
                    <p class="sidebar-metadata meta-label">{{ locale === 'es' ? 'En este artículo' : 'In this article' }}</p>
                    <ul class="post-toc">
                        <li v-for="item in tocItems" :key="item.id" :class="{ 'toc-sub': item.level === 3 }">
                            <a
                                :href="'#' + item.id"
                                :class="{ active: activeTocId === item.id }"
                                @click.prevent="scrollToSection(item.id)"
                            >{{ item.text }}</a>
                        </li>
                    </ul>
                </div>

                <div class="panel sidebar-metadata">
                    <div v-if="post.publishedAt">
                        <p class="meta-label">{{ locale === 'es' ? 'Publicado el' : 'Published on' }}</p>
                        <p class="meta-value">{{ post.publishedAt }}</p>
                    </div>
                    <div v-if="post.season">
                        <p class="meta-label">{{ locale === 'es' ? 'Temporada' : 'Season' }}</p>
                        <p class="meta-value">{{ post.season.name }} — {{ locale === 'es' ? 'Episodio' : 'Episode' }} {{ post.episodeNumber }}</p>
                    </div>
                    <div v-if="shareUrl">
                        <p class="meta-label">{{ locale === 'es' ? 'Compartir' : 'Share' }}</p>
                        <a class="secondary-button" :href="shareUrl" target="_blank" rel="noreferrer" style="font-size:0.8rem;padding:0.5rem 1rem;">
                            {{ locale === 'es' ? 'LinkedIn' : 'LinkedIn' }}
                        </a>
                    </div>
                </div>

                <div class="panel sidebar-related" v-if="post.relatedProject">
                    <p class="meta-label">{{ locale === 'es' ? 'Caso de estudio relacionado' : 'Related case study' }}</p>
                    <RouterLink :to="`/projects/${post.relatedProject.slug}`" class="project-card">
                        <div class="project-copy">
                            <h3>{{ post.relatedProject.title }}</h3>
                        </div>
                    </RouterLink>
                </div>
            </aside>
        </div>

        <section class="panel" v-if="post.relatedProjects && post.relatedProjects.length > 0">
            <h2>{{ locale === 'es' ? 'Proyectos relacionados' : 'Related projects' }}</h2>
            <div class="list-grid">
                <RouterLink
                    v-for="project in post.relatedProjects"
                    :key="project.id"
                    :to="`/projects/${project.slug}`"
                    class="list-card"
                >
                    <div>
                        <h3>{{ project.title }}</h3>
                        <p>{{ project.summary }}</p>
                    </div>
                </RouterLink>
            </div>
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
            <div class="season-discover" v-if="post.season">
                <RouterLink :to="`/posts?season=${post.season.slug}`" class="secondary-button">
                    {{ locale === 'es' ? 'Descubrir la temporada completa' : 'Discover the full season' }}
                </RouterLink>
            </div>
        </section>

        <section class="panel">
            <FutureContentIndicator type="video" />
        </section>
    </div>
</template>
