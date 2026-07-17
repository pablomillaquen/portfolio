<script setup>
defineProps({
    type: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="future-content-indicator">
        <span class="indicator-icon">🎬</span>
        <span class="indicator-text">{{ type === 'video' ? 'Video coming soon' : 'Coming soon' }}</span>
    </div>
</template>
