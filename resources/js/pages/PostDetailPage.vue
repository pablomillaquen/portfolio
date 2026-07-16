<script setup>
import { computed, inject, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { api } from '../services/api';

const site = inject('site');
const route = useRoute();
const post = ref(null);

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
            {{ site.locale.value === 'es' ? 'Volver a posts' : 'Back to posts' }}
        </RouterLink>

        <section class="panel article-card">
            <img v-if="post.coverImageUrl" class="article-cover" :src="post.coverImageUrl" :alt="post.title">
            <p class="eyebrow">{{ post.publishedAt }}</p>
            <h1>{{ post.title }}</h1>
            <p class="lead">{{ post.excerpt }}</p>
            <div class="article-body" v-html="post.content"></div>
            <div class="cta-row">
                <a v-if="shareUrl" class="secondary-button" :href="shareUrl" target="_blank" rel="noreferrer">
                    Compartir en LinkedIn
                </a>
            </div>
        </section>
    </div>
</template>
