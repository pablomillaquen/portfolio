<script setup>
import { computed } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
    selected: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['toggle']);

const toggleCategory = (slug) => {
    emit('toggle', slug);
};

const isSelected = (slug) => {
    return props.selected.includes(slug);
};
</script>

<template>
    <div class="category-filter">
        <button
            v-for="category in categories"
            :key="category.slug"
            :class="['filter-button', { active: isSelected(category.slug) }]"
            @click="toggleCategory(category.slug)"
        >
            {{ category.name }}
        </button>
    </div>
</template>
