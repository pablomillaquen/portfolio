<script setup>
defineProps({
    seasons: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div class="season-list">
        <article v-for="season in seasons" :key="season.id" class="season-card">
            <div class="season-header">
                <h3>{{ season.name }}</h3>
                <span :class="['season-status', season.status]">{{ season.status }}</span>
            </div>
            <p v-if="season.description">{{ season.description }}</p>
            <p class="season-count">{{ season.postsCount }} {{ season.postsCount === 1 ? 'episode' : 'episodes' }}</p>
        </article>
    </div>
</template>
