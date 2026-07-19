<script setup>
import { computed, inject, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import MobileNavDrawer from './MobileNavDrawer.vue';

const site = inject('site');
const route = useRoute();

const isMenuOpen = ref(false);

const brand = computed(() => site.settings.value?.home?.brand || 'PM');
const footerText = computed(() => site.settings.value?.footer?.copyright || 'All rights reserved.');
const year = new Date().getFullYear();

const links = [
    { to: '/posts', label: { es: 'Posts', en: 'Posts' } },
    { to: '/projects', label: { es: 'Proyectos', en: 'Projects' } },
    { to: '/courses', label: { es: 'Cursos', en: 'Courses' } },
    { to: '/contact', label: { es: 'Contacto', en: 'Contact' } },
];

function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
}

function closeMenu() {
    isMenuOpen.value = false;
}

watch(isMenuOpen, (open) => {
    if (open) {
        document.body.classList.add('menu-open');
    } else {
        document.body.classList.remove('menu-open');
    }
});
</script>

<template>
    <div class="site-shell">
        <a href="#main-content" class="skip-link">
            {{ site.locale.value === 'en' ? 'Skip to main content' : 'Saltar al contenido principal' }}
        </a>
        <header class="site-header">
            <RouterLink class="brand-mark" to="/">{{ brand }}</RouterLink>
            <nav class="primary-nav" :aria-label="site.locale.value === 'en' ? 'Main navigation' : 'Navegación principal'">
                <RouterLink
                    v-for="link in links"
                    :key="link.to"
                    :to="link.to"
                    :class="{ active: route.path.startsWith(link.to) }"
                >
                    {{ link.label[site.locale.value] }}
                </RouterLink>
            </nav>
            <div class="toolbar">
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
                <RouterLink class="ghost-button" to="/admin">Admin</RouterLink>
            </div>
            <button
                class="hamburger-btn"
                :aria-label="site.locale.value === 'en' ? 'Open menu' : 'Abrir menú'"
                :aria-expanded="isMenuOpen"
                @click="toggleMenu"
            >
                <span class="hamburger-icon" :class="{ 'is-open': isMenuOpen }">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </header>

        <MobileNavDrawer :is-open="isMenuOpen" @close="closeMenu" />

        <main id="main-content" tabindex="-1" class="page-frame">
            <slot />
        </main>

        <footer class="site-footer">
            <p>© {{ year }} Pablo Millaquen. {{ footerText }}</p>
            <div class="social-row">
                <a
                    v-for="link in site.socialLinks.value"
                    :key="link.id"
                    :href="link.url"
                    target="_blank"
                    rel="noreferrer"
                >
                    {{ link.label }}
                </a>
            </div>
        </footer>
    </div>
</template>
