<script setup>
import { computed, inject, onMounted, ref, watch } from 'vue';
import { api } from '../services/api';
import SeasonList from '../components/SeasonList.vue';

const site = inject('site');
const posts = ref([]);
const seasons = ref([]);
const query = ref('');
const selectedSeason = ref(null);

const locale = computed(() => site.locale.value);

const loadPosts = async () => {
    const params = { locale: site.locale.value };
    if (selectedSeason.value) {
        params.season = selectedSeason.value;
    }
    const { data } = await api.get('/api/posts', { params });
    posts.value = data;
};

const loadSeasons = async () => {
    const { data } = await api.get('/api/seasons', { params: { locale: site.locale.value } });
    seasons.value = data.data;
};

const selectSeason = (slug) => {
    selectedSeason.value = selectedSeason.value === slug ? null : slug;
    loadPosts();
};

const filtered = computed(() =>
    posts.value.filter((post) =>
        `${post.title} ${post.excerpt}`.toLowerCase().includes(query.value.toLowerCase())
    )
);

const loadData = async () => {
    await Promise.all([loadPosts(), loadSeasons()]);
};

watch(() => site.locale.value, loadData);
onMounted(loadData);
</script>

<template>
    <section class="panel">
        <div class="section-heading">
            <h1>{{ locale === 'es' ? 'Publicaciones' : 'Posts' }}</h1>
        </div>
        <input
            v-model="query"
            class="search-input"
            :placeholder="locale === 'es' ? 'Buscar posts...' : 'Search posts...'"
        >
        <SeasonList
            v-if="seasons.length > 0"
            :seasons="seasons"
            @select="selectSeason"
        />
        <div class="list-grid posts-list">
            <component
                :is="post.type === 'external' ? 'a' : 'router-link'"
                v-for="post in filtered"
                :key="post.id"
                :to="post.type === 'internal' ? `/posts/${post.slug}` : undefined"
                :href="post.type === 'external' ? post.externalUrl : undefined"
                :target="post.type === 'external' ? '_blank' : undefined"
                :rel="post.type === 'external' ? 'noreferrer' : undefined"
                class="list-card"
            >
                <img v-if="post.coverImageUrl" :src="post.coverImageUrl" :alt="post.title" class="post-cover">
                <div>
                    <h3>{{ post.title }}</h3>
                    <p>{{ post.excerpt }}</p>
                    <p v-if="post.season" class="season-badge">{{ post.season.name }} - {{ locale === 'es' ? 'Episodio' : 'Episode' }} {{ post.episodeNumber }}</p>
                </div>
                <span>{{ post.publishedAt }}</span>
            </component>
        </div>
    </section>
</template>
