import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import PublicShell from '../../components/PublicShell.vue';

vi.mock('vue-router', () => ({
    useRoute: () => ({
        path: '/',
    }),
    RouterLink: {
        template: '<a><slot /></a>',
        props: ['to'],
    },
}));

const mockSite = {
    locale: { value: 'en' },
    theme: { value: 'dark' },
    settings: { value: { home: { brand: 'TestBrand' }, footer: { copyright: 'Test rights' } } },
    socialLinks: { value: [] },
    toggleTheme: vi.fn(),
    setLocale: vi.fn(),
};

const globalProvide = {
    site: mockSite,
};

describe('PublicShell', () => {
    const createWrapper = () =>
        mount(PublicShell, {
            global: {
                plugins: [],
                provide: globalProvide,
            },
        });

    it('has skip link', () => {
        const wrapper = createWrapper();
        const skipLink = wrapper.find('a.skip-link');
        expect(skipLink.exists()).toBe(true);
        expect(skipLink.attributes('href')).toBe('#main-content');
    });

    it('nav has aria-label', () => {
        const wrapper = createWrapper();
        const nav = wrapper.find('nav.primary-nav');
        expect(nav.exists()).toBe(true);
        expect(nav.attributes('aria-label')).toBe('Main navigation');
    });

    it('theme toggle button has aria-label', () => {
        const wrapper = createWrapper();
        const buttons = wrapper.findAll('button.ghost-button');
        const themeButton = buttons[0];
        expect(themeButton.attributes('aria-label')).toBe('Switch to light mode');
    });
});
