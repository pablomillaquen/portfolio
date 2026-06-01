<script setup>
import { computed, inject } from 'vue';
import { RouterLink, useRoute } from 'vue-router';

const site = inject('site');
const route = useRoute();

const brand = computed(() => site.settings.value?.home?.brand || 'PM');
const footerText = computed(() => site.settings.value?.footer?.copyright || 'All rights reserved.');
const year = new Date().getFullYear();

const links = [
    { to: '/posts', label: { es: 'Posts', en: 'Posts' } },
    { to: '/projects', label: { es: 'Proyectos', en: 'Projects' } },
    { to: '/courses', label: { es: 'Cursos', en: 'Courses' } },
    { to: '/contact', label: { es: 'Contacto', en: 'Contact' } },
];
</script>

<template>
    <div class="site-shell">
        <header class="site-header">
            <RouterLink class="brand-mark" to="/">{{ brand }}</RouterLink>
            <nav class="primary-nav">
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
                <button class="ghost-button" @click="site.toggleTheme">
                    {{ site.theme.value === 'dark' ? 'Light' : 'Dark' }}
                </button>
                <button
                    class="ghost-button"
                    @click="site.setLocale(site.locale.value === 'en' ? 'es' : 'en')"
                >
                    {{ site.locale.value.toUpperCase() }}
                </button>
                <RouterLink class="ghost-button" to="/admin">Admin</RouterLink>
            </div>
        </header>

        <main class="page-frame">
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
