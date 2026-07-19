<script setup>
import { computed, inject, onMounted, onUnmounted, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
});

const emit = defineEmits(['close']);

const site = inject('site');
const route = useRoute();

const links = [
    { to: '/', label: { es: 'Inicio', en: 'Home' } },
    { to: '/posts', label: { es: 'Posts', en: 'Posts' } },
    { to: '/projects', label: { es: 'Proyectos', en: 'Projects' } },
    { to: '/courses', label: { es: 'Cursos', en: 'Courses' } },
    { to: '/contact', label: { es: 'Contacto', en: 'Contact' } },
];

function close() {
    emit('close');
}

function onKeydown(e) {
    if (e.key === 'Escape' && props.isOpen) {
        close();
    }
}

watch(() => props.isOpen, (open) => {
    if (open) {
        document.addEventListener('keydown', onKeydown);
    } else {
        document.removeEventListener('keydown', onKeydown);
    }
});

onUnmounted(() => {
    document.removeEventListener('keydown', onKeydown);
});
</script>

<template>
    <div
        class="nav-overlay"
        :class="{ 'is-visible': isOpen }"
        @click="close"
    />
    <div
        class="nav-drawer"
        :class="{ 'is-open': isOpen }"
        role="dialog"
        aria-modal="true"
        :aria-label="site.locale.value === 'en' ? 'Navigation menu' : 'Menú de navegación'"
    >
        <div class="nav-drawer-header">
            <span class="nav-drawer-title">{{ site.settings.value?.home?.brand || 'PM' }}</span>
            <button
                class="nav-drawer-close"
                :aria-label="site.locale.value === 'en' ? 'Close menu' : 'Cerrar menú'"
                @click="close"
            >
                &times;
            </button>
        </div>
        <nav class="nav-drawer-links" :aria-label="site.locale.value === 'en' ? 'Navigation' : 'Navegación'">
            <RouterLink
                v-for="link in links"
                :key="link.to"
                :to="link.to"
                class="nav-drawer-link"
                :class="{ active: route.path === link.to }"
                @click="close"
            >
                {{ link.label[site.locale.value] }}
            </RouterLink>
        </nav>
        <div class="nav-drawer-controls">
            <button
                class="ghost-button"
                :aria-label="site.locale.value === 'en'
                    ? (site.theme.value === 'dark' ? 'Switch to light mode' : 'Switch to dark mode')
                    : (site.theme.value === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro')"
                @click="site.toggleTheme"
            >
                {{ site.theme.value === 'dark' ? 'Light' : 'Dark' }}
            </button>
            <button
                class="ghost-button"
                :aria-label="site.locale.value === 'en' ? 'Switch to Spanish' : 'Cambiar a inglés'"
                @click="site.setLocale(site.locale.value === 'en' ? 'es' : 'en')"
            >
                {{ site.locale.value.toUpperCase() }}
            </button>
            <RouterLink class="ghost-button" to="/admin" @click="close">Admin</RouterLink>
        </div>
    </div>
</template>
