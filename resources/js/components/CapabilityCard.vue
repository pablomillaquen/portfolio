<script setup>
defineProps({
    capability: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <article class="capability-card">
        <h3>{{ capability.name }}</h3>
        <p v-if="capability.description">{{ capability.description }}</p>
    </article>
</template>
