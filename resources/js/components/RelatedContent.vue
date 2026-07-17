<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    items: {
        type: Array,
        default: () => [],
    },
    type: {
        type: String,
        default: 'posts',
    },
});
</script>

<template>
    <div class="related-content" v-if="items.length > 0">
        <h3>{{ title }}</h3>
        <div class="related-list">
            <RouterLink
                v-for="item in items"
                :key="item.id"
                :to="type === 'posts' ? `/posts/${item.slug}` : `/projects/${item.slug}`"
                class="related-item"
            >
                <span class="related-title">{{ item.title }}</span>
                <span v-if="item.season" class="related-meta">{{ item.season.name }} - Episode {{ item.episodeNumber }}</span>
            </RouterLink>
        </div>
    </div>
</template>
