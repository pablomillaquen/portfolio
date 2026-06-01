<script setup>
import { computed, inject, onMounted, ref, watch } from 'vue';
import { api } from '../services/api';

const site = inject('site');
const posts = ref([]);
const query = ref('');

const load = async () => {
    const { data } = await api.get('/api/posts', { params: { locale: site.locale.value } });
    posts.value = data;
};

const filtered = computed(() =>
    posts.value.filter((post) =>
        `${post.title} ${post.excerpt}`.toLowerCase().includes(query.value.toLowerCase())
    )
);

watch(() => site.locale.value, load);
onMounted(load);
</script>

<template>
    <section class="panel">
        <div class="section-heading">
            <h1>Posts</h1>
        </div>
        <input
            v-model="query"
            class="search-input"
            :placeholder="site.locale.value === 'es' ? 'Buscar posts...' : 'Search posts...'"
        >
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
                <div>
                    <h3>{{ post.title }}</h3>
                    <p>{{ post.excerpt }}</p>
                </div>
                <span>{{ post.publishedAt }}</span>
            </component>
        </div>
    </section>
</template>
